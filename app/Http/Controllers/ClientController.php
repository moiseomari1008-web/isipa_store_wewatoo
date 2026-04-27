<?php

namespace App\Http\Controllers;

use App\Models\CategorieProduit;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Panier;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\ProduitsPanier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function boutique(Request $request): View
    {
        $categorieId = $request->integer('categorie');
        $search = trim((string) $request->string('q'));

        $produits = Produit::with('categorie')
            ->where('statut', 'disponible')
            ->when($categorieId, fn ($query) => $query->where('id_categories', $categorieId))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('nom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('date_ajout')
            ->paginate(9)
            ->withQueryString();

        $categories = CategorieProduit::withCount([
            'produits as produits_disponibles_count' => fn ($query) => $query->where('statut', 'disponible'),
        ])->orderBy('nom')->get();

        return view('client.boutique', [
            'produits' => $produits,
            'categories' => $categories,
            'categorieActive' => $categorieId,
            'search' => $search,
        ]);
    }

    public function panier(): View
    {
        $panier = $this->resolvePanier();
        $items = $panier->produitsPaniers()->with('produit.categorie')->get();

        return view('client.panier', [
            'items' => $items,
            'total' => $this->sumCart($items),
        ]);
    }

    public function ajouterAuPanier(Request $request, Produit $produit): RedirectResponse
    {
        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        if ($produit->statut !== 'disponible' || $produit->stock < $validated['quantite']) {
            return back()->with('client_error', 'Ce produit n\'est pas disponible dans la quantite demandee.');
        }

        $panier = $this->resolvePanier();

        $ligne = $panier->produitsPaniers()->where('id_produit', $produit->id)->first();
        $nouvelleQuantite = ($ligne?->quantite ?? 0) + $validated['quantite'];

        if ($nouvelleQuantite > $produit->stock) {
            return back()->with('client_error', 'La quantite totale depasse le stock disponible.');
        }

        if ($ligne) {
            $ligne->update(['quantite' => $nouvelleQuantite]);
        } else {
            $panier->produitsPaniers()->create([
                'id_produit' => $produit->id,
                'quantite' => $validated['quantite'],
            ]);
        }

        return redirect()->route('panier.index')->with('client_success', 'Produit ajoute au panier.');
    }

    public function mettreAJourPanier(Request $request, ProduitsPanier $produitsPanier): RedirectResponse
    {
        $this->assertCartOwnership($produitsPanier);

        $validated = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantite'] > $produitsPanier->produit->stock) {
            return back()->with('client_error', 'La quantite demandee depasse le stock disponible.');
        }

        $produitsPanier->update($validated);

        return back()->with('client_success', 'Panier mis a jour.');
    }

    public function supprimerDuPanier(ProduitsPanier $produitsPanier): RedirectResponse
    {
        $this->assertCartOwnership($produitsPanier);
        $produitsPanier->delete();

        return back()->with('client_success', 'Produit retire du panier.');
    }

    public function checkout(): View
    {
        $panier = $this->resolvePanier();
        $items = $panier->produitsPaniers()->with('produit.categorie')->get();

        abort_if($items->isEmpty(), 404);

        return view('client.checkout', [
            'items' => $items,
            'total' => $this->sumCart($items),
            'user' => Auth::user(),
            'modesPaiement' => ['Mobile Money', 'Carte bancaire', 'Virement bancaire', 'Paiement a la livraison'],
        ]);
    }

    public function passerCommande(Request $request): RedirectResponse
    {
        $panier = $this->resolvePanier();
        $items = $panier->produitsPaniers()->with('produit')->get();

        if ($items->isEmpty()) {
            return redirect()->route('panier.index')->with('client_error', 'Votre panier est vide.');
        }

        $validated = $request->validate([
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'type_paiement' => ['required', Rule::in(['Mobile Money', 'Carte bancaire', 'Virement bancaire', 'Paiement a la livraison'])],
            'num_compte' => ['nullable', 'string', 'max:100'],
            'reference_transaction' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($items as $item) {
            if ($item->quantite > $item->produit->stock) {
                return redirect()->route('panier.index')->with('client_error', "Le stock du produit {$item->produit->nom} n'est plus suffisant.");
            }
        }

        $commande = DB::transaction(function () use ($validated, $items, $panier) {
            $commande = Commande::create([
                'date_commande' => now(),
                'statut' => 'en_attente',
                'adresse_livraison' => $validated['adresse_livraison'],
                'date_livraison' => null,
                'id_utilisateur' => Auth::id(),
                'montant_total' => $this->sumCart($items),
                'statut_livraison' => 'en_attente',
            ]);

            foreach ($items as $item) {
                CommandeProduit::create([
                    'id_produit' => $item->id_produit,
                    'id_commande' => $commande->id,
                    'quantite' => $item->quantite,
                ]);

                $stockApresCommande = $item->produit->stock - $item->quantite;
                $item->produit->decrement('stock', $item->quantite);

                if ($stockApresCommande <= 0) {
                    $item->produit->update(['statut' => 'non disponible']);
                }
            }

            Paiement::create([
                'montant' => $this->sumCart($items),
                'type_paiement' => $validated['type_paiement'],
                'date_paiement' => now(),
                'num_compte' => $validated['num_compte'] ?: null,
                'reference_transaction' => $validated['reference_transaction'] ?: null,
                'id_commande' => $commande->id,
            ]);

            $panier->produitsPaniers()->delete();

            return $commande;
        });

        return redirect()->route('client.commandes.show', $commande)->with('client_success', 'Commande enregistree avec succes.');
    }

    public function commandes(): View
    {
        $commandes = Auth::user()->commandes()
            ->with('commandeProduits.produit', 'paiement')
            ->latest()
            ->paginate(8);

        return view('client.commandes.index', [
            'commandes' => $commandes,
        ]);
    }

    public function showCommande(Commande $commande): View
    {
        $this->assertOrderOwnership($commande);

        return view('client.commandes.show', [
            'commande' => $commande->load('commandeProduits.produit.categorie', 'paiement'),
        ]);
    }

    public function annulerCommande(Commande $commande): RedirectResponse
    {
        $this->assertOrderOwnership($commande);

        if (! in_array($commande->statut, ['en_attente', 'confirmee'], true)) {
            return back()->with('client_error', 'Cette commande ne peut plus etre annulee.');
        }

        DB::transaction(function () use ($commande) {
            $commande->load('commandeProduits.produit');

            foreach ($commande->commandeProduits as $ligne) {
                $ligne->produit->increment('stock', $ligne->quantite);

                if ($ligne->produit->statut === 'non disponible' && $ligne->produit->stock > 0) {
                    $ligne->produit->update(['statut' => 'disponible']);
                }
            }

            $commande->update(['statut' => 'annulee']);
        });

        return back()->with('client_success', 'Commande annulee avec succes.');
    }

    protected function resolvePanier(): Panier
    {
        return Panier::firstOrCreate([
            'id_utilisateur' => Auth::id(),
        ]);
    }

    protected function sumCart($items): float
    {
        return (float) $items->sum(fn ($item) => $item->quantite * (float) $item->produit->prix_unitaire);
    }

    protected function assertCartOwnership(ProduitsPanier $produitsPanier): void
    {
        abort_unless($produitsPanier->panier->id_utilisateur === Auth::id(), 403);
    }

    protected function assertOrderOwnership(Commande $commande): void
    {
        abort_unless($commande->id_utilisateur === Auth::id(), 403);
    }
}

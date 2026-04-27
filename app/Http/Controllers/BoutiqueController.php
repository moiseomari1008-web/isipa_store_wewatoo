<?php

namespace App\Http\Controllers;

use App\Models\CategorieProduit;
use App\Models\Produit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    public function accueil(): View
    {
        $produitsVedettes = Produit::with('categorie')
            ->where('statut', 'disponible')
            ->orderByDesc('date_ajout')
            ->take(6)
            ->get();

        $categories = CategorieProduit::withCount([
            'produits as produits_disponibles_count' => fn ($query) => $query->where('statut', 'disponible'),
        ])
            ->orderBy('nom')
            ->take(6)
            ->get();

        return view('visitor.home', [
            'produitsVedettes' => $produitsVedettes,
            'categories' => $categories,
            'stats' => [
                'produits' => Produit::where('statut', 'disponible')->count(),
                'categories' => CategorieProduit::count(),
            ],
        ]);
    }

    public function catalogue(Request $request): View
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

        return view('visitor.catalog', [
            'produits' => $produits,
            'categories' => $categories,
            'categorieActive' => $categorieId,
            'search' => $search,
        ]);
    }

    public function show(Produit $produit): View
    {
        $produit->load('categorie');

        $similaires = Produit::with('categorie')
            ->where('statut', 'disponible')
            ->where('id_categories', $produit->id_categories)
            ->whereKeyNot($produit->id)
            ->orderByDesc('date_ajout')
            ->take(4)
            ->get();

        return view('visitor.show', [
            'produit' => $produit,
            'similaires' => $similaires,
        ]);
    }
}

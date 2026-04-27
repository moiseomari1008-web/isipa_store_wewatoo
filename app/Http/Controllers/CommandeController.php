<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommandeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Commande::with('utilisateur', 'commandeProduits', 'paiement')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date_commande' => ['nullable', 'date'],
            'statut' => ['required', Rule::in(['rejeté', 'annulé', 'en attente', 'confirmé', 'livré'])],
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'date_livraison' => ['nullable', 'date'],
            'id_utilisateur' => ['required', 'integer', 'exists:utilisateurs,id'],
        ]);

        $commande = Commande::create($validated);

        return response()->json($commande->load('utilisateur'), 201);
    }

    public function show(Commande $commande): JsonResponse
    {
        return response()->json($commande->load('utilisateur', 'commandeProduits.produit', 'paiement'));
    }

    public function update(Request $request, Commande $commande): JsonResponse
    {
        $validated = $request->validate([
            'date_commande' => ['nullable', 'date'],
            'statut' => ['required', Rule::in(['rejeté', 'annulé', 'en attente', 'confirmé', 'livré'])],
            'adresse_livraison' => ['required', 'string', 'max:500'],
            'date_livraison' => ['nullable', 'date'],
            'id_utilisateur' => ['required', 'integer', 'exists:utilisateurs,id'],
        ]);

        $commande->update($validated);

        return response()->json($commande->load('utilisateur'));
    }

    public function destroy(Commande $commande): JsonResponse
    {
        $commande->delete();

        return response()->json(status: 204);
    }
}

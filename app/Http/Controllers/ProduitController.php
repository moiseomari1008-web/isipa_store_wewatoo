<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Produit::with('categorie')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', Rule::in(['disponible', 'non disponible'])],
            'date_ajout' => ['nullable', 'date'],
            'id_categories' => ['required', 'integer', 'exists:categorie_produits,id'],
        ]);

        $produit = Produit::create($validated);

        return response()->json($produit->load('categorie'), 201);
    }

    public function show(Produit $produit): JsonResponse
    {
        return response()->json($produit->load('categorie', 'commandeProduits', 'produitsPaniers'));
    }

    public function update(Request $request, Produit $produit): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', Rule::in(['disponible', 'non disponible'])],
            'date_ajout' => ['nullable', 'date'],
            'id_categories' => ['required', 'integer', 'exists:categorie_produits,id'],
        ]);

        $produit->update($validated);

        return response()->json($produit->load('categorie'));
    }

    public function destroy(Produit $produit): JsonResponse
    {
        $produit->delete();

        return response()->json(status: 204);
    }
}

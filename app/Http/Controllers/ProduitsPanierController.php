<?php

namespace App\Http\Controllers;

use App\Models\ProduitsPanier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduitsPanierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ProduitsPanier::with('panier', 'produit')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_produit' => ['required', 'integer', 'exists:produits,id'],
            'id_panier' => [
                'required',
                'integer',
                'exists:paniers,id',
                Rule::unique('produits_paniers')->where(
                    fn ($query) => $query->where('id_produit', $request->integer('id_produit'))
                ),
            ],
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $produitsPanier = ProduitsPanier::create($validated);

        return response()->json($produitsPanier->load('panier', 'produit'), 201);
    }

    public function show(ProduitsPanier $produitsPanier): JsonResponse
    {
        return response()->json($produitsPanier->load('panier', 'produit'));
    }

    public function update(Request $request, ProduitsPanier $produitsPanier): JsonResponse
    {
        $validated = $request->validate([
            'id_produit' => ['required', 'integer', 'exists:produits,id'],
            'id_panier' => [
                'required',
                'integer',
                'exists:paniers,id',
                Rule::unique('produits_paniers')
                    ->ignore($produitsPanier->id)
                    ->where(fn ($query) => $query->where('id_produit', $request->integer('id_produit'))),
            ],
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $produitsPanier->update($validated);

        return response()->json($produitsPanier->load('panier', 'produit'));
    }

    public function destroy(ProduitsPanier $produitsPanier): JsonResponse
    {
        $produitsPanier->delete();

        return response()->json(status: 204);
    }
}

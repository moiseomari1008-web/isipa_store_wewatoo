<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Panier::with('utilisateur', 'produitsPaniers.produit')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_utilisateur' => ['required', 'integer', 'exists:utilisateurs,id', 'unique:paniers,id_utilisateur'],
        ]);

        $panier = Panier::create($validated);

        return response()->json($panier->load('utilisateur'), 201);
    }

    public function show(Panier $panier): JsonResponse
    {
        return response()->json($panier->load('utilisateur', 'produitsPaniers.produit'));
    }

    public function update(Request $request, Panier $panier): JsonResponse
    {
        $validated = $request->validate([
            'id_utilisateur' => ['required', 'integer', 'exists:utilisateurs,id', 'unique:paniers,id_utilisateur,'.$panier->id],
        ]);

        $panier->update($validated);

        return response()->json($panier->load('utilisateur'));
    }

    public function destroy(Panier $panier): JsonResponse
    {
        $panier->delete();

        return response()->json(status: 204);
    }
}

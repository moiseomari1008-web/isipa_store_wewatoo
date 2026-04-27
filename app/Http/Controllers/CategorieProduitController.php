<?php

namespace App\Http\Controllers;

use App\Models\CategorieProduit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategorieProduitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(CategorieProduit::with('produits')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categorie_produits,nom'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
        ]);

        $categorieProduit = CategorieProduit::create($validated);

        return response()->json($categorieProduit, 201);
    }

    public function show(CategorieProduit $categorieProduit): JsonResponse
    {
        return response()->json($categorieProduit->load('produits'));
    }

    public function update(Request $request, CategorieProduit $categorieProduit): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categorie_produits,nom,'.$categorieProduit->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
        ]);

        $categorieProduit->update($validated);

        return response()->json($categorieProduit);
    }

    public function destroy(CategorieProduit $categorieProduit): JsonResponse
    {
        $categorieProduit->delete();

        return response()->json(status: 204);
    }
}

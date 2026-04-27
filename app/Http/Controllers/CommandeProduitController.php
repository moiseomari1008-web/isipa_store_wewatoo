<?php

namespace App\Http\Controllers;

use App\Models\CommandeProduit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommandeProduitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(CommandeProduit::with('commande', 'produit')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_produit' => ['required', 'integer', 'exists:produits,id'],
            'id_commande' => [
                'required',
                'integer',
                'exists:commandes,id',
                Rule::unique('commande_produits')->where(
                    fn ($query) => $query->where('id_produit', $request->integer('id_produit'))
                ),
            ],
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $commandeProduit = CommandeProduit::create($validated);

        return response()->json($commandeProduit->load('commande', 'produit'), 201);
    }

    public function show(CommandeProduit $commandeProduit): JsonResponse
    {
        return response()->json($commandeProduit->load('commande', 'produit'));
    }

    public function update(Request $request, CommandeProduit $commandeProduit): JsonResponse
    {
        $validated = $request->validate([
            'id_produit' => ['required', 'integer', 'exists:produits,id'],
            'id_commande' => [
                'required',
                'integer',
                'exists:commandes,id',
                Rule::unique('commande_produits')
                    ->ignore($commandeProduit->id)
                    ->where(fn ($query) => $query->where('id_produit', $request->integer('id_produit'))),
            ],
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        $commandeProduit->update($validated);

        return response()->json($commandeProduit->load('commande', 'produit'));
    }

    public function destroy(CommandeProduit $commandeProduit): JsonResponse
    {
        $commandeProduit->delete();

        return response()->json(status: 204);
    }
}

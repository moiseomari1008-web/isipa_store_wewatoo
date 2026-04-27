<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Paiement::with('commande')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'montant' => ['required', 'numeric', 'min:0'],
            'type_paiement' => ['required', 'string', 'max:100'],
            'date_paiement' => ['nullable', 'date'],
            'num_compte' => ['nullable', 'string', 'max:100'],
            'reference_transaction' => ['nullable', 'string', 'max:255'],
            'id_commande' => ['required', 'integer', 'exists:commandes,id'],
        ]);

        $paiement = Paiement::create($validated);

        return response()->json($paiement->load('commande'), 201);
    }

    public function show(Paiement $paiement): JsonResponse
    {
        return response()->json($paiement->load('commande'));
    }

    public function update(Request $request, Paiement $paiement): JsonResponse
    {
        $validated = $request->validate([
            'montant' => ['required', 'numeric', 'min:0'],
            'type_paiement' => ['required', 'string', 'max:100'],
            'date_paiement' => ['nullable', 'date'],
            'num_compte' => ['nullable', 'string', 'max:100'],
            'reference_transaction' => ['nullable', 'string', 'max:255'],
            'id_commande' => ['required', 'integer', 'exists:commandes,id'],
        ]);

        $paiement->update($validated);

        return response()->json($paiement->load('commande'));
    }

    public function destroy(Paiement $paiement): JsonResponse
    {
        $paiement->delete();

        return response()->json(status: 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::with('role', 'commandes', 'panier')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:utilisateurs,email'],
            'mot_de_passe' => ['required', 'string', 'min:8'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'id_role' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $validated['mot_de_passe'] = Hash::make($validated['mot_de_passe']);

        $user = User::create($validated);

        return response()->json($user->load('role'), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user->load('role', 'commandes', 'panier'));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'nom_complet' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:utilisateurs,email,'.$user->id],
            'mot_de_passe' => ['nullable', 'string', 'min:8'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'id_role' => ['required', 'integer', 'exists:roles,id'],
        ]);

        if (! empty($validated['mot_de_passe'])) {
            $validated['mot_de_passe'] = Hash::make($validated['mot_de_passe']);
        } else {
            unset($validated['mot_de_passe']);
        }

        $user->update($validated);

        return response()->json($user->load('role'));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(status: 204);
    }
}

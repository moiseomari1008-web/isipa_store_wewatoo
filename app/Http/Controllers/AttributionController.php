<?php

namespace App\Http\Controllers;

use App\Models\Attribution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttributionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Attribution::with('role', 'permission')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_role' => ['required', 'integer', 'exists:roles,id'],
            'id_permission' => [
                'required',
                'integer',
                'exists:permissions,id',
                Rule::unique('attributions')->where(
                    fn ($query) => $query->where('id_role', $request->integer('id_role'))
                ),
            ],
        ]);

        $attribution = Attribution::create($validated);

        return response()->json($attribution->load('role', 'permission'), 201);
    }

    public function show(Attribution $attribution): JsonResponse
    {
        return response()->json($attribution->load('role', 'permission'));
    }

    public function update(Request $request, Attribution $attribution): JsonResponse
    {
        $validated = $request->validate([
            'id_role' => ['required', 'integer', 'exists:roles,id'],
            'id_permission' => [
                'required',
                'integer',
                'exists:permissions,id',
                Rule::unique('attributions')
                    ->ignore($attribution->id)
                    ->where(fn ($query) => $query->where('id_role', $request->integer('id_role'))),
            ],
        ]);

        $attribution->update($validated);

        return response()->json($attribution->load('role', 'permission'));
    }

    public function destroy(Attribution $attribution): JsonResponse
    {
        $attribution->delete();

        return response()->json(status: 204);
    }
}

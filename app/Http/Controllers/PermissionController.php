<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Permission::with('roles')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:permissions,nom'],
            'description' => ['nullable', 'string'],
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    public function show(Permission $permission): JsonResponse
    {
        return response()->json($permission->load('roles'));
    }

    public function update(Request $request, Permission $permission): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:permissions,nom,'.$permission->id],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json(status: 204);
    }
}

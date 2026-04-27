<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $role = $user->role;

        if (!$role) {
            abort(403, 'Pas de rôle assigné');
        }

        // Vérifier si le rôle a la permission
        $hasPermission = $role->permissions()
            ->where('permissions.nom', $permission)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Permission refusée: ' . $permission);
        }

        return $next($request);
    }
}

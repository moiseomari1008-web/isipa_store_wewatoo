<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier que l'utilisateur est authentifié et qu'il a le tableau de bord
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $role = $user->role;

        if (!$role || !in_array($role->nom, ['Super Admin', 'Admin Articles', 'Admin Utilisateurs'])) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}

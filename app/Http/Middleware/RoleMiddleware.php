<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = auth()->user();

        // Charger la relation role si elle n'est pas chargée
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        foreach ($roles as $role) {
            if ($user->role && $user->role->slug === $role) {
                return $next($request);
            }
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Accès non autorisé.');
        }

        if ($user->isArtisan()) {
            return redirect()->route('artisan.dashboard')->with('error', 'Accès non autorisé.');
        }

        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}
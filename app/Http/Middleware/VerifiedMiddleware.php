<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifiedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->isArtisan() && !$user->is_verified) {
            return redirect()->route('artisan.waiting')
                ->with('warning', 'Votre compte est en attente de vérification par l\'administrateur.');
        }

        return $next($request);
    }
}
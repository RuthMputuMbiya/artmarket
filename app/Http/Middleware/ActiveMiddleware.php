<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->statut === 'suspendu') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été suspendu.');
        }

        if ($user && $user->statut === 'inactif') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte est inactif.');
        }

        return $next($request);
    }
}
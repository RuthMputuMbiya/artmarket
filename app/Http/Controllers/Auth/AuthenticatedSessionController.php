<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function create()
    {
        // ✅ CORRECTION: utiliser auth.login au lieu de app
        return view('auth.login');
    }

    /**
     * Authentifier l'utilisateur
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirection selon le rôle
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isArtisan()) {
            return redirect()->route('artisan.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    }

    /**
     * Déconnexion
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
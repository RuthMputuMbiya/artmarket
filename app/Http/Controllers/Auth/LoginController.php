<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Authentifier l'utilisateur
     */
    public function login(Request $request)
    {
        // ===== VALIDATION =====
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Veuillez entrer votre email.',
            'email.email' => 'Veuillez entrer un email valide.',
            'password.required' => 'Veuillez entrer votre mot de passe.',
        ]);

        // ===== TENTATIVE DE CONNEXION =====
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Vérifier si le compte est actif
            if ($user->statut === 'suspendu') {
                Auth::logout();
                return back()->with('error', 'Votre compte a été suspendu.');
            }

            if ($user->statut === 'inactif') {
                Auth::logout();
                return back()->with('error', 'Votre compte est inactif.');
            }

            // Mettre à jour la dernière connexion
            $user->last_login_at = now();
            $user->save();

            // Régénérer la session
            $request->session()->regenerate();

            // ===== REDIRECTION SELON LE RÔLE =====
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenue Admin !');
            } elseif ($user->isArtisan()) {
                if (!$user->is_verified) {
                    return redirect()->route('artisan.waiting')->with('warning', 'Compte en attente de vérification.');
                }
                return redirect()->route('artisan.dashboard')->with('success', 'Bienvenue dans votre atelier !');
            } else {
                return redirect()->route('client.dashboard')->with('success', 'Bienvenue sur ArtMarket !');
            }
        }

        // ===== ÉCHEC DE CONNEXION =====
        return back()
            ->withErrors(['email' => 'Email ou mot de passe incorrect.'])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous êtes déconnecté.');
    }

    /**
     * Page d'attente pour les artisans
     */
    public function waiting()
    {
        return view('auth.waiting');
    }
}
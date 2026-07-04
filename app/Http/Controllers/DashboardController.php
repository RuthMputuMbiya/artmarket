<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Rediriger vers le tableau de bord approprié selon le rôle
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // Vérifier le rôle et rediriger
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Bienvenue Admin !');
        }

        if ($user->isArtisan()) {
            if (!$user->is_verified) {
                return redirect()->route('artisan.waiting')->with('warning', 'Votre compte est en attente de vérification par l\'administrateur.');
            }
            return redirect()->route('artisan.dashboard')->with('success', 'Bienvenue dans votre atelier !');
        }

        // Client par défaut
        return redirect()->route('client.dashboard')->with('success', 'Bienvenue sur ArtMarket !');
    }

    /**
     * Vérifier le statut de l'utilisateur (AJAX)
     */
    public function status()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'authenticated' => false,
                'redirect' => route('login')
            ]);
        }

        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->slug ?? 'client',
                'is_verified' => $user->is_verified,
                'statut' => $user->statut,
            ],
            'dashboard_url' => $this->getDashboardUrl($user),
        ]);
    }

    /**
     * Obtenir l'URL du dashboard selon le rôle
     */
    private function getDashboardUrl($user)
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($user->isArtisan()) {
            if (!$user->is_verified) {
                return route('artisan.waiting');
            }
            return route('artisan.dashboard');
        }

        return route('client.dashboard');
    }
}
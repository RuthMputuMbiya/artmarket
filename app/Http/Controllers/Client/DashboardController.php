<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Favoris;
use App\Models\Notification;
use App\Models\Avis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ============================================================
        // STATISTIQUES
        // ============================================================
        $stats = [
            'total_commandes' => Commande::where('user_id', $user->id)->count(),
            'commandes_en_cours' => Commande::where('user_id', $user->id)
                ->whereIn('statut', ['en_attente', 'en_cours', 'expediee'])
                ->count(),
            'commandes_livrees' => Commande::where('user_id', $user->id)
                ->where('statut', 'livree')
                ->count(),
            'total_depenses' => Commande::where('user_id', $user->id)
                ->where('statut', 'livree')
                ->sum('total_ttc') ?? 0,
            'favoris_count' => Favoris::where('user_id', $user->id)->count(),
            'avis_count' => Avis::where('user_id', $user->id)->count(),
            'notifications_non_lues' => Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count(),
        ];

        // ============================================================
        // DERNIERES COMMANDES
        // ============================================================
        $dernieres_commandes = Commande::where('user_id', $user->id)
            ->with(['devise', 'lignes.oeuvre'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // COMMANDES EN ATTENTE
        // ============================================================
        $commandes_attente = Commande::where('user_id', $user->id)
            ->where('statut', 'en_attente')
            ->with(['devise', 'lignes.oeuvre'])
            ->orderBy('created_at', 'asc')
            ->get();

        // ============================================================
        // DERNIERES NOTIFICATIONS
        // ============================================================
        $dernieres_notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // DEPENSES PAR MOIS
        // ============================================================
        $depenses_par_mois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $depenses_par_mois[] = [
                'mois' => $date->format('M'),
                'total' => Commande::where('user_id', $user->id)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('statut', 'livree')
                    ->sum('total_ttc') ?? 0,
            ];
        }

        return view('client.dashboard', compact(
            'stats',
            'dernieres_commandes',
            'commandes_attente',
            'dernieres_notifications',
            'depenses_par_mois'
        ));
    }
}
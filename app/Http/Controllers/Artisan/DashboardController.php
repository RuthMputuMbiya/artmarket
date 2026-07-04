<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Oeuvre;
use App\Models\Avis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ============================================================
        // STATISTIQUES
        // ============================================================
        $stats = [
            'total_oeuvres' => Oeuvre::where('user_id', $user->id)->count(),
            'oeuvres_publiees' => Oeuvre::where('user_id', $user->id)
                ->where('statut', 'publiee')->count(),
            'oeuvres_brouillon' => Oeuvre::where('user_id', $user->id)
                ->where('statut', 'brouillon')->count(),
            'total_commandes' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'commandes_en_attente' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_attente')->count(),
            'commandes_livrees' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->count(),
            'total_ventes' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->sum('total_ttc') ?? 0,
            'avis_total' => Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'avis_moyenne' => number_format(Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->avg('note') ?? 0, 1),
        ];

        // ============================================================
        // VENTES 7 JOURS
        // ============================================================
        $ventes_7_jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $ventes_7_jours[] = [
                'date' => $date->format('d/m'),
                'total' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereDate('created_at', $date->toDateString())
                    ->where('statut', 'livree')
                    ->sum('total_ttc') ?? 0,
            ];
        }

        // ============================================================
        // TOP 5 ŒUVRES
        // ============================================================
        $top_oeuvres = Oeuvre::where('user_id', $user->id)
            ->withCount(['commandes as total_ventes' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('statut', 'livree');
                });
            }])
            ->orderBy('total_ventes', 'desc')
            ->limit(5)
            ->get();

        // ============================================================
        // DERNIÈRES COMMANDES
        // ============================================================
        $dernieres_commandes = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'devise'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // DERNIERS AVIS
        // ============================================================
        $derniers_avis = Avis::whereHas('oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'oeuvre'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('artisan.dashboard', compact(
            'stats',
            'ventes_7_jours',
            'top_oeuvres',
            'dernieres_commandes',
            'derniers_avis'
        ));
    }
}
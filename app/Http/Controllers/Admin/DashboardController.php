<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Oeuvre;
use App\Models\User;
use App\Models\Paiement;
use App\Models\Avis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================================
        // STATISTIQUES GLOBALES
        // ============================================================
        $stats = [
            'total_users' => User::count(),
            'total_artisans' => User::whereHas('role', function($q) {
                $q->where('slug', 'artisan');
            })->count(),
            'total_clients' => User::whereHas('role', function($q) {
                $q->where('slug', 'client');
            })->count(),
            'total_oeuvres' => Oeuvre::count(),
            'total_commandes' => Commande::count(),
            'total_ventes' => Commande::where('statut', 'livree')->sum('total_ttc') ?? 0,
            'total_paiements' => Paiement::where('statut', 'paye')->sum('montant') ?? 0,
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'oeuvres_publiees' => Oeuvre::where('statut', 'publiee')->count(),
            'avis_total' => Avis::count(),
            'avis_moyenne' => number_format(Avis::avg('note') ?? 0, 1),
        ];

        // ============================================================
        // VENTES 7 JOURS
        // ============================================================
        $ventes_7_jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $ventes_7_jours[] = [
                'date' => $date->format('d/m'),
                'total' => Commande::whereDate('created_at', $date->toDateString())
                    ->where('statut', 'livree')
                    ->sum('total_ttc') ?? 0,
            ];
        }

        // ============================================================
        // VENTES PAR MOIS
        // ============================================================
        $ventes_par_mois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ventes_par_mois[] = [
                'mois' => $date->format('M'),
                'total' => Commande::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('statut', 'livree')
                    ->sum('total_ttc') ?? 0,
            ];
        }

        // ============================================================
        // VENTES PAR CATÉGORIE
        // ============================================================
        $ventes_par_categorie = \DB::table('commandes')
            ->join('ligne_commandes', 'commandes.id', '=', 'ligne_commandes.commande_id')
            ->join('oeuvres', 'ligne_commandes.oeuvre_id', '=', 'oeuvres.id')
            ->join('categories', 'oeuvres.categorie_id', '=', 'categories.id')
            ->where('commandes.statut', 'livree')
            ->select('categories.nom', \DB::raw('SUM(ligne_commandes.total) as total'))
            ->groupBy('categories.nom')
            ->limit(5)
            ->get();

        // ============================================================
        // STATUT COMMANDES
        // ============================================================
        $statut_commandes = [
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'en_cours' => Commande::where('statut', 'en_cours')->count(),
            'expediee' => Commande::where('statut', 'expediee')->count(),
            'livree' => Commande::where('statut', 'livree')->count(),
            'annulee' => Commande::where('statut', 'annulee')->count(),
        ];

        // ============================================================
        // DERNIÈRES COMMANDES
        // ============================================================
        $dernieres_commandes = Commande::with(['user', 'devise'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // DERNIERS UTILISATEURS
        // ============================================================
        $derniers_utilisateurs = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // DERNIÈRES ŒUVRES
        // ============================================================
        $dernieres_oeuvres = Oeuvre::with(['artisan', 'categorie'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'ventes_7_jours',
            'ventes_par_mois',
            'ventes_par_categorie',
            'dernieres_commandes',
            'derniers_utilisateurs',
            'dernieres_oeuvres',
            'statut_commandes'
        ));
    }
}
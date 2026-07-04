<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Oeuvre;
use App\Models\Avis;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // ============================================
        // STATISTIQUES GLOBALES
        // ============================================
        $stats = [
            // Œuvres
            'total_oeuvres' => Oeuvre::where('user_id', $user->id)->count(),
            'oeuvres_publiees' => Oeuvre::where('user_id', $user->id)->where('statut', 'publiee')->count(),
            'oeuvres_brouillon' => Oeuvre::where('user_id', $user->id)->where('statut', 'brouillon')->count(),
            'oeuvres_en_attente' => Oeuvre::where('user_id', $user->id)->where('statut', 'en_attente')->count(),
            'oeuvres_archivees' => Oeuvre::where('user_id', $user->id)->where('statut', 'archivee')->count(),

            // Commandes
            'total_commandes' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'commandes_en_attente' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_attente')->count(),
            'commandes_en_cours' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_cours')->count(),
            'commandes_expediees' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'expediee')->count(),
            'commandes_livrees' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->count(),
            'commandes_annulees' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'annulee')->count(),

            // Ventes
            'total_ventes' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->sum('total_ttc') ?? 0,
            'total_ventes_ht' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->sum('total_ht') ?? 0,
            'commission_totale' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->sum('commission') ?? 0,

            // Avis
            'total_avis' => Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'avis_moyenne' => number_format(Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->avg('note') ?? 0, 1),
            'avis_approuves' => Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('is_approved', true)->count(),
            'avis_en_attente' => Avis::whereHas('oeuvre', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('is_approved', false)->count(),

            // Stock
            'stock_total' => Oeuvre::where('user_id', $user->id)->sum('stock'),
            'stock_alerte' => Oeuvre::where('user_id', $user->id)
                ->whereColumn('stock', '<=', 'stock_min')
                ->where('stock', '>', 0)
                ->count(),
            'stock_rupture' => Oeuvre::where('user_id', $user->id)
                ->where('stock', 0)
                ->count(),
        ];

        // ============================================
        // VENTES PAR MOIS (12 derniers mois)
        // ============================================
        $ventes_par_mois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ventes_par_mois[] = [
                'mois' => $date->format('M'),
                'total' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('statut', 'livree')
                    ->sum('total_ttc') ?? 0,
            ];
        }

        // ============================================
        // VENTES PAR CATÉGORIE
        // ============================================
        $ventes_par_categorie = Oeuvre::where('user_id', $user->id)
            ->withCount(['commandes as total_ventes' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('statut', 'livree');
                });
            }])
            ->withSum(['commandes as total_montant' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('statut', 'livree');
                });
            }], 'total')
            ->with('categorie')
            ->orderBy('total_ventes', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // TOP 5 CLIENTS
        // ============================================
        $top_clients = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'livree')
            ->select('user_id', \DB::raw('COUNT(*) as total_commandes'), \DB::raw('SUM(total_ttc) as total_depenses'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total_depenses', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // TOP 5 ŒUVRES LES PLUS VENDUES
        // ============================================
        $top_oeuvres = Oeuvre::where('user_id', $user->id)
            ->withCount(['commandes as total_ventes' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('statut', 'livree');
                });
            }])
            ->orderBy('total_ventes', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // ÉVOLUTION DES COMMANDES (7 jours)
        // ============================================
        $commandes_7_jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $commandes_7_jours[] = [
                'date' => $date->format('d/m'),
                'total' => Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        return view('artisan.statistiques.index', compact(
            'stats',
            'ventes_par_mois',
            'ventes_par_categorie',
            'top_clients',
            'top_oeuvres',
            'commandes_7_jours'
        ));
    }

    /**
     * Exporter les statistiques en PDF (ou CSV)
     */
    public function export()
    {
        // Logique d'export
    }
}
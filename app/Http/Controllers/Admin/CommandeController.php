<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Liste des commandes
     */
    public function index(Request $request)
    {
        $query = Commande::with(['user', 'devise', 'lignes.oeuvre']);

        // ===== FILTRES =====
        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Filtre par client
        if ($request->has('client') && $request->client) {
            $query->where('user_id', $request->client);
        }

        // Recherche par référence
        if ($request->has('search') && $request->search) {
            $query->where('reference', 'LIKE', "%{$request->search}%");
        }

        // Filtre par date
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Filtre par montant
        if ($request->has('montant_min') && $request->montant_min) {
            $query->where('total_ttc', '>=', $request->montant_min);
        }
        if ($request->has('montant_max') && $request->montant_max) {
            $query->where('total_ttc', '<=', $request->montant_max);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(20);
        $clients = User::whereHas('role', function($q) {
            $q->where('slug', 'client');
        })->get();
        $statuts = ['en_attente', 'en_cours', 'expediee', 'livree', 'annulee', 'rembourse'];

        // Statistiques
        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'en_cours' => Commande::where('statut', 'en_cours')->count(),
            'livree' => Commande::where('statut', 'livree')->count(),
            'annulee' => Commande::where('statut', 'annulee')->count(),
            'total_ventes' => Commande::where('statut', 'livree')->sum('total_ttc') ?? 0,
        ];

        return view('admin.commandes.index', compact('commandes', 'clients', 'statuts', 'stats'));
    }

    /**
     * Afficher une commande
     */
    public function show($id)
    {
        $commande = Commande::with([
            'user',
            'devise',
            'lignes.oeuvre.images',
            'lignes.oeuvre.artisan',
            'paiements'
        ])->findOrFail($id);

        return view('admin.commandes.show', compact('commande'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,expediee,livree,annulee,rembourse'
        ]);

        $commande->statut = $request->statut;

        if ($request->statut === 'livree') {
            $commande->date_livraison = now();
        }

        $commande->save();

        return redirect()->route('admin.commandes.show', $commande->id)
            ->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Supprimer une commande
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);

        if ($commande->statut === 'livree') {
            return back()->with('error', 'Impossible de supprimer une commande livrée.');
        }

        $commande->delete();

        return redirect()->route('admin.commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Exporter les commandes en CSV
     */
    public function export(Request $request)
    {
        $query = Commande::with(['user', 'devise']);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $commandes = $query->get();

        $filename = 'commandes_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($commandes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Référence', 'Client', 'Total', 'Statut', 'Date', 'Livraison']);

            foreach ($commandes as $commande) {
                fputcsv($file, [
                    $commande->reference,
                    $commande->user->name ?? 'N/A',
                    number_format($commande->total_ttc, 2) . ' ' . ($commande->devise->symbole ?? 'FC'),
                    $commande->statut,
                    $commande->created_at->format('d/m/Y H:i'),
                    $commande->date_livraison ? $commande->date_livraison->format('d/m/Y') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistiques des commandes
     */
    public function stats()
    {
        $stats = [
            'par_mois' => Commande::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total, SUM(total_ttc) as montant')
                ->where('statut', 'livree')
                ->groupBy('mois', 'annee')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->limit(12)
                ->get(),
            'par_statut' => Commande::selectRaw('statut, COUNT(*) as total, SUM(total_ttc) as montant')
                ->groupBy('statut')
                ->get(),
        ];

        return view('admin.commandes.stats', compact('stats'));
    }
}
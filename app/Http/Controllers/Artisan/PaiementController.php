<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Commande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    /**
     * Liste des paiements reçus par l'artisan
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Récupérer les commandes qui contiennent des oeuvres de cet artisan
        $commandeIds = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('id');

        $query = Paiement::whereIn('commande_id', $commandeIds)
            ->with(['commande', 'commande.user', 'commande.devise']);

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        // Filtre par méthode
        if ($request->has('methode') && $request->methode) {
            $query->where('methode', $request->methode);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhereHas('commande', function($q2) use ($search) {
                      $q2->where('reference', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filtre par date
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $paiements = $query->orderBy('created_at', 'desc')->paginate(15);

        // ===== STATISTIQUES =====
        $stats = [
            'total_paiements' => Paiement::whereIn('commande_id', $commandeIds)->count(),
            'total_payes' => Paiement::whereIn('commande_id', $commandeIds)->where('statut', 'paye')->count(),
            'total_attente' => Paiement::whereIn('commande_id', $commandeIds)->where('statut', 'en_attente')->count(),
            'total_echoue' => Paiement::whereIn('commande_id', $commandeIds)->where('statut', 'echoue')->count(),
            'total_paye' => Paiement::whereIn('commande_id', $commandeIds)->where('statut', 'paye')->sum('montant') ?? 0,
        ];

        $statuts = ['en_attente', 'paye', 'echoue', 'rembourse'];
        $methodes = ['orange_money', 'airtel_money', 'mpesa', 'carte', 'shwary'];

        return view('artisan.paiements.index', compact('paiements', 'stats', 'statuts', 'methodes'));
    }

    /**
     * Afficher un paiement
     */
    public function show($id)
    {
        $user = Auth::user();

        $commandeIds = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('id');

        $paiement = Paiement::whereIn('commande_id', $commandeIds)
            ->with(['commande', 'commande.user', 'commande.devise', 'commande.lignes.oeuvre'])
            ->findOrFail($id);

        return view('artisan.paiements.show', compact('paiement'));
    }

    /**
     * Télécharger le reçu d'un paiement
     */
    public function recu($id)
    {
        $user = Auth::user();

        $commandeIds = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('id');

        $paiement = Paiement::whereIn('commande_id', $commandeIds)
            ->with(['commande', 'commande.user', 'commande.devise', 'commande.lignes.oeuvre'])
            ->findOrFail($id);

        if ($paiement->statut !== 'paye') {
            return back()->with('error', 'Ce paiement n\'a pas ete confirme.');
        }

        $commande = $paiement->commande;

        $pdf = Pdf::loadView('artisan.paiements.recu', compact('paiement', 'commande'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('recu-paiement-' . $paiement->reference . '.pdf');
    }

    /**
     * Exporter les paiements en CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        $commandeIds = Commande::whereHas('lignes.oeuvre', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('id');

        $query = Paiement::whereIn('commande_id', $commandeIds)
            ->with(['commande', 'commande.user', 'commande.devise']);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $paiements = $query->get();

        $filename = 'paiements_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($paiements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'Commande', 'Client', 'Montant', 'Methode', 'Statut', 'Date']);

            foreach ($paiements as $paiement) {
                fputcsv($file, [
                    $paiement->reference,
                    $paiement->commande->reference ?? 'N/A',
                    $paiement->commande->user->name ?? 'N/A',
                    number_format($paiement->montant, 2) . ' ' . ($paiement->commande->devise->symbole ?? 'FC'),
                    $this->getMethodeLabel($paiement->methode),
                    $paiement->statut,
                    $paiement->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtenir le libellé de la méthode de paiement
     */
    private function getMethodeLabel($methode)
    {
        $labels = [
            'orange_money' => 'Orange Money',
            'airtel_money' => 'Airtel Money',
            'mpesa' => 'M-Pesa',
            'carte' => 'Carte bancaire',
            'shwary' => 'Shwary',
        ];
        return $labels[$methode] ?? $methode;
    }
}
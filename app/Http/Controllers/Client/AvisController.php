<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\User;
use App\Models\Oeuvre;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Liste des avis
     */
    public function index(Request $request)
    {
        $query = Avis::with(['user', 'oeuvre', 'oeuvre.artisan']);

        // ===== FILTRES =====
        // Filtre par statut d'approbation
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filtre par note
        if ($request->has('note') && $request->note) {
            $query->where('note', $request->note);
        }

        // Filtre par client
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('commentaire', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('oeuvre', function($q3) use ($search) {
                      $q3->where('titre', 'LIKE', "%{$search}%");
                  });
            });
        }

        $avis = $query->orderBy('created_at', 'desc')->paginate(20);
        $clients = User::whereHas('role', function($q) {
            $q->where('slug', 'client');
        })->get();

        // Statistiques
        $stats = [
            'total' => Avis::count(),
            'approved' => Avis::where('is_approved', true)->count(),
            'pending' => Avis::where('is_approved', false)->count(),
            'moyenne' => number_format(Avis::avg('note') ?? 0, 1),
            'par_note' => [
                1 => Avis::where('note', 1)->count(),
                2 => Avis::where('note', 2)->count(),
                3 => Avis::where('note', 3)->count(),
                4 => Avis::where('note', 4)->count(),
                5 => Avis::where('note', 5)->count(),
            ],
        ];

        return view('admin.avis.index', compact('avis', 'clients', 'stats'));
    }

    /**
     * Approuver un avis
     */
    public function approve($id)
    {
        $avis = Avis::findOrFail($id);
        $avis->is_approved = true;
        $avis->save();

        return back()->with('success', 'Avis approuve avec succes.');
    }

    /**
     * Rejeter un avis
     */
    public function reject($id)
    {
        $avis = Avis::findOrFail($id);
        $avis->is_approved = false;
        $avis->save();

        return back()->with('success', 'Avis rejete.');
    }

    /**
     * Supprimer un avis
     */
    public function destroy($id)
    {
        $avis = Avis::findOrFail($id);
        $avis->delete();

        return redirect()->route('admin.avis.index')
            ->with('success', 'Avis supprime avec succes.');
    }

    /**
     * Exporter les avis en CSV
     */
    public function export(Request $request)
    {
        $query = Avis::with(['user', 'oeuvre']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_approved', $request->status === 'approved');
        }

        if ($request->has('note') && $request->note) {
            $query->where('note', $request->note);
        }

        $avis = $query->get();

        $filename = 'avis_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($avis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Client', 'Oeuvre', 'Note', 'Commentaire', 'Approuve', 'Date']);

            foreach ($avis as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->user->name ?? 'N/A',
                    $item->oeuvre->titre ?? 'N/A',
                    $item->note . ' / 5',
                    $item->commentaire ?? '-',
                    $item->is_approved ? 'Oui' : 'Non',
                    $item->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
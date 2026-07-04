<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    /**
     * Liste des commandes recues par l'artisan
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $query = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'devise', 'lignes.oeuvre']);

        // ===== FILTRES =====
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $query->where('reference', 'LIKE', "%{$request->search}%");
        }

        if ($request->filled('client')) {
            $query->where('user_id', $request->client);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $commandes = $query->orderBy('created_at', 'desc')->paginate(15);
        $statuts = ['en_attente', 'en_cours', 'expediee', 'livree', 'annulee', 'rembourse'];

        $clients = User::whereHas('role', function ($q) {
            $q->where('slug', 'client');
        })->get();

        $stats = [
            'total' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'en_attente' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_attente')->count(),
            'en_cours' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_cours')->count(),
            'livree' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->count(),
            'annulee' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'annulee')->count(),
            'total_ventes' => Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'livree')->sum('total_ttc') ?? 0,
        ];

        return view('artisan.commandes.index', compact('commandes', 'statuts', 'stats', 'clients'));
    }

    /**
     * Afficher une commande
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $commande = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with([
            'user',
            'devise',
            'lignes.oeuvre.images',
            'lignes.oeuvre.artisan',
            'paiements'
        ])->findOrFail($id);

        return view('artisan.commandes.show', compact('commande'));
    }

    /**
     * Mettre a jour le statut d'une commande
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $commande = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,expediee,livree,annulee,rembourse'
        ]);

        $commande->statut = $request->statut;

        if ($request->statut === 'livree') {
            $commande->date_livraison = now();
        }

        $commande->save();

        return redirect()->route('artisan.commandes.index')
            ->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Modifier une commande - Afficher le formulaire d'edition
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $commande = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'devise', 'lignes.oeuvre'])->findOrFail($id);

        if (in_array($commande->statut, ['livree', 'annulee'])) {
            return redirect()->route('artisan.commandes.index')
                ->with('error', 'Cette commande ne peut pas etre modifiee.');
        }

        return view('artisan.commandes.edit', compact('commande'));
    }

    /**
     * Mettre a jour une commande
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $commande = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        if (in_array($commande->statut, ['livree', 'annulee'])) {
            return redirect()->route('artisan.commandes.index')
                ->with('error', 'Cette commande ne peut pas etre modifiee.');
        }

        $request->validate([
            'adresse_livraison' => 'nullable|string',
            'ville_livraison' => 'nullable|string',
            'code_postal_livraison' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $commande->update([
            'adresse_livraison' => $request->adresse_livraison,
            'ville_livraison' => $request->ville_livraison,
            'code_postal_livraison' => $request->code_postal_livraison,
            'notes' => $request->notes,
        ]);

        return redirect()->route('artisan.commandes.index')
            ->with('success', 'Commande mise à jour avec succès.');
    }

    /**
     * Supprimer une commande
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $commande = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        if (in_array($commande->statut, ['livree', 'en_cours', 'expediee'])) {
            return redirect()->route('artisan.commandes.index')
                ->with('error', 'Cette commande ne peut pas etre supprimee.');
        }

        $commande->delete();

        return redirect()->route('artisan.commandes.index')
            ->with('success', 'Commande supprimee avec succes.');
    }

    /**
     * Recuperer les commandes en attente (AJAX)
     */
    public function getEnAttente()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Non authentifie'], 401);
        }

        $commandes = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'en_attente')
            ->with(['user', 'devise'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($commandes);
    }

    /**
     * Exporter les commandes en CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $query = Commande::whereHas('lignes.oeuvre', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'devise']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $commandes = $query->get();

        $filename = 'commandes_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($commandes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'Client', 'Total', 'Statut', 'Date']);

            foreach ($commandes as $commande) {
                fputcsv($file, [
                    $commande->reference,
                    $commande->user->name ?? 'N/A',
                    number_format($commande->total_ttc, 2) . ' ' . ($commande->devise->symbole ?? 'FC'),
                    $commande->statut,
                    $commande->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

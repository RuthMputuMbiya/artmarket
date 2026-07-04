<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Devise;
use App\Models\Oeuvre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OeuvreController extends Controller
{
    /**
     * Liste des oeuvres
     */
    public function index(Request $request)
    {
        $query = Oeuvre::with(['artisan', 'categorie', 'devise', 'images'])
            ->withCount(['avis', 'commandes']);

        // ===== FILTRES =====
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->has('artisan') && $request->artisan) {
            $query->where('user_id', $request->artisan);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('artisan', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->has('prix_min') && $request->prix_min) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max') && $request->prix_max) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->has('stock') && $request->stock === 'en_stock') {
            $query->where('stock', '>', 0);
        } elseif ($request->has('stock') && $request->stock === 'rupture') {
            $query->where('stock', '<=', 0);
        }

        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured);
        }

        $oeuvres = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Categorie::where('is_active', true)->get();
        $artisans = User::whereHas('role', function($q) {
            $q->where('slug', 'artisan');
        })->get();
        $statuts = ['brouillon', 'en_attente', 'publiee', 'archivee'];

        return view('admin.oeuvres.index', compact('oeuvres', 'categories', 'artisans', 'statuts'));
    }

    /**
     * Afficher une oeuvre
     */
    public function show($id)
    {
        $oeuvre = Oeuvre::with([
            'artisan',
            'categorie',
            'devise',
            'images',
            'avis.user',
            'commandes'
        ])->withCount(['avis', 'commandes'])
          ->findOrFail($id);

        $stats = [
            'total_avis' => $oeuvre->avis()->count(),
            'moyenne_notes' => number_format($oeuvre->avis()->avg('note') ?? 0, 1),
            'total_commandes' => $oeuvre->commandes()->count(),
            'total_ventes' => $oeuvre->commandes()->whereHas('commande', function($q) {
                $q->where('statut', 'livree');
            })->sum('total') ?? 0,
        ];

        return view('admin.oeuvres.show', compact('oeuvre', 'stats'));
    }

    /**
     * Formulaire d'edition d'une oeuvre
     */
    public function edit($id)
    {
        $oeuvre = Oeuvre::with(['artisan', 'categorie', 'devise', 'images'])->findOrFail($id);
        $categories = Categorie::where('is_active', true)->get();
        $artisans = User::whereHas('role', function($q) {
            $q->where('slug', 'artisan');
        })->get();
        $devises = Devise::all();
        $statuts = ['brouillon', 'en_attente', 'publiee', 'archivee'];

        return view('admin.oeuvres.edit', compact('oeuvre', 'categories', 'artisans', 'devises', 'statuts'));
    }

    /**
     * Mettre a jour une oeuvre
     */
    public function update(Request $request, $id)
    {
        $oeuvre = Oeuvre::findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0|lt:prix',
            'categorie_id' => 'nullable|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
            'stock' => 'required|integer|min:0',
            'statut' => 'required|in:brouillon,en_attente,publiee,archivee',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'materiaux' => 'nullable|string',
            'dimensions' => 'nullable|string',
        ]);

        $oeuvre->update([
            'titre' => $request->titre,
            'slug' => Str::slug($request->titre),
            'description' => $request->description,
            'prix' => $request->prix,
            'prix_promo' => $request->prix_promo,
            'categorie_id' => $request->categorie_id,
            'devise_id' => $request->devise_id,
            'stock' => $request->stock,
            'statut' => $request->statut,
            'is_featured' => $request->has('is_featured'),
            'is_available' => $request->has('is_available'),
            'materiaux' => $request->materiaux,
            'dimensions' => $request->dimensions,
        ]);

        return redirect()->route('admin.oeuvres.index')
            ->with('success', 'Oeuvre mise a jour avec succes.');
    }

    /**
     * Changer le statut d'une oeuvre
     */
    public function changeStatus(Request $request, $id)
    {
        $oeuvre = Oeuvre::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:brouillon,en_attente,publiee,archivee'
        ]);

        $oeuvre->statut = $request->statut;

        if ($request->statut === 'publiee' && !$oeuvre->published_at) {
            $oeuvre->published_at = now();
        }

        $oeuvre->save();

        return redirect()->route('admin.oeuvres.index')
            ->with('success', 'Statut de l\'oeuvre mis a jour.');
    }

    /**
     * Mettre en avant / retirer une oeuvre
     */
    public function toggleFeatured($id)
    {
        $oeuvre = Oeuvre::findOrFail($id);
        $oeuvre->is_featured = !$oeuvre->is_featured;
        $oeuvre->save();

        $message = $oeuvre->is_featured ? 'Oeuvre mise en avant.' : 'Oeuvre retiree des avant.';
        return back()->with('success', $message);
    }

    /**
     * Supprimer une oeuvre
     */
    public function destroy($id)
    {
        $oeuvre = Oeuvre::findOrFail($id);

        if ($oeuvre->commandes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une oeuvre qui a des commandes.');
        }

        foreach ($oeuvre->images as $image) {
            $path = str_replace('/storage/', '', $image->url);
            Storage::disk('public')->delete($path);
            $image->delete();
        }

        $oeuvre->delete();

        return redirect()->route('admin.oeuvres.index')
            ->with('success', 'Oeuvre supprimee avec succes.');
    }

    /**
     * Exporter les oeuvres en CSV
     */
    public function export(Request $request)
    {
        $query = Oeuvre::with(['artisan', 'categorie', 'devise']);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie_id', $request->categorie);
        }

        $oeuvres = $query->get();

        $filename = 'oeuvres_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($oeuvres) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Titre', 'Artisan', 'Categorie', 'Prix', 'Stock', 'Statut', 'Featured', 'Date de creation']);

            foreach ($oeuvres as $oeuvre) {
                fputcsv($file, [
                    $oeuvre->id,
                    $oeuvre->titre,
                    $oeuvre->artisan->name ?? 'N/A',
                    $oeuvre->categorie->nom ?? 'N/A',
                    number_format($oeuvre->prix_actuel, 2) . ' ' . ($oeuvre->devise->symbole ?? 'FC'),
                    $oeuvre->stock,
                    $oeuvre->statut,
                    $oeuvre->is_featured ? 'Oui' : 'Non',
                    $oeuvre->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Devise;
use App\Models\Oeuvre;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OeuvreController extends Controller
{
    /**
     * Liste des oeuvres de l'artisan
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $query = Oeuvre::where('user_id', $user->id)
            ->with(['categorie', 'devise', 'images'])
            ->withCount(['commandes as ventes' => function($q) {
                $q->whereHas('commande', function($q2) {
                    $q2->where('statut', 'livree');
                });
            }]);

        // Filtres
        if ($request->has('search') && $request->search) {
            $query->where('titre', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->has('stock') && $request->stock) {
            if ($request->stock === 'disponible') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'rupture') {
                $query->where('stock', 0);
            }
        }

        $oeuvres = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Categorie::where('is_active', true)->get();
        $statuts = ['brouillon', 'en_attente', 'publiee', 'archivee'];

        // Statistiques
        $stats = [
            'total' => Oeuvre::where('user_id', $user->id)->count(),
            'publiees' => Oeuvre::where('user_id', $user->id)->where('statut', 'publiee')->count(),
            'brouillons' => Oeuvre::where('user_id', $user->id)->where('statut', 'brouillon')->count(),
            'en_attente' => Oeuvre::where('user_id', $user->id)->where('statut', 'en_attente')->count(),
            'archivees' => Oeuvre::where('user_id', $user->id)->where('statut', 'archivee')->count(),
            'stock_total' => Oeuvre::where('user_id', $user->id)->sum('stock') ?? 0,
            'ventes' => Oeuvre::where('user_id', $user->id)
                ->withCount(['commandes as ventes' => function($q) {
                    $q->whereHas('commande', function($q2) {
                        $q2->where('statut', 'livree');
                    });
                }])
                ->get()
                ->sum('ventes'),
        ];

        return view('artisan.oeuvres.index', compact('oeuvres', 'categories', 'statuts', 'stats'));
    }

    /**
     * Formulaire de creation
     */
    public function create()
    {
        $categories = Categorie::where('is_active', true)->get();
        $devises = Devise::where('is_active', true)->get();
        $statuts = ['brouillon', 'en_attente', 'publiee'];

        return view('artisan.oeuvres.create', compact('categories', 'devises', 'statuts'));
    }

    /**
     * Enregistrer une nouvelle oeuvre
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0|lt:prix',
            'categorie_id' => 'nullable|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
            'statut' => 'required|in:brouillon,en_attente,publiee',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'nullable|integer|min:0',
            'quantite_par_commande' => 'nullable|integer|min:1',
            'materiaux' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $validated['user_id'] = $user->id;
        $validated['slug'] = Str::slug($validated['titre'] . '-' . time());
        $validated['is_available'] = $validated['stock'] > 0;
        $validated['is_featured'] = false;
        $validated['stock_min'] = $validated['stock_min'] ?? 1;
        $validated['quantite_par_commande'] = $validated['quantite_par_commande'] ?? 5;

        if ($validated['statut'] === 'publiee') {
            $validated['published_at'] = now();
        }

        $oeuvre = Oeuvre::create($validated);

        // ===== GESTION DES IMAGES =====
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('oeuvres/' . $oeuvre->id, 'public');
                Image::create([
                    'oeuvre_id' => $oeuvre->id,
                    'url' => $path,
                    'is_principal' => $index === 0,
                    'ordre' => $index,
                ]);
            }
        }

        return redirect()->route('artisan.oeuvres.index')
            ->with('success', 'Oeuvre creee avec succes.');
    }

    /**
     * Afficher une oeuvre
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)
            ->with(['categorie', 'devise', 'images', 'avis.user', 'commandes.commande'])
            ->findOrFail($id);

        $stats = [
            'total_avis' => $oeuvre->avis()->count(),
            'moyenne_notes' => number_format($oeuvre->avis()->avg('note') ?? 0, 1),
            'total_commandes' => $oeuvre->commandes()->count(),
            'total_ventes' => $oeuvre->commandes()->whereHas('commande', function($q) {
                $q->where('statut', 'livree');
            })->sum('total') ?? 0,
            'commandes_en_attente' => $oeuvre->commandes()->whereHas('commande', function($q) {
                $q->where('statut', 'en_attente');
            })->count(),
        ];

        return view('artisan.oeuvres.show', compact('oeuvre', 'stats'));
    }

    /**
     * Formulaire d'edition
     */
    public function edit($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)
            ->with(['images'])
            ->findOrFail($id);

        $categories = Categorie::where('is_active', true)->get();
        $devises = Devise::where('is_active', true)->get();
        $statuts = ['brouillon', 'en_attente', 'publiee', 'archivee'];

        return view('artisan.oeuvres.edit', compact('oeuvre', 'categories', 'devises', 'statuts'));
    }

    /**
     * Mettre a jour une oeuvre
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0|lt:prix',
            'categorie_id' => 'nullable|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
            'statut' => 'required|in:brouillon,en_attente,publiee,archivee',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'nullable|integer|min:0',
            'quantite_par_commande' => 'nullable|integer|min:1',
            'materiaux' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $validated['is_available'] = $validated['stock'] > 0;
        $validated['stock_min'] = $validated['stock_min'] ?? 1;
        $validated['quantite_par_commande'] = $validated['quantite_par_commande'] ?? 5;

        if ($validated['statut'] === 'publiee' && !$oeuvre->published_at) {
            $validated['published_at'] = now();
        }

        $oeuvre->update($validated);

        // ===== GESTION DES NOUVELLES IMAGES =====
        if ($request->hasFile('images')) {
            $currentCount = $oeuvre->images->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('oeuvres/' . $oeuvre->id, 'public');
                Image::create([
                    'oeuvre_id' => $oeuvre->id,
                    'url' => $path,
                    'is_principal' => $currentCount === 0 && $index === 0,
                    'ordre' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('artisan.oeuvres.index')
            ->with('success', 'Oeuvre mise a jour avec succes.');
    }

    /**
     * Supprimer une oeuvre
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);

        if ($oeuvre->commandes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une oeuvre qui a des commandes.');
        }

        foreach ($oeuvre->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }

        $oeuvre->delete();

        return redirect()->route('artisan.oeuvres.index')
            ->with('success', 'Oeuvre supprimee avec succes.');
    }

    /**
     * Supprimer une image specifique
     */
    public function deleteImage($id, $imageId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);
        $image = $oeuvre->images()->findOrFail($imageId);

        Storage::disk('public')->delete($image->url);
        $image->delete();

        if ($image->is_principal) {
            $firstImage = $oeuvre->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_principal' => true]);
            }
        }

        return back()->with('success', 'Image supprimee avec succes.');
    }

    /**
     * Definir une image comme principale
     */
    public function setPrincipalImage($id, $imageId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);

        $oeuvre->images()->update(['is_principal' => false]);
        $oeuvre->images()->where('id', $imageId)->update(['is_principal' => true]);

        return back()->with('success', 'Image principale definie.');
    }

    /**
     * Changer le statut d'une oeuvre
     */
    public function changeStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'statut' => 'required|in:brouillon,en_attente,publiee,archivee'
        ]);

        $oeuvre->statut = $request->statut;

        if ($request->statut === 'publiee' && !$oeuvre->published_at) {
            $oeuvre->published_at = now();
        }

        $oeuvre->save();

        return back()->with('success', 'Statut mis a jour.');
    }

    /**
     * Mettre en avant / retirer une oeuvre
     */
    public function toggleFeatured($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);
        $oeuvre->is_featured = !$oeuvre->is_featured;
        $oeuvre->save();

        $message = $oeuvre->is_featured ? 'Oeuvre mise en avant.' : 'Oeuvre retiree des avant.';
        return back()->with('success', $message);
    }

    /**
     * Exporter les oeuvres en CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $query = Oeuvre::where('user_id', $user->id)->with(['categorie', 'devise']);

        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        $oeuvres = $query->get();

        $filename = 'oeuvres_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($oeuvres) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Titre', 'Categorie', 'Prix', 'Stock', 'Statut', 'Date de creation']);

            foreach ($oeuvres as $oeuvre) {
                fputcsv($file, [
                    $oeuvre->titre,
                    $oeuvre->categorie->nom ?? 'Non classe',
                    number_format($oeuvre->prix_actuel, 2) . ' ' . ($oeuvre->devise->symbole ?? 'FC'),
                    $oeuvre->stock,
                    $oeuvre->statut,
                    $oeuvre->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dupliquer une oeuvre (brouillon)
     */
    public function duplicate($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $oeuvre = Oeuvre::where('user_id', $user->id)->findOrFail($id);

        $newOeuvre = $oeuvre->replicate();
        $newOeuvre->titre = $oeuvre->titre . ' (copie)';
        $newOeuvre->slug = Str::slug($newOeuvre->titre . '-' . time());
        $newOeuvre->statut = 'brouillon';
        $newOeuvre->published_at = null;
        $newOeuvre->save();

        foreach ($oeuvre->images as $image) {
            $oldPath = $image->url;
            $newPath = 'oeuvres/' . $newOeuvre->id . '/' . basename($oldPath);

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newPath);
                Image::create([
                    'oeuvre_id' => $newOeuvre->id,
                    'url' => $newPath,
                    'is_principal' => $image->is_principal,
                    'ordre' => $image->ordre,
                ]);
            }
        }

        return redirect()->route('artisan.oeuvres.edit', $newOeuvre->id)
            ->with('success', 'Oeuvre dupliquee avec succes. Modifiez-la et publiez-la.');
    }
}
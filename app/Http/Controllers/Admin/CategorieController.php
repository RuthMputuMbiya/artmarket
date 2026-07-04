<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    /**
     * Liste des catégories
     */
    public function index(Request $request)
    {
        $query = Categorie::with(['parent'])->withCount('oeuvres');

        // ===== RECHERCHE =====
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // ===== FILTRE ACTIF =====
        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', $request->active);
        }

        // ===== FILTRE TYPE =====
        if ($request->filled('type')) {
            if ($request->type === 'parent') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'child') {
                $query->whereNotNull('parent_id');
            }
        }

        $categories = $query->orderBy('nom')->paginate(20);

        // ===== STATS =====
        $stats = [
            'total' => Categorie::count(),
            'actives' => Categorie::where('is_active', true)->count(),
            'inactives' => Categorie::where('is_active', false)->count(),
            'parents' => Categorie::whereNull('parent_id')->count(),
            'enfants' => Categorie::whereNotNull('parent_id')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $parents = Categorie::whereNull('parent_id')->orderBy('nom')->get();
        return view('admin.categories.create', compact('parents'));
    }

    /**
     * Enregistrer une catégorie
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $categorie = Categorie::create([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'parent_id' => $request->parent_id ?? null,
            'description' => $request->description,
            'icone' => $request->icone ?? '📁',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Afficher une catégorie
     */
    public function show($id)
    {
        $categorie = Categorie::with(['parent', 'enfants', 'oeuvres' => function($query) {
                $query->limit(10)->orderBy('created_at', 'desc');
            }])
            ->withCount('oeuvres')
            ->findOrFail($id);

        return view('admin.categories.show', compact('categorie'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $categorie = Categorie::findOrFail($id);
        $parents = Categorie::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('nom')
            ->get();

        return view('admin.categories.edit', compact('categorie', 'parents'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom,' . $id,
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ], [
            'parent_id.not_in' => 'Une catégorie ne peut pas être son propre parent.',
        ]);

        $categorie->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->nom),
            'parent_id' => $request->parent_id ?? null,
            'description' => $request->description,
            'icone' => $request->icone ?? '📁',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);

        // Vérifier les sous-catégories
        if ($categorie->enfants()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : cette catégorie contient des sous-catégories.');
        }

        // Vérifier les œuvres
        if ($categorie->oeuvres()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : cette catégorie contient des œuvres.');
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Activer/Désactiver une catégorie
     */
    public function toggleActive($id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->is_active = !$categorie->is_active;
        $categorie->save();

        $message = $categorie->is_active ? 'Catégorie activée.' : 'Catégorie désactivée.';
        return back()->with('success', $message);
    }

    /**
     * Exporter les catégories en CSV
     */
    public function export()
    {
        $categories = Categorie::with(['parent'])->withCount('oeuvres')->get();

        $filename = 'categories_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Catégorie parente', 'Type', 'Icône', 'Statut', 'Nombre d\'œuvres', 'Date de création']);

            foreach ($categories as $categorie) {
                fputcsv($file, [
                    $categorie->id,
                    $categorie->nom,
                    $categorie->parent?->nom ?? 'Aucune',
                    $categorie->isParent() ? 'Principale' : 'Sous-catégorie',
                    $categorie->icone ?? '📁',
                    $categorie->is_active ? 'Actif' : 'Inactif',
                    $categorie->oeuvres_count,
                    $categorie->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
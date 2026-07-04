<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Oeuvre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogueController extends Controller
{
    /**
     * Page d'accueil avec les dernières œuvres
     */
    public function home()
    {
        $oeuvres = Oeuvre::where('statut', 'publiee')
            ->where('is_available', true)
            ->with(['artisan', 'devise', 'images', 'categorie'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $categories = Categorie::where('is_active', true)
            ->withCount(['oeuvres' => function($q) {
                $q->where('statut', 'publiee')->where('is_available', true);
            }])
            ->get();

        $stats = [
            'total_oeuvres' => Oeuvre::where('statut', 'publiee')->where('is_available', true)->count(),
            'total_artisans' => Oeuvre::where('statut', 'publiee')->distinct('user_id')->count('user_id'),
            'total_categories' => Categorie::where('is_active', true)->count(),
        ];

        return view('welcome', compact('oeuvres', 'categories', 'stats'));
    }

    /**
     * Catalogue complet
     */
    public function index(Request $request)
    {
        $query = Oeuvre::with(['artisan', 'categorie', 'devise', 'images'])
            ->where('statut', 'publiee')
            ->where('is_available', true);

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('artisan', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('entreprise', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Catégorie
        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie_id', $request->categorie);
        }

        // Prix
        if ($request->has('prix_min') && $request->prix_min) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max') && $request->prix_max) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Disponible
        if ($request->has('disponible') && $request->disponible) {
            $query->where('stock', '>', 0);
        }

        // À la une
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Tri
        $tri = $request->tri ?? 'recent';
        switch ($tri) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'populaire':
                $query->withCount('commandes')->orderBy('commandes_count', 'desc');
                break;
            case 'mieux_note':
                $query->withAvg('avis', 'note')->orderBy('avis_avg_note', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $oeuvres = $query->paginate(12);

        $categories = Categorie::where('is_active', true)
            ->withCount(['oeuvres' => function($q) {
                $q->where('statut', 'publiee')->where('is_available', true);
            }])
            ->get();

        $favoris = [];
        if (auth()->check()) {
            $favoris = auth()->user()->favoris()->pluck('oeuvre_id')->toArray();
        }

        $stats = [
            'total' => Oeuvre::where('statut', 'publiee')->where('is_available', true)->count(),
            'categories' => Categorie::where('is_active', true)->count(),
            'artisans' => Oeuvre::where('statut', 'publiee')->distinct('user_id')->count('user_id'),
            'prix_min' => Oeuvre::where('statut', 'publiee')->min('prix') ?? 0,
            'prix_max' => Oeuvre::where('statut', 'publiee')->max('prix') ?? 0,
        ];

        return view('client.catalogue.index', compact('oeuvres', 'categories', 'favoris', 'stats'));
    }

    public function show($slug)
    {
        $oeuvre = Oeuvre::with([
            'artisan',
            'categorie',
            'devise',
            'images',
            'avis' => function($query) {
                $query->where('is_approved', true)->orderBy('created_at', 'desc');
            },
            'avis.user'
        ])->where('slug', $slug)
          ->where('statut', 'publiee')
          ->firstOrFail();

        $similaires = Oeuvre::where('categorie_id', $oeuvre->categorie_id)
            ->where('id', '!=', $oeuvre->id)
            ->where('statut', 'publiee')
            ->where('is_available', true)
            ->limit(4)
            ->get();

        $stats = [
            'total_avis' => $oeuvre->avis()->where('is_approved', true)->count(),
            'moyenne_notes' => number_format($oeuvre->avis()->where('is_approved', true)->avg('note') ?? 0, 1),
            'repartition_notes' => [
                1 => $oeuvre->avis()->where('is_approved', true)->where('note', 1)->count(),
                2 => $oeuvre->avis()->where('is_approved', true)->where('note', 2)->count(),
                3 => $oeuvre->avis()->where('is_approved', true)->where('note', 3)->count(),
                4 => $oeuvre->avis()->where('is_approved', true)->where('note', 4)->count(),
                5 => $oeuvre->avis()->where('is_approved', true)->where('note', 5)->count(),
            ],
        ];

        $estFavori = false;
        if (auth()->check()) {
            $estFavori = Auth::user()->favoris()->where('oeuvre_id', $oeuvre->id)->exists();
        }

        $user = Auth::user();
        return view('client.catalogue.show', compact('oeuvre', 'similaires', 'stats', 'estFavori'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $oeuvres = Oeuvre::where('statut', 'publiee')
            ->where('is_available', true)
            ->where(function($q) use ($query) {
                $q->where('titre', 'LIKE', "%{$query}%")
                  ->orWhereHas('artisan', function($q2) use ($query) {
                      $q2->where('name', 'LIKE', "%{$query}%")
                         ->orWhere('entreprise', 'LIKE', "%{$query}%");
                  });
            })
            ->with(['artisan', 'devise', 'images'])
            ->limit(10)
            ->get()
            ->map(function($oeuvre) {
                return [
                    'id' => $oeuvre->id,
                    'titre' => $oeuvre->titre,
                    'prix' => number_format($oeuvre->prix_actuel, 2),
                    'devise' => $oeuvre->devise->symbole ?? 'FC',
                    'image' => $oeuvre->image_principale_url,
                    'artisan' => $oeuvre->artisan->name ?? 'Artisan',
                    'slug' => $oeuvre->slug,
                    'url' => route('client.oeuvre.show', $oeuvre->slug),
                ];
            });

        return response()->json($oeuvres);
    }

    public function filterByCategorie($categorieId)
    {
        $categorie = Categorie::findOrFail($categorieId);

        $oeuvres = Oeuvre::where('categorie_id', $categorieId)
            ->where('statut', 'publiee')
            ->where('is_available', true)
            ->with(['artisan', 'devise', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('client.catalogue.partials.grid', compact('oeuvres'))->render();
    }

    public function filterByPrix(Request $request)
    {
        $min = $request->min ?? 0;
        $max = $request->max ?? PHP_INT_MAX;

        $oeuvres = Oeuvre::where('statut', 'publiee')
            ->where('is_available', true)
            ->whereBetween('prix', [$min, $max])
            ->with(['artisan', 'devise', 'images'])
            ->orderBy('prix', 'asc')
            ->paginate(12);

        return view('client.catalogue.partials.grid', compact('oeuvres'))->render();
    }
}
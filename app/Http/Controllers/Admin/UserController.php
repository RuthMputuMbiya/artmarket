<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // ===== FILTRES =====
        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('entreprise', 'LIKE', "%{$search}%")
                  ->orWhere('telephone', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // ===== STATISTIQUES =====
        $stats = [
            'total' => User::count(),
            'admins' => User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->count(),
            'artisans' => User::whereHas('role', function($q) {
                $q->where('slug', 'artisan');
            })->count(),
            'clients' => User::whereHas('role', function($q) {
                $q->where('slug', 'client');
            })->count(),
        ];

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Enregistrer un utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,inactif,suspendu',
            'is_verified' => 'boolean',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'entreprise' => 'nullable|string|max:255',
            'siret' => 'nullable|string|max:14',
            'bio' => 'nullable|string',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:50',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_profil')) {
            $photoPath = $request->file('photo_profil')->store('users', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'telephone' => $request->telephone,
            'statut' => $request->statut,
            'is_verified' => $request->has('is_verified'),
            'photo_profil' => $photoPath,
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'bio' => $request->bio,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'pays' => $request->pays ?? 'France',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Afficher un utilisateur
     */
    public function show($id)
    {
        $user = User::with(['role', 'oeuvres', 'commandes', 'avis'])
            ->findOrFail($id);

        $stats = [
            'total_commandes' => $user->commandes()->count(),
            'total_oeuvres' => $user->oeuvres()->count(),
            'total_avis' => $user->avis()->count(),
            'total_depenses' => $user->commandes()->where('statut', 'livree')->sum('total_ttc') ?? 0,
            'derniere_connexion' => $user->last_login_at,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'required|in:actif,inactif,suspendu',
            'is_verified' => 'boolean',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'entreprise' => 'nullable|string|max:255',
            'siret' => 'nullable|string|max:14',
            'bio' => 'nullable|string',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('photo_profil')) {
            if ($user->photo_profil) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            $photoPath = $request->file('photo_profil')->store('users', 'public');
        } else {
            $photoPath = $user->photo_profil;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'telephone' => $request->telephone,
            'statut' => $request->statut,
            'is_verified' => $request->has('is_verified'),
            'photo_profil' => $photoPath,
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'bio' => $request->bio,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'pays' => $request->pays ?? 'France',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de modifier le statut d\'un administrateur.');
        }

        $newStatus = $user->statut === 'actif' ? 'suspendu' : 'actif';
        $user->update(['statut' => $newStatus]);

        $message = $newStatus === 'actif' ? 'Utilisateur activé.' : 'Utilisateur suspendu.';
        return back()->with('success', $message);
    }

    /**
     * Vérifier un utilisateur (artisan)
     */
    public function verify($id)
    {
        $user = User::findOrFail($id);

        if (!$user->isArtisan()) {
            return back()->with('error', 'Seul un artisan peut être vérifié.');
        }

        $user->is_verified = !$user->is_verified;
        $user->save();

        $message = $user->is_verified ? 'Artisan vérifié avec succès.' : 'Vérification de l\'artisan annulée.';
        return back()->with('success', $message);
    }

    /**
     * Exporter les utilisateurs en CSV
     */
    public function export(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->get();

        $filename = 'utilisateurs_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Email', 'Téléphone', 'Entreprise', 'Rôle', 'Statut', 'Vérifié', 'Date création']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->telephone ?? '',
                    $user->entreprise ?? '',
                    $user->role->nom ?? 'Client',
                    $user->statut,
                    $user->is_verified ? 'Oui' : 'Non',
                    $user->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
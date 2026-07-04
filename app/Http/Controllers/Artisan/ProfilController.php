<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Afficher le profil de l'artisan
     */
    public function index()
    {
     $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $stats = [
            'total_oeuvres' => $user->oeuvres()->count(),
            'total_commandes' => $user->oeuvres()->withCount('commandes')->get()->sum('commandes_count'),
            'total_ventes' => $user->oeuvres()->withSum('commandes', 'total')->get()->sum('commandes_sum_total'),
            'total_avis' => $user->oeuvres()->withCount('avis')->get()->sum('avis_count'),
            'avis_moyenne' => number_format($user->oeuvres()->withAvg('avis', 'note')->get()->avg('avis_avg_note') ?? 0, 1),
        ];

        return view('artisan.profil.index', compact('user', 'stats'));
    }

    /**
     * Formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        return view('artisan.profil.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
          $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'entreprise' => 'nullable|string|max:255',
            'siret' => 'nullable|string|max:14',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:10',
            'pays' => 'nullable|string|max:50',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Gestion de la photo
        $photoPath = $user->photo_profil;
        if ($request->hasFile('photo_profil')) {
            if ($user->photo_profil) {
                Storage::disk('public')->delete($user->photo_profil);
            }
            $photoPath = $request->file('photo_profil')->store('users', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'bio' => $request->bio,
            'entreprise' => $request->entreprise,
            'siret' => $request->siret,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'pays' => $request->pays ?? 'France',
            'photo_profil' => $photoPath,
        ]);

        return redirect()->route('artisan.profil.index')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
         $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('artisan.profil.index')
            ->with('success', 'Mot de passe mis à jour avec succès !');
    }

    /**
     * Supprimer le compte
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        // Vérifier si l'artisan a des commandes
        if ($user->oeuvres()->withCount('commandes')->get()->sum('commandes_count') > 0) {
            return back()->with('error', 'Impossible de supprimer votre compte car vous avez des commandes.');
        }

        // Supprimer la photo
        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        // Supprimer les œuvres et leurs images
        foreach ($user->oeuvres as $oeuvre) {
            foreach ($oeuvre->images as $image) {
                $path = str_replace('/storage/', '', $image->url);
                Storage::disk('public')->delete($path);
                $image->delete();
            }
            $oeuvre->delete();
        }

        $user->delete();

        auth()->logout();

        return redirect('/')->with('success', 'Votre compte a été supprimé.');
    }
}
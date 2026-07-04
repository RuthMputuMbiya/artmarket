<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Favoris;  // ✅ AVEC S
use App\Models\Oeuvre;
use Illuminate\Http\Request;

class FavorisController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Favoris::where('user_id', $user->id)  // ✅ AVEC S
            ->with(['oeuvre', 'oeuvre.artisan', 'oeuvre.devise', 'oeuvre.images']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('oeuvre', function($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $favoris = $query->orderBy('created_at', 'desc')->paginate(12);

        $stats = [
            'total' => Favoris::where('user_id', $user->id)->count(),  // ✅ AVEC S
            'categories' => Favoris::where('user_id', $user->id)  // ✅ AVEC S
                ->whereHas('oeuvre', function($q) {
                    $q->whereNotNull('categorie_id');
                })
                ->distinct('oeuvre_id')
                ->count(),
        ];

        return view('client.favoris.index', compact('favoris', 'stats'));
    }

    public function toggle(Request $request, $oeuvreId)
    {
        $user = auth()->user();
        $oeuvre = Oeuvre::findOrFail($oeuvreId);

        $favori = Favoris::where('user_id', $user->id)  // ✅ AVEC S
            ->where('oeuvre_id', $oeuvreId)
            ->first();

        if ($favori) {
            $favori->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Retiré des favoris.',
            ]);
        } else {
            Favoris::create([  // ✅ AVEC S
                'user_id' => $user->id,
                'oeuvre_id' => $oeuvreId,
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Ajouté aux favoris.',
            ]);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $favori = Favoris::where('user_id', $user->id)  // ✅ AVEC S
            ->with('oeuvre')
            ->findOrFail($id);

        $favori->delete();

        return redirect()->route('client.favoris.index')
            ->with('success', 'Œuvre retirée des favoris.');
    }

    public function clear()
    {
        $user = auth()->user();
        Favoris::where('user_id', $user->id)->delete();  // ✅ AVEC S

        return redirect()->route('client.favoris.index')
            ->with('success', 'Favoris vidés.');
    }

    public function check($oeuvreId)
    {
        $user = auth()->user();
        $isFavori = Favoris::where('user_id', $user->id)  // ✅ AVEC S
            ->where('oeuvre_id', $oeuvreId)
            ->exists();

        return response()->json(['is_favori' => $isFavori]);
    }
}
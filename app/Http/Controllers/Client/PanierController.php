<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Oeuvre;
use Illuminate\Http\Request;

class PanierController extends Controller
{
    public function index()
    {
        $panier = session()->get('panier', []);
        $total = 0;
        $deviseSymbole = 'FC';
        $items = [];

        foreach ($panier as $key => $item) {
            $oeuvre = Oeuvre::with(['artisan', 'devise'])->find($item['id']);

            if ($oeuvre) {
                $prix = $oeuvre->prix_promo ?? $oeuvre->prix;
                $items[] = [
                    'id' => $oeuvre->id,
                    'key' => $key,
                    'titre' => $oeuvre->titre,
                    'prix' => $prix,
                    'quantite' => $item['quantite'],
                    'artisan' => $oeuvre->artisan->name ?? 'Artisan',
                    'image' => $oeuvre->image_principale_url,
                    'stock' => $oeuvre->stock,
                    'devise_symbole' => $oeuvre->devise->symbole ?? 'FC',
                    'total' => $prix * $item['quantite'],
                ];
                $total += $prix * $item['quantite'];
                $deviseSymbole = $oeuvre->devise->symbole ?? 'FC';
            }
        }

        // ✅ AJOUT DE totalTTC (pas de frais de livraison)
        $totalTTC = $total;

        return view('client.panier.index', compact('items', 'total', 'totalTTC', 'deviseSymbole'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'oeuvre_id' => 'required|exists:oeuvres,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $oeuvre = Oeuvre::find($request->oeuvre_id);

        if ($oeuvre->stock < $request->quantite) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $panier = session()->get('panier', []);

        $existe = false;
        foreach ($panier as $key => $item) {
            if ($item['id'] == $request->oeuvre_id) {
                $nouvelleQuantite = $item['quantite'] + $request->quantite;
                if ($nouvelleQuantite > $oeuvre->stock) {
                    return back()->with('error', 'Quantité totale dépasse le stock disponible.');
                }
                $panier[$key]['quantite'] = $nouvelleQuantite;
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $panier[] = [
                'id' => $request->oeuvre_id,
                'quantite' => $request->quantite,
            ];
        }

        session()->put('panier', $panier);

        return redirect()->route('client.panier.index')
            ->with('success', 'Œuvre ajoutée au panier !');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        $panier = session()->get('panier', []);

        if (!isset($panier[$key])) {
            return redirect()->route('client.panier.index')
                ->with('error', 'Article non trouvé.');
        }

        $oeuvre = Oeuvre::find($panier[$key]['id']);

        if ($request->quantite > $oeuvre->stock) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $panier[$key]['quantite'] = $request->quantite;
        session()->put('panier', $panier);

        return redirect()->route('client.panier.index')
            ->with('success', 'Panier mis à jour.');
    }

    public function remove($key)
    {
        $panier = session()->get('panier', []);

        if (!isset($panier[$key])) {
            return redirect()->route('client.panier.index')
                ->with('error', 'Article non trouvé.');
        }

        unset($panier[$key]);
        $panier = array_values($panier);
        session()->put('panier', $panier);

        return redirect()->route('client.panier.index')
            ->with('success', 'Article retiré du panier.');
    }

    public function clear()
    {
        session()->forget('panier');

        return redirect()->route('client.panier.index')
            ->with('success', 'Panier vidé.');
    }

    public function count()
    {
        $panier = session()->get('panier', []);
        $count = 0;
        foreach ($panier as $item) {
            $count += $item['quantite'];
        }

        return response()->json(['count' => $count]);
    }
}
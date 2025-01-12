<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $produits = Produit::all();
        
        $produitsAlerte = Produit::whereColumn('quantite_stock', '<=', 'stock_minimum')->get();
        
        return view('stocks.index', compact('produits', 'produitsAlerte'));
    }

    public function ajuster(Request $request, Produit $produit)
    {
        $request->validate([
            'quantite' => 'required|integer',
            'type' => 'required|in:ajout,retrait'
        ]);

        if ($request->type === 'ajout') {
            $produit->increment('quantite_stock', $request->quantite);
        } else {
            if ($produit->quantite_stock >= $request->quantite) {
                $produit->decrement('quantite_stock', $request->quantite);
            } else {
                return back()->with('error', 'Stock insuffisant');
            }
        }

        return back()->with('success', 'Stock mis à jour');
    }

    public function updateMinimum(Request $request, Produit $produit)
    {
        $request->validate([
            'stock_minimum' => 'required|integer|min:0'
        ]);

        $produit->update([
            'stock_minimum' => $request->stock_minimum
        ]);

        return back()->with('success', 'Seuil minimum mis à jour');
    }
} 
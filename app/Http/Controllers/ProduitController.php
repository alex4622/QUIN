<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::latest()->get();
        return view('produits.index', compact('produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0'
        ]);

        Produit::create($request->all());
        return back()->with('success', 'Produit ajouté avec succès');
    }

    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'nom' => 'required',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0'
        ]);

        $produit->update($request->all());
        return back()->with('success', 'Produit modifié avec succès');
    }
} 
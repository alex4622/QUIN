<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Http\Request;

class AchatController extends Controller
{
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $produits = Produit::all();
        return view('achats.create', compact('fournisseurs', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id' => 'required',
            'produit_id' => 'required',
            'quantite' => 'required|numeric|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date' => 'required|date'
        ]);

        $produit = Produit::findOrFail($request->produit_id);

        $achat = Achat::create([
            'fournisseur_id' => $request->fournisseur_id,
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant_total' => $request->prix_unitaire * $request->quantite,
            'date' => $request->date
        ]);

        $produit->increment('quantite_stock', $request->quantite);

        return redirect()->route('dashboard')->with('success', 'Achat enregistré');
    }

    public function destroy($id)
    {
        $achat = Achat::findOrFail($id);
        
        // Déduire le stock des produits achetés
        foreach ($achat->produits as $produit) {
            $produit->stock -= $produit->pivot->quantite;
            $produit->save();
        }
        
        $achat->delete();
        
        return redirect()->back()->with('success', 'Achat supprimé avec succès');
    }

    public function index()
    {
        $achats = Achat::with(['fournisseur', 'produit'])->latest('date')->get();
        $fournisseurs = Fournisseur::all();
        $produits = Produit::all();
        return view('achats.index', compact('achats', 'fournisseurs', 'produits'));
    }

    public function update(Request $request, Achat $achat)
    {
        $request->validate([
            'fournisseur_id' => 'required',
            'produit_id' => 'required',
            'quantite' => 'required|numeric|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date' => 'required|date'
        ]);

        // Restaurer l'ancien stock
        $produit = Produit::findOrFail($achat->produit_id);
        $produit->decrement('quantite_stock', $achat->quantite);

        // Mettre à jour l'achat
        $achat->update([
            'fournisseur_id' => $request->fournisseur_id,
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'montant_total' => $request->prix_unitaire * $request->quantite,
            'date' => $request->date
        ]);

        // Mettre à jour le nouveau stock
        $nouveauProduit = Produit::findOrFail($request->produit_id);
        $nouveauProduit->increment('quantite_stock', $request->quantite);

        return redirect()->back()->with('success', 'Achat modifié avec succès');
    }
} 
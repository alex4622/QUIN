<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::latest()->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'telephone' => 'nullable',
            'email' => 'nullable|email',
            'adresse' => 'nullable'
        ]);

        Fournisseur::create($request->all());
        return back()->with('success', 'Fournisseur ajouté avec succès');
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom' => 'required',
            'telephone' => 'nullable',
            'email' => 'nullable|email',
            'adresse' => 'nullable'
        ]);

        $fournisseur->update($request->all());
        return back()->with('success', 'Fournisseur modifié avec succès');
    }

    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé avec succès.');
    }
} 
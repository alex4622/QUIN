<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Achat;
use App\Models\Produit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVentes = Vente::sum('montant_total');
        $totalAchats = Achat::sum('montant_total');
        $nombreProduits = Produit::sum('quantite_stock');
        $benefices = $totalVentes - $totalAchats;

        $dernieresVentes = Vente::with(['client', 'lignes.produit'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $derniersAchats = Achat::with(['fournisseur', 'produit'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('welcome', compact(
            'totalVentes',
            'totalAchats',
            'nombreProduits',
            'benefices',
            'dernieresVentes',
            'derniersAchats'
        ));
    }
} 
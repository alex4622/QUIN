<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VenteController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        $produits = Produit::where('quantite_stock', '>', 0)->get();
        return view('ventes.create', compact('clients', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'produits' => 'required|array',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|numeric|min:1',
            'date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Créer la vente
            $vente = Vente::create([
                'client_id' => $request->client_id,
                'date' => $request->date,
                'montant_total' => 0
            ]);

            $montantTotal = 0;

            // Traiter chaque produit
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['produit_id']);
                
                if ($produit->quantite_stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}");
                }

                $montantLigne = $produit->prix_vente * $item['quantite'];
                $montantTotal += $montantLigne;

                // Créer la ligne de vente
                $vente->lignes()->create([
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $produit->prix_vente,
                    'montant' => $montantLigne
                ]);

                // Mettre à jour le stock
                $produit->decrement('quantite_stock', $item['quantite']);
            }

            // Mettre à jour le montant total
            $vente->update(['montant_total' => $montantTotal]);

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Vente enregistrée avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', "Erreur lors de l'enregistrement : " . $e->getMessage())
                ->withInput();
        }
    }

    public function generatePDF(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        
        $ventes = Vente::with(['client', 'lignes.produit'])
            ->whereDate('date', $date)
            ->get();

        $total = $ventes->sum('montant_total');

        $pdf = PDF::loadView('pdf.ventes', compact('ventes', 'total', 'date'));
        
        return $pdf->download('ventes-' . $date . '.pdf');
    }

    public function destroy($id)
    {
        $vente = Vente::findOrFail($id);
        
        // Restaurer le stock des produits vendus
        foreach ($vente->produits as $produit) {
            $produit->stock += $produit->pivot->quantite;
            $produit->save();
        }
        
        $vente->delete();
        
        return redirect()->back()->with('success', 'Vente supprimée avec succès');
    }

    public function index()
    {
        $ventes = Vente::with('client')->latest('date')->get();
        $clients = Client::all();
        $produits = Produit::where('quantite_stock', '>', 0)->get();
        return view('ventes.index', compact('ventes', 'clients', 'produits'));
    }

    public function update(Request $request, Vente $vente)
    {
        $request->validate([
            'client_id' => 'required',
            'produits' => 'required|array',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|numeric|min:1',
            'date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Restaurer le stock des produits de l'ancienne vente
            foreach ($vente->lignes as $ligne) {
                $produit = $ligne->produit;
                $produit->increment('quantite_stock', $ligne->quantite);
            }

            // Supprimer les anciennes lignes
            $vente->lignes()->delete();

            // Mettre à jour la vente
            $vente->update([
                'client_id' => $request->client_id,
                'date' => $request->date,
                'montant_total' => 0
            ]);

            $montantTotal = 0;

            // Créer les nouvelles lignes
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['produit_id']);
                
                if ($produit->quantite_stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}");
                }

                $montantLigne = $produit->prix_vente * $item['quantite'];
                $montantTotal += $montantLigne;

                $vente->lignes()->create([
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $produit->prix_vente,
                    'montant' => $montantLigne
                ]);

                $produit->decrement('quantite_stock', $item['quantite']);
            }

            $vente->update(['montant_total' => $montantTotal]);

            DB::commit();
            return redirect()->back()->with('success', 'Vente modifiée avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', "Erreur lors de la modification : " . $e->getMessage())
                ->withInput();
        }
    }
} 
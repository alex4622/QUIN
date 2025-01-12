<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    protected $fillable = [
        'fournisseur_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
} 
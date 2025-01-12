<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom', 
        'description', 
        'prix_achat', 
        'prix_vente', 
        'quantite_stock',
        'stock_minimum'
    ];

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }
} 
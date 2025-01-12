<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $fillable = [
        'client_id',
        'montant_total',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneVente::class);
    }
} 
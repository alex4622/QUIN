<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = ['nom', 'telephone', 'email', 'adresse'];

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }
} 
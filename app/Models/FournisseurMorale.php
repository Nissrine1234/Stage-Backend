<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FournisseurMorale extends Authenticatable
{
    use HasFactory;

    protected $table = 'fournisseurs_morales';

    protected $fillable = [
        'user_id',
        'code_postal',
        'nom_entreprise',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(ServiceAchat::class, 'fournisseur_morale_service');
    }

    public function genererMotDePasse()
    {
        return optional($this->user)->nom_entreprise . '@2025';
    }
}


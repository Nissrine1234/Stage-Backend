<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FournisseurPhysique extends Authenticatable
{
    use HasFactory;

    protected $table = 'fournisseurs_physiques';

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'cin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(ServiceAchat::class, 'fournisseur_physique_service');
    }

    public function genererMotDePasse()
    {
        return optional($this->user)->cin . '@2025';
    }
}

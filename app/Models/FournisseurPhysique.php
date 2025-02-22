<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FournisseurPhysique extends Authenticatable
{
    use HasFactory;

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
        return $this->cin . '@2025';
    }
}

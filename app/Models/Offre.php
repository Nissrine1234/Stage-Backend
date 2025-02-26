<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $table = 'offres';

    protected $fillable = [
        'appel_offre_id',
        'fournisseur_physique_id',
        'fournisseur_morale_id',
        'montant',
        'delai',
        'status',
    ];

    public function fournisseur()
    {
        return $this->role_type === 'fournisseur_physique'
            ? $this->hasOne(FournisseurPhysique::class, 'user_id')
            : $this->hasOne(FournisseurMorale::class, 'user_id');
    }

    public function appelOffre()
    {
        return $this->belongsTo(AppelOffre::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'appel_offre_id',
        'fournisseur_physique_id',
        'fournisseur_morale_id',
        'montant',
        'delai',
        'status',
    ];

    public function appelOffre()
    {
        return $this->belongsTo(AppelOffre::class);
    }
}

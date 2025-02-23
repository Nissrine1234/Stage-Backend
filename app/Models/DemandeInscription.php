<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeInscription extends Model
{
    use HasFactory;

    protected $table = 'demandes_inscriptions';

    protected $fillable = [
        'type_fournisseur',
        'email',
        'telephone',
        'date_demande',
        'nom_entreprise',
        'code_postal',
        'nom',
        'prenom',
        'cin',
    ];
}

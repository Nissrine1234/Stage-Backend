<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FournisseurPhysiqueService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_achat_id',
        'fournisseur_physique_id',
    ];
}
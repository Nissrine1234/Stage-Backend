<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FournisseurPhysiqueService extends Model
{
    use HasFactory;

    protected $table = 'fournisseur_physique_service';


    protected $fillable = [
        'service_achat_id',
        'fournisseur_physique_id',
    ];
}

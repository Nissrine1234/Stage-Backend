<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FournisseurMoraleService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_achat_id',
        'fournisseur_morale_id',
    ];
}

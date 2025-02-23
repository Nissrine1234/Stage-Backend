<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FournisseurMoraleService extends Model
{
    use HasFactory;

    protected $table = 'fournisseur_morale_service';
    
    protected $fillable = [
        'service_achat_id',
        'fournisseur_morale_id',
    ];
}


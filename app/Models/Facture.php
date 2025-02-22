<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'montant',
        'date_emission',
        'date_paiement',
        'statut',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}

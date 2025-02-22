<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'offre_id',
        'statut',
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}

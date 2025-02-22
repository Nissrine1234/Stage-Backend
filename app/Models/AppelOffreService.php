<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppelOffreService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_achat_id',
        'appel_offre_id',
    ];
}

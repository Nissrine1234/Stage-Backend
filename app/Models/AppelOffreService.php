<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppelOffreService extends Model
{
    use HasFactory;

    protected $table = 'appel_offre_service';

    protected $fillable = [
        'service_achat_id',
        'appel_offre_id',
    ];
}

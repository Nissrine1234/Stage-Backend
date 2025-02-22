<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAchat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_service',
        'responsable',
        'email',
        'password',
    ];
}

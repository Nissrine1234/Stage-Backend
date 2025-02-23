<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAchat extends Model
{
    use HasFactory;

    protected $table = 'service_achats';

    protected $fillable = [
        'nom_service',
        'responsable',
        'email',
        'password',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppelOffre extends Model
{
    use HasFactory;

    protected $table = 'appels_offres';

    protected $fillable = [
        'titre',
        'details',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppelOffre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'details',
    ];
}

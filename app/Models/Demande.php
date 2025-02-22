<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

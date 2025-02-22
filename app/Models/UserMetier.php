<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMetier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'cin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

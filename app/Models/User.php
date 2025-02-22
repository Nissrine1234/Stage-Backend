<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'role_type',
        'role_id',
        'email',
        'password',
        'adresse',
        'telephone',
    ];

    public function role()
    {
        return match ($this->role_type) {
            'admin' => $this->hasOne(Admin::class, 'id', 'role_id'),
            'user_metier' => $this->hasOne(UserMetier::class, 'id', 'role_id'),
            'fournisseur_morale' => $this->hasOne(FournisseurMorale::class, 'id', 'role_id'),
            'fournisseur_physique' => $this->hasOne(FournisseurPhysique::class, 'id', 'role_id'),
            'service_achat' => $this->hasOne(ServiceAchat::class, 'id', 'role_id'),
            default => null,
        };
    }
}

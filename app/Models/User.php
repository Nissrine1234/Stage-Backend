<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory , HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_type',
        'role_id',
        'email',
        'password',
        'adresse',
        'telephone',
    ];
    // Définir l'association avec le modèle de rôle
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


    public function details()
    {
        return $this->role_type === 'fournisseur_physique'
            ? $this->hasOne(FournisseurPhysique::class, 'user_id')
            : $this->hasOne(FournisseurMorale::class, 'user_id');
    }

    // Accessor pour ajouter les attributs dynamiquement
    public function getRoleAttributesAttribute()
    {
        if ($this->role_type === 'fournisseur_morale') {
            $fournisseurMorale = $this->role; // Charge le fournisseur morale lié
            return [
                'nom_entreprise' => $fournisseurMorale ? $fournisseurMorale->nom_entreprise : null,
                'code_postal' => $fournisseurMorale ? $fournisseurMorale->code_postal : null,
            ];
        }

        if ($this->role_type === 'fournisseur_physique') {
            $fournisseurPhysique = $this->role; // Charge le fournisseur physique lié
            return [
                'cin' => $fournisseurPhysique ? $fournisseurPhysique->cin : null,
                'nom' => $fournisseurPhysique ? $fournisseurPhysique->nom : null,
                'prenom' => $fournisseurPhysique ? $fournisseurPhysique->prenom : null,
            ];
        }

        return [];
    }

    // Méthode toArray pour personnaliser la structure de la réponse JSON
    public function toArray()
    {
        $array = parent::toArray();

        // Ajouter les attributs spécifiques basés sur le rôle
        $array['details'] = $this->roleAttributes;

        return $array;
    }
}

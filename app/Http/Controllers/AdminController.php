<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function acceptFournisseur($id)
{
    // Trouver le fournisseur
    $fournisseur = User::findOrFail($id);

    // Vérifier si c'est bien un fournisseur et qu'il est en attente
    if ($fournisseur->role_type !== 'fournisseur_physique' || $fournisseur->status !== 'pending') {
        return response()->json(['message' => 'Demande invalide ou déjà traitée'], 400);
    }

    // Générer un mot de passe aléatoire
    $plainPassword = Str::random(10); // Exemple: "aBcD1234Xy"

    // Mettre à jour le statut et stocker le mot de passe haché
    $fournisseur->update([
        'status' => 'accepted',
        'password' => Hash::make($plainPassword),
    ]);

    // Envoyer l'email avec les identifiants
    Mail::to($fournisseur->email)->send(new SendPasswordMail($fournisseur, $plainPassword));

    return response()->json(['message' => 'Fournisseur accepté et mot de passe envoyé.']);
}

}

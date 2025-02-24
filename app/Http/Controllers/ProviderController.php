<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FournisseurMorale;
use App\Models\FournisseurPhysique;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * Inscription autonome d'un fournisseur
     */
    public function becomeProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'role_type' => 'required|in:fournisseur_morale,fournisseur_physique',
            'nom_entreprise' => 'required_if:role_type,fournisseur_morale',
            'code_postal' => 'required_if:role_type,fournisseur_morale',
            'nom' => 'required_if:role_type,fournisseur_physique',
            'prenom' => 'required_if:role_type,fournisseur_physique',
            'cin' => 'required_if:role_type,fournisseur_physique'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Création de l'utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'role_type' => $request->role_type
        ]);

        // Associer le fournisseur à la bonne table
        if ($request->role_type === 'morale') {
            FournisseurMorale::create([
                'user_id' => $user->id,
                'nom_entreprise' => $request->nom_entreprise,
                'code_postal' => $request->code_postal
            ]);
        } else {
            FournisseurPhysique::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'cin' => $request->cin
            ]);
        }

        return response()->json(['message' => 'Fournisseur inscrit avec succès', 'user' => $user], 201);
    }

    /**
     * Création d'un fournisseur par un administrateur
     */
    public function createProvider(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        if (auth()->user()->role_type !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'role_type' => 'required|in:morale,physique',
            'nom_entreprise' => 'required_if:role_type,morale',
            'code_postal' => 'required_if:role_type,morale',
            'nom' => 'required_if:role_type,physique',
            'prenom' => 'required_if:role_type,physique',
            'cin' => 'required_if:role_type,physique'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Création de l'utilisateur
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'role_type' => 'fournisseur_' . $request->role_type
        ]);

        // Associer le fournisseur à la bonne table
        if ($request->role_type === 'morale') {
            FournisseurMorale::create([
                'user_id' => $user->id,
                'nom_entreprise' => $request->nom_entreprise,
                'code_postal' => $request->code_postal
            ]);
        } else {
            FournisseurPhysique::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'cin' => $request->cin
            ]);
        }

        return response()->json(['message' => 'Fournisseur créé avec succès par l\'admin'], 201);
    }

    /**
     * Mise à jour des informations d'un fournisseur
     */
    public function updateProvider(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Fournisseur non trouvé'], 404);
        }

        $user->update($request->only(['email', 'adresse', 'telephone']));

        if ($user->role_type === 'fournisseur_morale') {
            $user->fournisseurMorale->update($request->only(['nom_entreprise', 'code_postal']));
        } else {
            $user->fournisseurPhysique->update($request->only(['nom', 'prenom', 'cin']));
        }

        return response()->json(['message' => 'Fournisseur mis à jour avec succès']);
    }

    /**
     * Suppression d'un fournisseur
     */
    public function deleteProvider($id)
    {
        // Vérifier que l'utilisateur est administrateur
        if (auth()->user()->role_type !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Fournisseur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Fournisseur supprimé avec succès']);
    }
}

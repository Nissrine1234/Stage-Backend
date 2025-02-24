<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\FournisseurMorale;
use App\Models\FournisseurPhysique;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string',
            'role_type' => 'required|in:fournisseur_morale,fournisseur_physique',
            'nom_entreprise' => 'required_if:role_type,fournisseur_morale',
            'code_postal' => 'required_if:role_type,fournisseur_morale',
            'nom' => 'required_if:role_type,fournisseur_physique',
            'prenom' => 'required_if:role_type,fournisseur_physique',
            'cin' => 'required_if:role_type,fournisseur_physique'

        ]);
        // Vérifier les erreurs de validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'role_type' => $request->role_type,
            'role_id' => null, // Temporairement null
        ]);
    
        // Étape 2 : Créer le fournisseur avec user_id
        if ($request->role_type === 'fournisseur_morale') {
            $fournisseur = FournisseurMorale::create([
                'nom_entreprise' => $request->nom_entreprise,
                'code_postal' => $request->code_postal,
                'user_id' => $user->id, // Associe le fournisseur à l'utilisateur
            ]);
        } else {
            $fournisseur = FournisseurPhysique::create([
                'nom' => $request->nom,  // Ajoute ce champ si nécessaire
                'prenom' => $request->prenom,  // Ajoute ce champ si nécessaire
                'cin' => $request->cin,  // Ajoute ce champ si nécessaire
                'user_id' => $user->id, // Associe le fournisseur à l'utilisateur
            ]);
        }
    

            // Étape 3 : Mettre à jour role_id de l'utilisateur
        $user->update(['role_id' => $fournisseur->id]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'fournisseur' => $fournisseur,
        ], 201);
    }

    /**
     * Connexion d'un utilisateur
     */
    public function login(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Tentative de connexion
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        // Supprimer le token de l'utilisateur
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}

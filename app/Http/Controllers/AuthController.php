<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\FournisseurMorale;
use App\Models\DemandeInscription;
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


        \Log::info('Données reçues pour inscription', $request->all());

        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6', // Maintenant optionnel 
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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }


        if ($request->role_type === 'fournisseur_morale') {
            $password = $request->nom_entreprise . '@2025';
        } else {
            $password = $request->cin . '@2025';
        }


        $user = User::create([
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'role_type' => $request->role_type,
            'role_id' => null, // Temporairement null
            'password' => Hash::make($password), // Hash le mot de passe
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
        \Log::info('Type fournisseur : ' . ($request->role_type === 'fournisseur_physique' ? 'physique' : 'moral'));

        DemandeInscription::create([
            'type_fournisseur' => $request->role_type === 'fournisseur_physique' ? 'physique' : 'moral',
            'email' => $request->email,
            'telephone' => $request->telephone ?? null,
            'nom_entreprise' => $request->nom_entreprise ?? null,
            'code_postal' => $request->code_postal ?? null,
            'nom' => $request->nom ?? null,
            'prenom' => $request->prenom ?? null,
            'cin' => $request->cin ?? null,
            'date_demande' => now(),
        ]);
        

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

        // Récupérer l'utilisateur
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        \Log::info('Mot de passe entré : ' . $request->password);
        \Log::info('Mot de passe stocké : ' . $user->password);



        // Vérifier le mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
    
        // Vérifier s'il s'agit bien d'un fournisseur
        if (!in_array($user->role_type, ['fournisseur_physique', 'fournisseur_morale'])) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        
    
        // Générer le token d'authentification
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

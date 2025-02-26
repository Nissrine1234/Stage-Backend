<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FournisseurMorale;
use App\Models\FournisseurPhysique;
use App\Models\FournisseurMoraleService;
use App\Models\FournisseurPhysiqueService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * Inscription autonome d'un fournisseur
     */
    public function becomeProvider(Request $request)
    {
        \Log::info('🔍 Données reçues dans becomeProvider:', $request->all());
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
        DB::beginTransaction();


        if ($request->role_type === 'fournisseur_morale') {
            $password = $request->nom_entreprise . '@2025';
        } else {
            $password = $request->cin . '@2025';
        }


    
        // Création de l'utilisateur
        try {
            $user = User::create([
                'email' => $request->email,
                'adresse' => $request->adresse,
                'telephone' => $request->telephone,
                'password' => Hash::make($password), // Hash le mot de passe
                'role_type' => $request->role_type
            ]);
        
            if (!$user) {
                DB::rollBack();
                return response()->json(['error' => 'Problème lors de l\'inscription'], 500);
            }


                    
        // Vérification du type de fournisseur
        if ($request->role_type === 'fournisseur_morale') {
            $fournisseur = FournisseurMorale::create([
                'user_id' => $user->id,
                'nom_entreprise' => $request->nom_entreprise,
                'code_postal' => $request->code_postal
            ]);
        } else {
            $fournisseur = FournisseurPhysique::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'cin' => $request->cin
            ]);
        }
    
        
        // Mise à jour du champ role_id dans users
        $user->update([
            'role_id' => $fournisseur->id
        ]);
        DB::commit(); 
    
        return response()->json(['data_received' => $request->all()], 200);
        dd($user);
        
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }
    

            /**
         * Liste des fournisseurs en fonction de leur type (morale ou physique)
         */
        public function listProviders(Request $request)
        {
            // Vérifier si un type de fournisseur est spécifié dans la requête
            $typeFournisseur = $request->query('role_type'); // 'morale' ou 'physique'

            if ($typeFournisseur) {
                // Si un type est précisé, récupérer les fournisseurs de ce type
                if ($typeFournisseur === 'morale') {
                    $providers = FournisseurMorale::with('user')->get(); // Récupère les fournisseurs moraux
                } elseif ($typeFournisseur === 'physique') {
                    $providers = FournisseurPhysique::with('user')->get(); // Récupère les fournisseurs physiques
                } else {
                    return response()->json(['message' => 'Type de fournisseur non valide'], 400);
                }
            } else {
                // Si aucun type n'est précisé, récupérer tous les fournisseurs (morale et physique)
                $providers = collect([
                    'morale' => FournisseurMorale::with('user')->get(),
                    'physique' => FournisseurPhysique::with('user')->get()
                ]);
            }

            return response()->json($providers);
        }




        public function dashboard(Request $request)
        {
            // Récupérer l'utilisateur connecté
            $user = $request->user();

            // Récupérer les données nécessaires
            $offresEnCours = Offre::where('fournisseur_id', $user->id)
                ->where('statut', 'en attente')
                ->count();

            $offresAcceptees = Offre::where('fournisseur_id', $user->id)
                ->where('statut', 'acceptée')
                ->count();

            $offresRefusees = Offre::where('fournisseur_id', $user->id)
                ->where('statut', 'refusée')
                ->count();

            $appelsOffresRecents = AppelOffre::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Retourner les données au format JSON
            return response()->json([
                'offres_en_cours' => $offresEnCours,
                'offres_acceptees' => $offresAcceptees,
                'offres_refusees' => $offresRefusees,
                'appels_offres_recents' => $appelsOffresRecents,
            ]);
        }


    /**
     * Création d'un fournisseur par un administrateur
     */
    public function createProvider(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        // if (auth()->user()->role_type !== 'admin') {
        //     return response()->json(['message' => 'Accès refusé'], 403);
        // }
    
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
                'code_postal' => $request->code_postal,
            ]);
        } elseif ($request->role_type === 'physique') {
            FournisseurPhysique::create([
                'user_id' => $user->id,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'cin' => $request->cin,
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

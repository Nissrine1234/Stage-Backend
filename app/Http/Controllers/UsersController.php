<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'role_type' => $request->role_type,
            'nom_entreprise' => $request->nom_entreprise,
            'code_postal' => $request->code_postal,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'cin' => $request->cin,
        ]);
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
    
        // Définir dynamiquement la relation en fonction du role_type
        $roleDetails = match ($user->role_type) {
            'fournisseur_morale' => $user->fournisseurMorale,
            'fournisseur_physique' => $user->fournisseurPhysique,
            'user_metier' => $user->userMetier,
            'admin' => $user->admin,
            'service_achat' => $user->serviceAchat,
            default => null,
        };
    
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'role_type' => $user->role_type,
            'telephone' => $user->telephone,
            'adresse' => $user->adresse,
            'details' => $roleDetails, // Ajoute les détails du fournisseur
        ]);
    }
    


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
}

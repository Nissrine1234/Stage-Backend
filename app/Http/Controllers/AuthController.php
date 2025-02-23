<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
            'role_type' => 'required|string',
            'role_id' => 'required|integer'
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
            'role_type' => $request->role_type,
            'role_id' => $request->role_id
        ]);

        return response()->json(['message' => 'Inscription réussie', 'user' => $user], 201);
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

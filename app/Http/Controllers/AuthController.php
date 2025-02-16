<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        // Valider les données saisies par l'utilisateur
        $this->validate($request, [
            'nom' => 'required',
            'email' => 'required|email|unique:providers',
            'password' => 'required|confirmed',
        ]);

        // Créer un nouveau fournisseur dans la base de données
        $provider = new Provider();
        $provider->nom = $request->input('nom');
        $provider->email = $request->input('email');
        $provider->password = bcrypt($request->input('password'));
        $provider->save();

        // Envoyer un e-mail de confirmation avec un mot de passe temporaire
        $token = Str::random(60);
        $provider->token = $token;
        $provider->save();
    
        Mail::to($provider->email)->send(new WelcomeEmail($provider));
    
        return redirect()->route('login')->with('success', 'Inscription réussie !');
    
    }

    public function login(Request $request){
        // Valider les données saisies par l'utilisateur
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Vérifier si l'utilisateur existe dans la base de données et si le mot de passe est correct
        $infoAuth  = $request->only(['email', 'password']);
        if (Auth::attempt($infoAuth)) {
            // Connecter l'utilisateur et le rediriger vers la page d'accueil
            return redirect()->route('home');
        } else {
            // Si l'utilisateur n'existe pas ou que le mot de passe est incorrect, afficher un message d'erreur
            return back()->withErrors(['email' => 'Adresse e-mail ou mot de passe incorrect']);
        }
    }
    public function forgotPassword(Request $request){
        // Valider l'adresse e-mail saisie par l'utilisateur
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        // Vérifier si l'utilisateur existe dans la base de données
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            // Si l'utilisateur n'existe pas, afficher un message d'erreur
            return back()->withErrors(['email' => 'Adresse e-mail non trouvée']);
        }
        // Générer un token de récupération de mot de passe
        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->save();
        // Envoyer un e-mail à l'utilisateur avec un lien de récupération de mot de passe
        Mail::to($user->email)->send(new PasswordResetEmail($user, $token));
        // Afficher un message de confirmation
        return back()->with('success', 'Un e-mail de récupération de mot de passe a été envoyé à votre adresse e-mail');
    }
    
}

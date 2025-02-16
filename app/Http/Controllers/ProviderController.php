<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    //becomeProvider est une méthode qui permet à un fournisseur de s'inscrire lui-même.
    public function becomeProvider(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:fournisseurs',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|confirmed',
        ]);

        $provider = new Fournisseur();
        $provider->name = $request->input('name');
        $provider->email = $request->input('email');
        $provider->phone = $request->input('phone');
        $provider->address = $request->input('address');
        $provider->password = bcrypt($request->input('password'));
        $provider->save();

        return redirect()->route('login')->with('success', 'Inscription réussie !');
    }

    //createProvider est une méthode qui permet à un administrateur de créer un nouveau fournisseur.
    public function createProvider(Request $request){
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $provider = new Provider();
        $provider->nom = $request->input('nom');
        $provider->email = $request->input('email');
        $provider->phone = $request->input('phone');
        $provider->address = $request->input('address');
        $provider->save();
        return redirect()->route('providers.index')->with('success', 'Fournisseur ajouté avec succès');
    }

    public function updateProvider(Request $request, $id){
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $provider = Provider::find($id);
        $provider->nom = $request->input('nom');
        $provider->email = $request->input('email');
        $provider->phone = $request->input('phone');
        $provider->address = $request->input('address');
        $provider->save();
        return redirect()->route('providers.index')->with('success', 'Fournisseur mis à jour avec succès');  
    
    }

    public function deleteProvider($id){
        $provider = Provider::find($id);
        $provider->delete();

        return redirect()->route('providers.index')->with('success', 'Fournisseur supprimé avec succès');
    }
}

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
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);

        // Définir dynamiquement le nom du détail en fonction du rôle
        $roleDetailsKey = match ($user->role) {
            'fournisseur_moral' => 'fournisseur_moral_details',
            'fournisseur_physique' => 'fournisseur_physique_details',
            'user_metier' => 'user_metier_details',
            'admin' => 'admin_details',
            'service_achat' => 'service_achat_details',
            default => 'details',
        };

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            $roleDetailsKey => $user->role
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

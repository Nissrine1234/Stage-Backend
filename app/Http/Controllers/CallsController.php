<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppelOffre;
use Illuminate\Support\Facades\Validator;

class CallsController extends Controller
{
    /**
     * Liste des appels d'offres
     */
    public function listCalls()
    {
        $appels = AppelOffre::all();
        return response()->json($appels);
    }

    /**
     * Création d'un appel d'offres
     */
    public function createCall(Request $request)
    {
        // Vérifier si l'utilisateur est un administrateur ou un service d'achat
        if (auth()->user()->role_type !== 'admin' && auth()->user()->role_type !== 'service_achat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'details' => 'required|string',
            'dateLimite' => 'required|date|after:today',
            'demande_id' => 'required|exists:demandes,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $appel = AppelOffre::create([
            'titre' => $request->titre,
            'details' => $request->details,
            'dateLimite' => $request->dateLimite,
            'demande_id' => $request->demande_id,
            'user_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Appel d\'offres créé avec succès', 'appel' => $appel], 201);
    }

    /**
     * Mise à jour d'un appel d'offres
     */
    public function updateCall(Request $request, $id)
    {
        $appel = AppelOffre::find($id);
        if (!$appel) {
            return response()->json(['message' => 'Appel d\'offres non trouvé'], 404);
        }

        // Vérifier si l'utilisateur est un administrateur ou un service d'achat
        if (auth()->user()->role_type !== 'admin' && auth()->user()->role_type !== 'service_achat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $appel->update($request->only(['titre', 'details', 'dateLimite']));
        return response()->json(['message' => 'Appel d\'offres mis à jour avec succès']);
    }

    /**
     * Suppression d'un appel d'offres
     */
    public function deleteCall($id)
    {
        $appel = AppelOffre::find($id);
        if (!$appel) {
            return response()->json(['message' => 'Appel d\'offres non trouvé'], 404);
        }

        // Vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role_type !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $appel->delete();
        return response()->json(['message' => 'Appel d\'offres supprimé avec succès']);
    }

    /**
     * Affichage des détails d'un appel d'offres
     */
    public function showCall($id)
    {
        $appel = AppelOffre::find($id);
        if (!$appel) {
            return response()->json(['message' => 'Appel d\'offres non trouvé'], 404);
        }

        return response()->json($appel);
    }
}

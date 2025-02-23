<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Offre;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OffresController extends Controller
{

    
    /**
     * Liste des offres
     */
    public function listOffers()
    {
        $offres = Offre::all();
        return response()->json($offres);
    }

    /**
     * Création d'une offre
     */
    public function createOffer(Request $request)
    {
        // Vérifier si l'utilisateur est un fournisseur
        if (auth()->user()->role_type !== 'fournisseur_morale' && auth()->user()->role_type !== 'fournisseur_physique') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'appel_offre_id' => 'required|exists:appels_offres,id',
            'montant' => 'required|numeric|min:1',
            'delai' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Vérifier si l'utilisateur est un fournisseur moral ou physique
        $fournisseur_id = auth()->user()->role_type === 'fournisseur_morale' ? 'fournisseur_morale_id' : 'fournisseur_physique_id';

        $offre = Offre::create([
            'appel_offre_id' => $request->appel_offre_id,
            $fournisseur_id => auth()->user()->role_id,
            'montant' => $request->montant,
            'delai' => $request->delai,
            'status' => 'en attente'
        ]);

        return response()->json(['message' => 'Offre soumise avec succès', 'offre' => $offre], 201);
    }

    /**
     * Mise à jour d'une offre
     */
    public function updateOffer(Request $request, $id)
    {
        $offre = Offre::find($id);
        if (!$offre) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }

        // Vérifier si l'utilisateur est le propriétaire de l'offre
        if ($offre->fournisseur_morale_id != auth()->user()->role_id && $offre->fournisseur_physique_id != auth()->user()->role_id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $offre->update($request->only(['montant', 'delai']));
        return response()->json(['message' => 'Offre mise à jour avec succès']);
    }

    /**
     * Suppression d'une offre
     */
    public function deleteOffer($id)
    {
        $offre = Offre::find($id);
        if (!$offre) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }

        // Vérifier si l'utilisateur est le propriétaire de l'offre
        if ($offre->fournisseur_morale_id != auth()->user()->role_id && $offre->fournisseur_physique_id != auth()->user()->role_id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $offre->delete();
        return response()->json(['message' => 'Offre supprimée avec succès']);
    }

    /**
     * Affichage des détails d'une offre
     */
    public function showOffer($id)
    {
        $offre = Offre::find($id);
        if (!$offre) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }

        return response()->json($offre);
    }
}

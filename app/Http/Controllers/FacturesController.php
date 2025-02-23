<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\Demande;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FacturesController extends Controller
{
    /**
     * Liste des factures
     */
    public function listFactures()
    {
        $factures = Facture::all();
        return response()->json($factures);
    }

    /**
     * Création d'une facture
     */
    public function createFacture(Request $request)
    {
        // Vérifier si l'utilisateur est un administrateur ou service d'achat
        if (auth()->user()->role_type !== 'admin' && auth()->user()->role_type !== 'service_achat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'demande_id' => 'required|exists:demandes,id',
            'montant' => 'required|numeric|min:1',
            'date_emission' => 'required|date',
            'date_paiement' => 'nullable|date|after_or_equal:date_emission',
            'statut' => 'required|in:en attente,payée,annulée'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $facture = Facture::create([
            'demande_id' => $request->demande_id,
            'montant' => $request->montant,
            'date_emission' => $request->date_emission,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut
        ]);

        return response()->json(['message' => 'Facture créée avec succès', 'facture' => $facture], 201);
    }

    /**
     * Mise à jour d'une facture
     */
    public function updateFacture(Request $request, $id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture non trouvée'], 404);
        }

        // Vérifier si l'utilisateur est un administrateur ou service d'achat
        if (auth()->user()->role_type !== 'admin' && auth()->user()->role_type !== 'service_achat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'montant' => 'nullable|numeric|min:1',
            'date_paiement' => 'nullable|date|after_or_equal:date_emission',
            'statut' => 'nullable|in:en attente,payée,annulée'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $facture->update($request->only(['montant', 'date_paiement', 'statut']));
        return response()->json(['message' => 'Facture mise à jour avec succès']);
    }

    /**
     * Suppression d'une facture
     */
    public function deleteFacture($id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture non trouvée'], 404);
        }

        // Vérifier si l'utilisateur est un administrateur
        if (auth()->user()->role_type !== 'admin') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $facture->delete();
        return response()->json(['message' => 'Facture supprimée avec succès']);
    }

    /**
     * Affichage des détails d'une facture
     */
    public function showFacture($id)
    {
        $facture = Facture::find($id);
        if (!$facture) {
            return response()->json(['message' => 'Facture non trouvée'], 404);
        }

        return response()->json($facture);
    }
}

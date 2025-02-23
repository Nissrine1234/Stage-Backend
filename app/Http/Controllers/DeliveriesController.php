<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livraison;
use Illuminate\Support\Facades\Validator;

class DeliveriesController extends Controller
{
    /**
     * Liste des livraisons
     */
    public function listDeliveries()
    {
        $livraisons = Livraison::all();
        return response()->json($livraisons);
    }

    /**
     * Création d'une livraison
     */
    public function createDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offre_id' => 'required|exists:offres,id',
            'statut' => 'required|in:en attente,en cours,livrée,annulée'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $livraison = Livraison::create([
            'offre_id' => $request->offre_id,
            'statut' => $request->statut
        ]);

        return response()->json(['message' => 'Livraison créée avec succès', 'livraison' => $livraison], 201);
    }

    /**
     * Mise à jour d'une livraison
     */
    public function updateDelivery(Request $request, $id)
    {
        $livraison = Livraison::find($id);
        if (!$livraison) {
            return response()->json(['message' => 'Livraison non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en attente,en cours,livrée,annulée'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $livraison->update($request->only(['statut']));
        return response()->json(['message' => 'Livraison mise à jour avec succès']);
    }

    /**
     * Suppression d'une livraison
     */
    public function deleteDelivery($id)
    {
        $livraison = Livraison::find($id);
        if (!$livraison) {
            return response()->json(['message' => 'Livraison non trouvée'], 404);
        }

        $livraison->delete();
        return response()->json(['message' => 'Livraison supprimée avec succès']);
    }

    /**
     * Affichage des détails d'une livraison
     */
    public function showDelivery($id)
    {
        $livraison = Livraison::find($id);
        if (!$livraison) {
            return response()->json(['message' => 'Livraison non trouvée'], 404);
        }

        return response()->json($livraison);
    }
}

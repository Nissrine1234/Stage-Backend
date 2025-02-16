<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallsController extends Controller
{
    public function listCalls()
    {
        $calls = AppelOffre::all();
        return view('calls.list', compact('calls'));
    }

    public function showCall($id)
    {
        $call = AppelOffre::find($id);
        if (!$call) {
            abort(404);
        }
        return view('calls.show', compact('call'));
    }
    public function createCall(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'details' => 'required',
            'dateLimite' => 'required|date',
        ]);

        AppelOffre::create($request->all());
        return redirect()->route('calls.list')->with('success', 'Appel d\'offre créé avec succès');
    }
    public function updateCall(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required',
            'details' => 'required',
            'dateLimite' => 'required|date',
        ]);

        $call = AppelOffre::find($id);
        $call->update($request->all());
        return redirect()->route('calls.list')->with('success', 'Appel d\'offre mis à jour avec succès');
    }
    public function deleteCall($id)
    {
        AppelOffre::destroy($id);
        return redirect()->route('calls.list')->with('success', 'Appel d\'offre supprimé avec succès');
    }


}

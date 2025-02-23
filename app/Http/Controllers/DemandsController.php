<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;

class DemandsController extends Controller
{
    public function index()
    {
        return response()->json(Demande::all(), 200);
    }

    public function store(Request $request)
    {
        $demande = Demande::create($request->all());
        return response()->json($demande, 201);
    }

    public function show($id)
    {
        return response()->json(Demande::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);
        $demande->update($request->all());
        return response()->json($demande, 200);
    }

    public function destroy($id)
    {
        Demande::destroy($id);
        return response()->json(null, 204);
    }
}

?>
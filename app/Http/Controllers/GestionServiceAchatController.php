<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceAchat;

class GestionServiceAchatController extends Controller
{
    public function index()
    {
        return response()->json(ServiceAchat::all(), 200);
    }

    public function store(Request $request)
    {
        $service = ServiceAchat::create($request->all());
        return response()->json($service, 201);
    }

    public function show($id)
    {
        return response()->json(ServiceAchat::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $service = ServiceAchat::findOrFail($id);
        $service->update($request->all());
        return response()->json($service, 200);
    }

    public function destroy($id)
    {
        ServiceAchat::destroy($id);
        return response()->json(null, 204);
    }
}
?>
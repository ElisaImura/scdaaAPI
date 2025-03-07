<?php

namespace App\Http\Controllers;

use App\Models\Tipos_Cultivo;
use Illuminate\Http\Request;

class TiposCultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tipos_Cultivo::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'tpCul_nombre' => 'required|string|max:191',
        ]);

        $tipoCultivo = Tipos_Cultivo::create($fields);

        return response()->json($tipoCultivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoCultivo = Tipos_Cultivo::find($id);

        if (!$tipoCultivo) {
            return response()->json(['message' => 'Tipo de cultivo no encontrado'], 404);
        }

        return response()->json($tipoCultivo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipoCultivo = Tipos_Cultivo::find($id);

        if (!$tipoCultivo) {
            return response()->json(['message' => 'Tipo de cultivo no encontrado'], 404);
        }

        $fields = $request->validate([
            'tpCul_nombre' => 'sometimes|required|string|max:191',
        ]);

        $tipoCultivo->update($fields);

        return response()->json($tipoCultivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipoCultivo = Tipos_Cultivo::find($id);

        if (!$tipoCultivo) {
            return response()->json(['message' => 'Tipo de cultivo no encontrado'], 404);
        }

        $tipoCultivo->delete();

        return response()->json(['message' => 'Tipo de cultivo eliminado correctamente']);
    }
}
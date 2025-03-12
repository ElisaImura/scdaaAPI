<?php

namespace App\Http\Controllers;

use App\Models\Tipos_Variedad;
use Illuminate\Http\Request;

class TiposVariedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tipos_Variedad::with('tipoCultivo')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'tpCul_id' => 'required|exists:tipos_cultivo,tpCul_id',
            'tpVar_nombre' => 'required|string|max:191',
        ]);

        $tipoVariedad = Tipos_Variedad::create($fields);

        return response()->json($tipoVariedad, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoVariedad = Tipos_Variedad::with('tipoCultivo')->find($id);

        if (!$tipoVariedad) {
            return response()->json(['message' => 'Tipo de variedad no encontrado'], 404);
        }

        return response()->json($tipoVariedad);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipoVariedad = Tipos_Variedad::find($id);

        if (!$tipoVariedad) {
            return response()->json(['message' => 'Tipo de variedad no encontrado'], 404);
        }

        $fields = $request->validate([
            'tpCul_id' => 'sometimes|required|exists:tipos_cultivo,tpCul_id',
            'tpVar_nombre' => 'sometimes|required|string|max:191',
        ]);

        $tipoVariedad->update($fields);

        return response()->json($tipoVariedad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipoVariedad = Tipos_Variedad::find($id);

        if (!$tipoVariedad) {
            return response()->json(['message' => 'Tipo de variedad no encontrado'], 404);
        }

        $tipoVariedad->delete();

        return response()->json(['message' => 'Tipo de variedad eliminado correctamente']);
    }

    public function getVariedadesPorCultivo($tpCul_id)
    {
        return Tipos_Variedad::where('tpCul_id', $tpCul_id)->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Tipos_Actividades;
use Illuminate\Http\Request;

class TiposActividadesController extends Controller
{
    /**
     * Muestra todos los tipos de actividades.
     */
    public function index()
    {
        return response()->json(Tipos_Actividades::all());
    }

    /**
     * Guarda un nuevo tipo de actividad.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'tpAct_nombre' => 'required|string|max:50',
            'tpAct_desc' => 'nullable|string|max:255',
        ]);

        $tipoActividad = Tipos_Actividades::create($fields);
        return response()->json($tipoActividad, 201);
    }

    /**
     * Muestra un tipo de actividad específico.
     */
    public function show($id)
    {
        $tipoActividad = Tipos_Actividades::find($id);

        if (!$tipoActividad) {
            return response()->json(['message' => 'Tipo de actividad no encontrado'], 404);
        }

        return response()->json($tipoActividad);
    }

    /**
     * Actualiza un tipo de actividad.
     */
    public function update(Request $request, $id)
    {
        $tipoActividad = Tipos_Actividades::find($id);

        if (!$tipoActividad) {
            return response()->json(['message' => 'Tipo de actividad no encontrado'], 404);
        }

        $fields = $request->validate([
            'tpAct_nombre' => 'sometimes|required|string|max:50',
            'tpAct_desc' => 'sometimes|nullable|string|max:255',
        ]);

        $tipoActividad->update($fields);
        return response()->json($tipoActividad);
    }

    /**
     * Elimina un tipo de actividad.
     */
    public function destroy($id)
    {
        $tipoActividad = Tipos_Actividades::find($id);

        if (!$tipoActividad) {
            return response()->json(['message' => 'Tipo de actividad no encontrado'], 404);
        }

        $tipoActividad->delete();
        return response()->json(['message' => 'Tipo de actividad eliminado correctamente']);
    }
}

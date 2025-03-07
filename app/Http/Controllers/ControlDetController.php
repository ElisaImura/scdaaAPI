<?php

namespace App\Http\Controllers;

use App\Models\Control_Det;
use Illuminate\Http\Request;

class ControlDetController extends Controller
{
    /**
     * Mostrar todos los registros.
     */
    public function index()
    {
        $control_Det = Control_Det::with('actividad')->get();
        return response()->json($control_Det);
    }

    /**
     * Almacenar un nuevo registro.
     */
    public function store(Request $request)
    {
        $request->validate([
            'act_id' => 'required|exists:actividades,act_id',
            'con_cant' => 'required|integer|min:1',
            'con_vigor' => 'required|integer|min:1|max:5', // Calificaciones de 1 a 5
        ]);

        $control_Det = Control_Det::create($request->all());

        return response()->json([
            'message' => 'Registro creado con éxito',
            'data' => $control_Det
        ], 201);
    }

    /**
     * Mostrar un registro específico.
     */
    public function show($id)
    {
        $control_Det = Control_Det::with('actividad')->find($id);

        if (!$control_Det) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        return response()->json($control_Det);
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $id)
    {
        $control_Det = Control_Det::find($id);

        if (!$control_Det) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $request->validate([
            'act_id' => 'sometimes|exists:actividades,act_id',
            'con_cant' => 'sometimes|integer|min:1',
            'con_vigor' => 'sometimes|integer|min:1|max:5', // Manteniendo la restricción
        ]);

        $control_Det->update($request->all());

        return response()->json([
            'message' => 'Registro actualizado con éxito',
            'data' => $control_Det
        ]);
    }

    /**
     * Eliminar un registro.
     */
    public function destroy($id)
    {
        $control_Det = Control_Det::find($id);

        if (!$control_Det) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $control_Det->delete();

        return response()->json(['message' => 'Registro eliminado con éxito']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Act_Ciclo;
use App\Models\Act_Ciclo_Insumo;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    /**
     * Mostrar todas las actividades.
     */
    public function index()
    {
        return response()->json(Actividades::all());
    }

    /**
     * Almacenar una nueva actividad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tpAct_id' => 'required|exists:tipos_actividades,tpAct_id',
            'act_fecha' => 'required|string|max:191',
            'act_desc' => 'required|string|max:191',
            'act_estado' => 'required|integer|in:1,2,3',
            'act_foto' => 'nullable|string|max:191',
            'uss_id' => 'required|exists:users,uss_id',
            'insumos' => 'array',
            'insumos.*' => 'exists:insumos,ins_id',
        ]);

        $actividad = Actividades::create($request->only(['tpAct_id', 'act_fecha', 'act_desc', 'act_estado', 'act_foto']));

        $actCiclo = Act_Ciclo::create([
            'act_id' => $actividad->act_id,
            'uss_id' => $request->uss_id,
        ]);

        foreach ($request->insumos as $insumoId) {
            Act_Ciclo_Insumo::create([
                'ci_id' => $actCiclo->ci_id,
                'ins_id' => $insumoId,
            ]);
        }

        return response()->json($actividad, 201);
    }

    /**
     * Mostrar una actividad específica.
     */
    public function show($id)
    {
        $actividad = Actividades::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        return response()->json($actividad);
    }

    /**
     * Actualizar una actividad.
     */
    public function update(Request $request, $id)
    {
        $actividad = Actividades::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $request->validate([
            'tpAct_id' => 'sometimes|exists:tipos_actividades,tpAct_id',
            'act_fecha' => 'sometimes|string|max:191',
            'act_desc' => 'sometimes|string|max:191',
            'act_estado' => 'sometimes|integer|in:1,2,3', // Solo acepta 1, 2 o 3
            'act_foto' => 'nullable|string|max:191',
        ]);

        $actividad->update($request->all());

        return response()->json($actividad);
    }

    /**
     * Eliminar una actividad.
     */
    public function destroy($id)
    {
        $actividad = Actividades::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        $actividad->delete();

        return response()->json(['message' => 'Actividad eliminada con éxito']);
    }
}

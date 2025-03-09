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
        $actividades = Actividades::with([
            'tipoActividad',
            'ciclo.lote',
            'ciclo.insumos' => function ($query) {
                $query->select('insumos.*', 'act_ciclo_insumo.ins_cant')
                      ->join('act_ciclo_insumo as aci', 'insumos.ins_id', '=', 'aci.ins_id'); // ✅ Agregamos alias 'aci'
            }
        ])->get();
    
        return response()->json($actividades);
    }      

    /**
     * Almacenar una nueva actividad.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tpAct_id' => 'required|exists:tipos_actividades,tpAct_id',
            'ci_id' => 'required|exists:ciclos,ci_id', // Asegurar que el ciclo existe
            'act_fecha' => 'required|string|max:191',
            'act_desc' => 'required|string|max:191',
            'act_estado' => 'required|integer|in:1,2,3',
            'act_foto' => 'nullable|string|max:191',
            'uss_id' => 'required|exists:users,uss_id',
            'insumos' => 'array',
            'insumos.*.ins_id' => 'exists:insumos,ins_id',
            'insumos.*.ins_cant' => 'required|numeric|min:0',
        ]);

        // 📌 Crear la actividad
        $actividad = Actividades::create([
            'tpAct_id' => $validatedData['tpAct_id'],
            'act_fecha' => $validatedData['act_fecha'],
            'act_desc' => $validatedData['act_desc'],
            'act_estado' => $validatedData['act_estado'],
            'act_foto' => $validatedData['act_foto'] ?? null,
        ]);

        // 📌 Crear la relación en act_ciclo
        $actCiclo = Act_Ciclo::create([
            'act_id' => $actividad->act_id,
            'ci_id' => $validatedData['ci_id'],
            'uss_id' => $validatedData['uss_id'],
        ]);

        // 📌 Relacionar insumos con sus cantidades
        if (!empty($validatedData['insumos'])) {
            foreach ($validatedData['insumos'] as $insumo) {
                Act_Ciclo_Insumo::create([
                    'act_ci_id' => $actCiclo->act_ci_id, // 💡 Asegurando que sea el ID correcto
                    'ins_id' => $insumo['ins_id'],
                    'ins_cant' => $insumo['ins_cant'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Actividad creada con éxito',
            'actividad' => $actividad->load('ciclo', 'ciclo.insumos'),
        ], 201);
    }

    /**
     * Mostrar una actividad específica.
     */
    public function show($id)
    {
        $actividad = Actividades::with([
            'tipoActividad',
            'ciclo',
            'ciclo.insumos',
            'ciclo.lote'
        ])->find($id);

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
            'ci_id' => 'sometimes|exists:ciclos,ci_id', // Validar ciclo en actualización
            'act_fecha' => 'sometimes|string|max:191',
            'act_desc' => 'sometimes|string|max:191',
            'act_estado' => 'sometimes|integer|in:1,2,3',
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

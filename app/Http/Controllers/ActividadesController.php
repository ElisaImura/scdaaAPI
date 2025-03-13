<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Ciclos;
use App\Models\Act_Ciclo;
use App\Models\Act_Ciclo_Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadesController extends Controller
{
    /**
     * Mostrar todas las actividades.
     */
    public function index()
    {
        $actividades = Actividades::with([
            'tipoActividad',  // Cargar el tipo de actividad
            'ciclo.lote',  // Cargar la relación con el lote del ciclo
            'ciclo.insumos' => function ($query) {
                $query->select('insumos.*', 'act_ciclo_insumo.ins_cant')
                      ->join('act_ciclo_insumo as aci', 'insumos.ins_id', '=', 'aci.ins_id')
                      ->distinct();  // Usamos DISTINCT para evitar duplicados
            },
            'ciclo.actCiclos' => function ($query) {
                $query->select('uss_id', 'uss_nombre');
            },
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
            'ci_id' => 'required|exists:ciclos,ci_id', // Ciclo relacionado
            'act_fecha' => 'required|string|max:191',
            'act_desc' => 'nullable|string|max:191',
            'act_estado' => 'required|integer|in:1,2,3',
            'act_foto' => 'nullable|string|max:191',
            'uss_id' => 'required|exists:users,uss_id',
            'insumos' => 'array',
            'insumos.*.ins_id' => 'exists:insumos,ins_id',
            'insumos.*.ins_cant' => 'required|numeric|min:0',
            // Datos de cosecha (opcionales)
            'cos_rendi' => 'nullable|numeric|min:0',
            'cos_hume' => 'nullable|numeric|min:0',
            // Datos de siembra (opcionales)
            'sie_densidad' => 'nullable|numeric|min:0',
            // Datos de control de germinación (opcionales)
            'con_cant' => 'nullable|integer|min:0',
            'con_vigor' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // 📌 Crear la actividad
            $actividad = Actividades::create([
                'tpAct_id' => $validatedData['tpAct_id'],
                'act_fecha' => $validatedData['act_fecha'],
                'act_desc' => $validatedData['act_desc'] ?? null,
                'act_estado' => $validatedData['act_estado'],
                'act_foto' => $validatedData['act_foto'] ?? null,
            ]);

            // 📌 Relacionar la actividad con un ciclo
            $actCiclo = Act_Ciclo::create([
                'act_id' => $actividad->act_id,
                'ci_id' => $validatedData['ci_id'],
                'uss_id' => $validatedData['uss_id'],
            ]);

            // 📌 Actualizar el ciclo si es una actividad de cosecha o siembra
            $ciclo = Ciclos::find($validatedData['ci_id']);
            if ($ciclo) {
                $ciclo->update([
                    'cos_rendi' => $validatedData['cos_rendi'] ?? $ciclo->cos_rendi,
                    'cos_hume' => $validatedData['cos_hume'] ?? $ciclo->cos_hume,
                    'sie_densidad' => $validatedData['sie_densidad'] ?? $ciclo->sie_densidad,
                ]);

                // 📌 Si los datos de cosecha están presentes, actualizar `ci_fechafin`
                if (isset($validatedData['cos_rendi']) || isset($validatedData['cos_hume'])) {
                    $ciclo->update([
                        'ci_fechafin' => $validatedData['act_fecha'],
                    ]);
                }
            }

            // 📌 Guardar datos de Control de Germinación si están presentes
            if (!empty($validatedData['con_cant']) && !empty($validatedData['con_vigor'])) {
                ControlDet::create([
                    'act_id' => $actividad->act_id,
                    'con_cant' => $validatedData['con_cant'],
                    'con_vigor' => $validatedData['con_vigor'],
                ]);
            }

            // 📌 Relacionar insumos con sus cantidades
            if (!empty($validatedData['insumos'])) {
                foreach ($validatedData['insumos'] as $insumo) {
                    Act_Ciclo_Insumo::create([
                        'act_ci_id' => $actCiclo->act_ci_id,
                        'ins_id' => $insumo['ins_id'],
                        'ins_cant' => $insumo['ins_cant'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Actividad creada con éxito',
                'actividad' => $actividad->load('ciclo', 'ciclo.insumos'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar la actividad', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar una actividad específica.
     */
    public function show($id)
    {
        $actividad = Actividades::with([
            'tipoActividad',
            'ciclo',
            'ciclo.insumos' => function ($query) {
                $query->select('insumos.*', 'act_ciclo_insumo.ins_cant')
                      ->join('act_ciclo_insumo as aci', 'insumos.ins_id', '=', 'aci.ins_id'); // ✅ Agregamos alias 'aci'
            },
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

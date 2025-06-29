<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Ciclos;
use App\Models\Control_Det;
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
            'tipoActividad',
            'ciclo.datosCiclo' => function ($query) {
                $query->select('ciclos.*', 'cos_rendi', 'cos_hume', 'sie_densidad');
            },
            'ciclo.lote',
            'ciclo.insumos' => function ($query) {
                $query->select('insumos.*', 'act_ciclo_insumo.ins_cant')
                      ->join('act_ciclo_insumo as aci', 'insumos.ins_id', '=', 'aci.ins_id')
                      ->distinct();  // Usamos DISTINCT para evitar duplicados
            },
            'ciclo.actCiclos' => function ($query) {
                $query->select('uss_id', 'uss_nombre');
            },
            'controlGerminacion' => function ($query) { 
                $query->select('act_id', 'con_cant', 'con_vigor');
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
            'ci_id' => 'required|exists:ciclos,ci_id',
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
                Control_Det::create([
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
            'ciclo.datosCiclo' => function ($query) {
                $query->select('ciclos.*', 'cos_rendi', 'cos_hume', 'sie_densidad');
            },
            'ciclo.lote',
            'ciclo.insumos' => function ($query) {
                $query->select('insumos.*', 'act_ciclo_insumo.ins_cant')
                      ->join('act_ciclo_insumo as aci', 'insumos.ins_id', '=', 'aci.ins_id')
                      ->distinct();  // Usamos DISTINCT para evitar duplicados
            },
            'ciclo.actCiclos' => function ($query) {
                $query->select('uss_id', 'uss_nombre');
            },
            'controlGerminacion' => function ($query) { 
                $query->select('act_id', 'con_cant', 'con_vigor');
            }
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
        $validatedData = $request->validate([
            'tpAct_id' => 'sometimes|exists:tipos_actividades,tpAct_id',
            'ci_id' => 'sometimes|exists:ciclos,ci_id',
            'act_fecha' => 'sometimes|string|max:191',
            'act_desc' => 'sometimes|nullable|string|max:191',
            'act_estado' => 'sometimes|integer|in:1,2,3',
            'act_foto' => 'nullable|string|max:191',
            'uss_id' => 'sometimes|exists:users,uss_id',
            'insumos' => 'array',
            'insumos.*.ins_id' => 'exists:insumos,ins_id',
            'insumos.*.ins_cant' => 'required|numeric|min:0',
            'cos_rendi' => 'nullable|numeric|min:0',
            'cos_hume' => 'nullable|numeric|min:0',
            'sie_densidad' => 'nullable|numeric|min:0',
            'con_cant' => 'nullable|integer|min:0',
            'con_vigor' => 'nullable|integer|min:0',
        ]);
    
        DB::beginTransaction();
    
        try {
            \Log::info('uss_id recibido:', ['uss_id' => $validatedData['uss_id']]);

            // 🔍 Buscar la actividad
            $actividad = Actividades::where('act_id', $id)->first();
            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }
    
            // 🔍 Guardar el tipo de actividad anterior antes de actualizar
            $tipoAnterior = $actividad->tpAct_id;
    
            // 🔍 Buscar la relación act_ciclo
            $actCiclo = Act_Ciclo::where('act_id', $actividad->act_id)->first();
    
            // ✅ Si no existe, crearlo
            if (!$actCiclo) {
                $actCiclo = Act_Ciclo::create([
                    'act_id' => $actividad->act_id,
                    'ci_id' => $validatedData['ci_id'] ?? $actividad->ci_id,
                    'uss_id' => $validatedData['uss_id'] ?? $actividad->uss_id,
                ]);
            }else {
                // Si ya existe, actualiza el `uss_id` en la relación
                $actCiclo->update([
                    'uss_id' => $validatedData['uss_id'], // Actualiza el `uss_id`
                ]);
            }
    
            if (!$actCiclo || !$actCiclo->act_ci_id) {
                DB::rollBack();
                return response()->json(['error' => 'Error: No se pudo establecer la relación en act_ciclo para esta actividad.'], 500);
            }
    
            // 📌 Actualizar la actividad
            $actividad->update($validatedData);
    
            // 📌 Si cambió el tipo de actividad, limpiar datos anteriores
            if (isset($validatedData['tpAct_id']) && $validatedData['tpAct_id'] != $tipoAnterior) {
                $ciclo = Ciclos::find($actCiclo->ci_id);
                if ($ciclo) {
                    if (in_array($tipoAnterior, [6, 3])) { 
                        $ciclo->update([
                            'cos_rendi' => null,
                            'cos_hume' => null,
                            'sie_densidad' => null,
                            'ci_fechafin' => null,
                        ]);
                    }
                }
    
                if ($tipoAnterior == 4) {
                    Control_Det::where('act_id', $actividad->act_id)->delete();
                }
            }
    
            // 📌 Actualizar los datos en `ciclos` si aplica
            $ciclo = Ciclos::find($actCiclo->ci_id);

            \Log::info('CI_ID encontrado en act_ciclo:', ['ci_id' => $actCiclo->ci_id]);

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
    
            // 📌 Actualizar o crear control de germinación si aplica
            if (!empty($validatedData['con_cant']) && !empty($validatedData['con_vigor'])) {
                Control_Det::updateOrCreate(
                    ['act_id' => $actividad->act_id],
                    [
                        'con_cant' => $validatedData['con_cant'],
                        'con_vigor' => $validatedData['con_vigor'],
                    ]
                );
            }
    
            // 📌 Eliminar insumos anteriores antes de insertar nuevos
            Act_Ciclo_Insumo::where('act_ci_id', $actCiclo->act_ci_id)->delete();
    
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
                'message' => 'Actividad actualizada con éxito',
                'actividad' => $actividad->load('ciclo', 'ciclo.insumos'),
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar la actividad', 'message' => $e->getMessage()], 500);
        }
    }      

    /**
     * Eliminar una actividad.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $actividad = Actividades::find($id);

            if (!$actividad) {
                return response()->json(['message' => 'Actividad no encontrada'], 404);
            }

            $tipoActividad = $actividad->tpAct_id;

            $actCiclo = Act_Ciclo::where('act_id', $actividad->act_id)->first();

            if ($actCiclo) {
                $ci_id = $actCiclo->ci_id;

                // Eliminar relación con insumos
                Act_Ciclo_Insumo::where('act_ci_id', $actCiclo->act_ci_id)->delete();

                // Eliminar relación act_ciclo
                $actCiclo->delete();

                // Si es control de germinación, eliminar datos específicos
                if ($tipoActividad == 4) {
                    Control_Det::where('act_id', $actividad->act_id)->delete();
                }

                // Verificar si quedan otras actividades de siembra o cosecha en ese ciclo
                $actividadesRestantes = Actividades::whereHas('ciclo', function ($query) use ($ci_id) {
                    $query->where('ci_id', $ci_id);
                })->where('act_id', '!=', $actividad->act_id)->get();

                $haySiembra = $actividadesRestantes->contains('tpAct_id', 3);
                $hayCosecha = $actividadesRestantes->contains('tpAct_id', 6);

                $ciclo = Ciclos::find($ci_id);
                if ($ciclo) {
                    $actualizaciones = [];

                    if ($tipoActividad == 3 && !$haySiembra) {
                        $actualizaciones['sie_densidad'] = null;
                    }

                    if ($tipoActividad == 6 && !$hayCosecha) {
                        $actualizaciones['cos_rendi'] = null;
                        $actualizaciones['cos_hume'] = null;
                        $actualizaciones['ci_fechafin'] = null;
                    }

                    if (!empty($actualizaciones)) {
                        $ciclo->update($actualizaciones);
                    }
                }
            }

            $actividad->delete();

            DB::commit();

            return response()->json(['message' => 'Actividad eliminada con éxito']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar la actividad', 'message' => $e->getMessage()], 500);
        }
    }
}

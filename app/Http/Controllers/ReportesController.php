<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function produccionAgricola()
    {
        // 1. Producción por cultivo y por lote (solo ciclos finalizados)
        $rendimiento = DB::table('ciclos')
            ->select(
                'tipos_cultivo.tpCul_nombre as cultivo',
                'lotes.lot_nombre as lote',
                DB::raw('SUM(ciclos.cos_rendi) as total_cosechado')
            )
            ->join('tipos_variedad', 'ciclos.tpVar_id', '=', 'tipos_variedad.tpVar_id')
            ->join('tipos_cultivo', 'tipos_variedad.tpCul_id', '=', 'tipos_cultivo.tpCul_id')
            ->join('lotes', 'ciclos.lot_id', '=', 'lotes.lot_id')
            ->whereNotNull('ciclos.ci_fechafin')
            ->groupBy('tipos_cultivo.tpCul_nombre', 'lotes.lot_nombre')
            ->get();
    
        // 2. Promedio de producción por cultivo y variedad (solo ciclos finalizados)
        $promedio = DB::table('ciclos')
            ->select(
                'tipos_cultivo.tpCul_nombre as cultivo',
                'tipos_variedad.tpVar_nombre as variedad',
                DB::raw('AVG(ciclos.cos_rendi) as promedio_cosecha')
            )
            ->join('tipos_variedad', 'ciclos.tpVar_id', '=', 'tipos_variedad.tpVar_id')
            ->join('tipos_cultivo', 'tipos_variedad.tpCul_id', '=', 'tipos_cultivo.tpCul_id')
            ->whereNotNull('ciclos.ci_fechafin')
            ->groupBy('tipos_cultivo.tpCul_nombre', 'tipos_variedad.tpVar_nombre')
            ->get();
    
        // 3. Comparativa entre ciclos (solo ciclos finalizados)
        $porCiclo = DB::table('ciclos')
            ->select(
                'ciclos.ci_nombre as ciclo',
                'tipos_cultivo.tpCul_nombre as cultivo',
                DB::raw('SUM(ciclos.cos_rendi) as total_cosechado')
            )
            ->join('tipos_variedad', 'ciclos.tpVar_id', '=', 'tipos_variedad.tpVar_id')
            ->join('tipos_cultivo', 'tipos_variedad.tpCul_id', '=', 'tipos_cultivo.tpCul_id')
            ->whereNotNull('ciclos.ci_fechafin')
            ->groupBy('ciclos.ci_nombre', 'tipos_cultivo.tpCul_nombre')
            ->get();
    
        // 4. Cantidad de insumos utilizados (sin filtro, ya que se refiere al total acumulado)
        $insumos = DB::table('act_ciclo_insumo')
            ->select(
                'insumos.ins_desc as insumo',
                DB::raw('SUM(act_ciclo_insumo.ins_cant) as total_utilizado'),
                'insumos.ins_unidad_medida as unidad'
            )
            ->join('insumos', 'act_ciclo_insumo.ins_id', '=', 'insumos.ins_id')
            ->groupBy('insumos.ins_desc', 'insumos.ins_unidad_medida')
            ->orderByDesc('total_utilizado')
            ->get();
    
        // 5. Días con lluvia por ciclo considerando lote
        $lluviaPorCiclo = DB::table('ciclos')
            ->select(
                'ciclos.ci_nombre as ciclo',
                'lotes.lot_nombre as lote',
                DB::raw('COUNT(climas.cl_fecha) as dias_lluvia')
            )
            ->join('climas', function ($join) {
                $join->on('climas.cl_fecha', '>=', 'ciclos.ci_fechaini')
                    ->on('climas.cl_fecha', '<=', DB::raw('COALESCE(ciclos.ci_fechafin, CURRENT_DATE)'))
                    ->on('climas.lot_id', '=', 'ciclos.lot_id');
            })
            ->join('lotes', 'ciclos.lot_id', '=', 'lotes.lot_id')
            ->whereNotNull('ciclos.ci_fechafin')
            ->whereNotNull('climas.cl_lluvia')
            ->groupBy('ciclos.ci_nombre', 'lotes.lot_nombre')
            ->get();
    
        // 6. Relación clima-producción (solo ciclos finalizados)
        $climaYProduccion = DB::table('ciclos')
            ->select(
                'ciclos.ci_nombre as ciclo',
                'ciclos.cos_rendi as produccion_total',
                DB::raw('(
                    SELECT COUNT(*) 
                    FROM climas 
                    WHERE climas.cl_fecha BETWEEN ciclos.ci_fechaini 
                        AND COALESCE(ciclos.ci_fechafin, CURRENT_DATE)
                    AND climas.cl_lluvia IS NOT NULL
                ) as dias_lluvia')
            )
            ->whereNotNull('ciclos.ci_fechafin')
            ->get();
    
        return response()->json([
            'rendimiento_por_lote' => $rendimiento,
            'promedio_por_variedad' => $promedio,
            'comparativa_por_ciclo' => $porCiclo,
            'insumos_mas_utilizados' => $insumos,
            'dias_lluvia_por_ciclo' => $lluviaPorCiclo,
            'relacion_clima_productividad' => $climaYProduccion,
        ]);
    }    

    public function lluviaPorFechas(Request $request)
    {
        $inicio = $request->query('inicio');
        $fin = $request->query('fin');
        $loteId = $request->query('loteId');
    
        if (!$inicio || !$fin) {
            return response()->json([
                'error' => 'Parámetros "inicio" y "fin" son requeridos.'
            ], 400);
        }
    
        $query = DB::table('climas')
            ->select('cl_fecha as fecha', 'cl_lluvia as total_lluvia', 'lot_id')
            ->whereBetween('cl_fecha', [$inicio, $fin])
            ->whereNotNull('cl_lluvia');
    
        if ($loteId) {
            $query->where('lot_id', $loteId);
        }
    
        $lluviaPorDia = $query->orderBy('cl_fecha')->get();
    
        return response()->json($lluviaPorDia);
    }    
}

<?php

namespace App\Http\Controllers;

use App\Models\Clima;
use Illuminate\Http\Request;

class ClimaController extends Controller
{
    /**
     * Muestra una lista de registros de clima.
     * Se puede filtrar por lote con ?lot_id=1
     */
    public function index(Request $request)
    {
        if ($request->has('lot_id')) {
            $climas = Clima::with('lote')->where('lot_id', $request->lot_id)->get();
        } else {
            $climas = Clima::with('lote')->get();
        }
    
        return response()->json($climas);
    }    

    /**
     * Almacena un nuevo registro de clima.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cl_fecha' => 'required|date',
            'cl_viento' => 'nullable|numeric',
            'cl_temp' => 'nullable|numeric',
            'cl_hume' => 'nullable|numeric',
            'cl_lluvia' => 'nullable|numeric',
            'lot_id' => 'required|exists:lotes,lot_id',
        ]);

        $clima = Clima::create($request->all());

        return response()->json($clima, 201);
    }

    /**
     * Muestra un registro específico de clima.
     */
    public function show($id)
    {
        $clima = Clima::find($id);

        if (!$clima) {
            return response()->json(['message' => 'Datos no encontrados'], 404);
        }

        return response()->json($clima);
    }

    /**
     * Actualiza un registro de clima existente.
     */
    public function update(Request $request, Clima $clima)
    {
        $request->validate([
            'cl_fecha' => 'sometimes|date',
            'cl_viento' => 'sometimes|numeric',
            'cl_temp' => 'sometimes|numeric',
            'cl_hume' => 'sometimes|numeric',
            'cl_lluvia' => 'sometimes|numeric',
            'lot_id' => 'sometimes|exists:lotes,lot_id',
        ]);

        $clima->update($request->all());

        return response()->json($clima);
    }

    /**
     * Elimina un registro de clima.
     */
    public function destroy($id)
    {
        $clima = Clima::find($id);

        if (!$clima) {
            return response()->json(['message' => 'Datos no encontrados'], 404);
        }

        $clima->delete();

        return response()->json(['message' => 'Dato eliminado correctamente']);
    }
}

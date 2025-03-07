<?php

namespace App\Http\Controllers;

use App\Models\Ciclos;
use Illuminate\Http\Request;

class CiclosController extends Controller
{
    /**
     * Muestra una lista de ciclos.
     */
    public function index()
    {
        $ciclos = Ciclos::all();
        return response()->json($ciclos);
    }

    /**
     * Almacena un nuevo ciclo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tpVar_id' => 'required|exists:tipos_variedad,tpVar_id',
            'uss_id' => 'required|exists:users,uss_id',
            'lot_id' => 'required|exists:lotes,lot_id',
            'ci_fechaini' => 'required|date',
            'ci_fechafin' => 'nullable|date',
            'cos_fecha' => 'nullable|date',
            'cos_rendi' => 'nullable|numeric',
            'cos_hume' => 'nullable|numeric',
            'sie_fecha' => 'nullable|date',
            'sie_densidad' => 'nullable|integer',
        ]);

        $ciclo = Ciclos::create($request->all());

        return response()->json($ciclo, 201);
    }

    /**
     * Muestra un ciclo específico.
     */
    public function show($id)
    {
        $ciclo = Ciclos::find($id);

        if (!$ciclo) {
            return response()->json(['message' => 'Ciclo no encontrado'], 404);
        }

        return response()->json($ciclo);
    }

    /**
     * Actualiza un ciclo existente.
     */
    public function update(Request $request, $id)
    {
        $ciclo = Ciclos::find($id);

        if (!$ciclo) {
            return response()->json(['message' => 'Ciclo no encontrado'], 404);
        }

        $request->validate([
            'tpVar_id' => 'sometimes|exists:tipos_variedad,tpVar_id',
            'uss_id' => 'sometimes|exists:users,uss_id',
            'lot_id' => 'sometimes|exists:lotes,lot_id',
            'ci_fechaini' => 'sometimes|date',
            'ci_fechafin' => 'nullable|date',
            'cos_fecha' => 'nullable|date',
            'cos_rendi' => 'nullable|numeric',
            'cos_hume' => 'nullable|numeric',
            'sie_fecha' => 'nullable|date',
            'sie_densidad' => 'nullable|integer',
        ]);

        $ciclo->update($request->all());

        return response()->json($ciclo);
    }

    /**
     * Elimina un ciclo.
     */
    public function destroy($id)
    {
        $ciclo = Ciclos::find($id);

        if (!$ciclo) {
            return response()->json(['message' => 'Ciclo no encontrado'], 404);
        }

        $ciclo->delete();
        return response()->json(['message' => 'Ciclo eliminado con éxito']);
    }
}

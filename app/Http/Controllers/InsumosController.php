<?php

namespace App\Http\Controllers;

use App\Models\Insumos;
use Illuminate\Http\Request;

class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Insumos::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'ins_desc' => 'required|string|max:191',
            'ins_unidad_medida' => 'required|string|max:50'
        ]);

        $insumo = Insumos::create($fields);

        return response()->json($insumo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $insumo = Insumos::find($id);

        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        return response()->json($insumo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $insumo = Insumos::find($id);

        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        $fields = $request->validate([
            'ins_desc' => 'sometimes|required|string|max:191',
            'ins_unidad_medida' => 'sometimes|required|string|max:50'
        ]);

        $insumo->update($fields);

        return response()->json($insumo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $insumo = Insumos::find($id);

        if (!$insumo) {
            return response()->json(['message' => 'Insumo no encontrado'], 404);
        }

        $insumo->delete();

        return response()->json(['message' => 'Insumo eliminado correctamente']);
    }
}

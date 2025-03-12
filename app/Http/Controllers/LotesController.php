<?php

namespace App\Http\Controllers;

use App\Models\Lotes;
use Illuminate\Http\Request;

class LotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lotes::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'lot_nombre' => 'required|max:255',
            'lot_ubi' => 'nullable|max:255'
        ]);

        $lotes = Lotes::create($fields);

        return $lotes;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lote = Lotes::where('lot_id', $id)->first();
    
        if (!$lote) {
            return response()->json(['message' => 'Lote no encontrado'], 404);
        }
    
        return response()->json($lote);
    }     

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lotes $lote)
    {
        $fields = $request->validate([
            'lot_nombre' => 'required|max:255',
            'lot_ubi' => 'required|max:255'
        ]);

        $lote->update($fields);

        return $lote;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lotes $lote)
    {
        $lote->delete();

        return "Deleted";
    }
}

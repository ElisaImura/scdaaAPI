<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act_Ciclo extends Model
{
    use HasFactory;

    protected $table = 'act_ciclo';
    protected $primaryKey = 'ci_id';
    protected $fillable = ['act_id', 'uss_id'];

    public function actividad()
    {
        return $this->belongsTo(Actividades::class, 'act_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uss_id');
    }

    public function insumos()
    {
        return $this->hasManyThrough(
            Insumos::class,
            Act_Ciclo_Insumo::class,
            'ci_id',  // Foreign key en act_ciclo_insumo
            'ins_id', // Foreign key en insumos
            'ci_id',  // Local key en act_ciclo
            'ins_id'  // Local key en act_ciclo_insumo
        );
    }

    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lot_id', 'lot_id');
    }
}

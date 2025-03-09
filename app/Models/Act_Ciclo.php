<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act_Ciclo extends Model
{
    use HasFactory;

    protected $table = 'act_ciclo';
    protected $primaryKey = 'act_ci_id';
    protected $fillable = ['act_id', 'uss_id', 'ci_id'];

    // Relación con la actividad
    public function actividad()
    {
        return $this->belongsTo(Actividades::class, 'act_id');
    }

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uss_id');
    }

    // Relación con el ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'ci_id', 'ci_id');
    }

    // Relación con insumos a través de act_ciclo_insumo
    public function insumos()
    {
        return $this->hasManyThrough(
            Insumos::class,
            Act_Ciclo_Insumo::class,
            'act_ci_id',  // Foreign key en act_ciclo_insumo
            'ins_id', // Foreign key en insumos
            'act_ci_id',  // Local key en act_ciclo
            'ins_id'  // Local key en act_ciclo_insumo
        );
    }

    // 💡 Nueva relación con el lote a través del ciclo
    public function lote()
    {
        return $this->hasOneThrough(
            Lotes::class,
            Ciclos::class,
            'ci_id',  // Foreign key en ciclos (relación con act_ciclo)
            'lot_id', // Foreign key en lotes
            'ci_id',  // Local key en act_ciclo
            'lot_id'  // Local key en ciclos
        );
    }
}

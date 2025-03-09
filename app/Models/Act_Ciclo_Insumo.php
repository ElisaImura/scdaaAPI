<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act_Ciclo_Insumo extends Model
{
    use HasFactory;

    protected $table = 'act_ciclo_insumo';
    protected $primaryKey = 'act_ci_ins_id';
    protected $fillable = ['act_ci_id', 'ins_id', 'ins_cant'];

    // Relación con Act_Ciclo
    public function actCiclo()
    {
        return $this->belongsTo(Act_Ciclo::class, 'act_ci_id', 'act_ci_id');
    }

    // Relación con Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumos::class, 'ins_id', 'ins_id'); 
    }
}

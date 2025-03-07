<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act_Ciclo_Insumo extends Model
{
    use HasFactory;

    protected $table = 'act_ciclo_insumo';
    protected $primaryKey = 'act_ci_id';
    protected $fillable = ['ci_id', 'ins_id'];

    public function ciclo()
    {
        return $this->belongsTo(ActCiclo::class, 'ci_id');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'ins_id');
    }
}

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
}

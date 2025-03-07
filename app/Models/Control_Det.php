<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control_Det extends Model
{
    use HasFactory;

    protected $table = 'control_det';
    protected $primaryKey = 'con_id';

    protected $fillable = [
        'act_id',
        'con_cant',
        'con_vigor',
    ];

    /**
     * Relación con la tabla actividades.
     */
    public function actividad()
    {
        return $this->belongsTo(Actividades::class, 'act_id', 'act_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos_Actividades extends Model
{
    use HasFactory;

    protected $table = 'tipos_actividades';
    protected $primaryKey = 'tpAct_id';
    public $timestamps = true;

    protected $fillable = [
        'tpAct_nombre',
        'tpAct_desc',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $primaryKey = 'act_id';
    public $timestamps = true;

    protected $fillable = [
        'tpAct_id',
        'act_fecha',
        'act_desc',
        'act_estado',
        'act_foto'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clima extends Model
{
    use HasFactory;

    protected $primaryKey = 'cl_id';
    
    protected $fillable = [
        'cl_fecha',
        'cl_viento',
        'cl_temp',
        'cl_hume',
        'cl_lluvia'
    ];
}

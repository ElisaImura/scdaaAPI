<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos_Cultivo extends Model
{
    use HasFactory;

    protected $table = 'tipos_cultivo';
    protected $primaryKey = 'tpCul_id';
    
    protected $fillable = ['tpCul_nombre'];
    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipos_Variedad extends Model
{
    use HasFactory;

    protected $table = 'tipos_variedad';
    protected $primaryKey = 'tpVar_id';
    public $timestamps = true;

    protected $fillable = [
        'tpCul_id',
        'tpVar_nombre',
    ];

    public function tipoCultivo()
    {
        return $this->belongsTo(Tipos_Cultivo::class, 'tpCul_id', 'tpCul_id');
    }
}

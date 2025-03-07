<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciclos extends Model
{
    use HasFactory;

    protected $table = 'ciclos';
    protected $primaryKey = 'ci_id';
    public $timestamps = true;

    protected $fillable = [
        'tpVar_id',
        'uss_id',
        'lot_id',
        'ci_fechaini',
        'ci_fechafin',
        'cos_fecha',
        'cos_rendi',
        'cos_hume',
        'sie_fecha',
        'sie_densidad',
    ];

    // Relaciones
    public function tipoVariedad()
    {
        return $this->belongsTo(TipoVariedad::class, 'tpVar_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'uss_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lot_id');
    }
}

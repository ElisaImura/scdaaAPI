<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Auth;

class Clima extends Model implements AuditableContract
{
    use HasFactory, AuditableTrait;

    protected $primaryKey = 'cl_id';
    
    protected $fillable = [
        'cl_fecha',
        'cl_viento',
        'cl_temp',
        'cl_hume',
        'cl_lluvia',
        'lot_id'
    ];

    protected $auditInclude = [
        'cl_fecha',
        'cl_viento',
        'cl_temp',
        'cl_hume',
        'cl_lluvia',
        'lot_id'
    ];

    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lot_id', 'lot_id');
    }
}

Audit::creating(function ($audit) {
    $audit->user_type = Auth::user()?->rol?->rol_desc;
    $audit->user_id = Auth::id();
});
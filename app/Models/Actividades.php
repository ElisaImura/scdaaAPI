<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Auth;

class Actividades extends Model implements AuditableContract
{
    use HasFactory, AuditableTrait;

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

    protected $auditInclude = [
        'tpAct_id',
        'act_fecha',
        'act_desc',
        'act_estado',
        'act_foto'
    ];

    public function tipoActividad()
    {
        return $this->belongsTo(Tipos_Actividades::class, 'tpAct_id', 'tpAct_id');
    }

    public function ciclo()
    {
        return $this->hasOne(Act_Ciclo::class, 'act_id', 'act_id');
    }

    public function controlGerminacion()
    {
        return $this->hasOne(Control_Det::class, 'act_id', 'act_id');
    }
}

Audit::creating(function ($audit) {
    $audit->user_type = Auth::user()?->rol?->rol_desc;
    $audit->user_id = Auth::id();
});

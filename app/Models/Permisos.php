<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $primaryKey = 'perm_id';

    protected $fillable = [
        'perm_nombre',
        'perm_desc'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_permiso', 'perm_id', 'uss_id');
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use App\Models\Permisos;
use App\Models\Roles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users'; // Nombre de la tabla
    protected $primaryKey = 'uss_id'; // Clave primaria

    protected $fillable = [
        'rol_id',
        'uss_nombre',
        'email',
        'uss_clave',
    ];

    protected $hidden = [
        'uss_clave',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Relación con la tabla roles.
     */
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'rol_id');
    }

    /**
     * Relación con los permisos a través de la tabla intermedia.
     */
    public function permisos()
    {
        return $this->belongsToMany(Permisos::class, 'usuario_permiso', 'uss_id', 'perm_id');
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->primaryKey}; // devuelve uss_id
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $primaryKey = 'rol_id';

    protected $fillable = [
        'rol_desc',
    ];

    public $timestamps = false;
}

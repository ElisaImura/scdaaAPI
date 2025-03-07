<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $primaryKey = 'lot_id'; 

    protected $fillable = [
        'lot_nombre',
        'lot_ubi',
    ];
}

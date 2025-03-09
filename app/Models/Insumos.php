<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumos extends Model
{
    use HasFactory;

    protected $table = 'insumos'; 
    protected $primaryKey = 'ins_id'; 
    public $timestamps = true;

    protected $fillable = ['ins_desc'];
}

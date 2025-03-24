<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\Permisos;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Roles::all();
        return response()->json($roles);
    }

    public function permisos()
    {
        $permisos = Permisos::all();
        return response()->json($permisos);
    }
}
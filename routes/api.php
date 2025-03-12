<?php

use App\Http\Controllers\LotesController;
use App\Http\Controllers\ClimaController;
use App\Http\Controllers\CiclosController;
use App\Http\Controllers\ControlDetController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\TiposCultivoController;
use App\Http\Controllers\TiposVariedadController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Lotes

Route::apiResource("lotes", LotesController::class);

//Insumos

Route::apiResource("insumos", InsumosController::class);

//Tipos_Actividad

Route::apiResource("tipos/actividades", TiposActividadesController::class);

//Tipos_Cultivo

Route::apiResource("tipos/cultivo", TiposCultivoController::class);

//Tipos_Variedad

Route::apiResource("tipos/variedad", TiposVariedadController::class);

//Variedades por cultivo

Route::get('/variedades/{tpCul_id}', [TiposVariedadController::class, 'getVariedadesPorCultivo']);

//Clima

Route::apiResource("clima", ClimaController::class);

//Ciclo

Route::apiResource("ciclos", CiclosController::class);

//Actividades

Route::apiResource("actividades", ActividadesController::class);

//Control_det

Route::apiResource("control_det", ControlDetController::class);



//Usuarios

Route::apiResource("usuarios", UserController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/usuarios/{id}/permisos', [UserController::class, 'asignarPermisos']);
});


//Auth

Route::post('/login',[AuthController::class, 'login']);

Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');
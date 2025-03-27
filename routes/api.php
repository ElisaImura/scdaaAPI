<?php

use App\Http\Controllers\LotesController;
use App\Http\Controllers\ClimaController;
use App\Http\Controllers\CiclosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ControlDetController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\TiposCultivoController;
use App\Http\Controllers\TiposVariedadController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Lotes

Route::middleware(['auth:sanctum'])->group(function () {
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

    //Ciclos por Lote
    Route::get('/ciclos/lote/{lotId}', [CiclosController::class, 'getCiclosByLote']);

    //Actividades
    Route::apiResource("actividades", ActividadesController::class);

    //Control_det
    Route::apiResource("control_det", ControlDetController::class);

    //Roles
    Route::get("/roles", [RolesController::class, 'index']);

    //Permisos
    Route::get("/permisos", [RolesController::class, 'permisos']);

    //Usuarios
    Route::apiResource("usuarios", UserController::class);

    //Asignar permiso
    Route::post('/usuarios/{id}/permisos', [UserController::class, 'asignarPermisos']);

    //Eliminar permiso
    Route::delete('/usuarios/{id}/permisos', [UserController::class, 'quitarPermisos']);

    //Auth logout
    Route::post('/logout',[AuthController::class, 'logout']);

    //Reportes
    Route::get('/reportes/produccion', [ReportesController::class, 'produccionAgricola']);
    Route::get('/reportes/lluvia', [ReportesController::class, 'lluviaPorFechas']);

    //Cambiar password
    Route::post('/change-password', [PasswordResetController::class, 'changePassword']);

});

//Auth login
Route::post('/login',[AuthController::class, 'login']);

//Password reset
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);


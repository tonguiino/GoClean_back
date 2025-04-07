<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\CalificacionesController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
|
| Aquí colocamos las rutas relacionadas con login, registro, etc.
| Por ahora solo tenemos login y NO usamos middleware como Sanctum.
|
*/
Route::post('/login', [UsuariosController::class, 'login']);
Route::post('/agendar', [ServicioController::class, 'agendarServicio']);


/*
|--------------------------------------------------------------------------
| Rutas API - Recursos
|--------------------------------------------------------------------------
|
| Estas rutas manejan los recursos principales de la aplicación.
| Cada apiResource genera automáticamente las rutas REST (index, show, store, update, destroy).
|
*/
Route::apiResource('usuarios', UsuariosController::class);
Route::apiResource('servicios', ServiciosController::class);
Route::apiResource('facturas', FacturasController::class);
Route::apiResource('calificaciones', CalificacionesController::class);

/*
|--------------------------------------------------------------------------
| Rutas para Solicitudes
|--------------------------------------------------------------------------
|
| Rutas para manejar las solicitudes de los usuarios y las interacciones con los socios.
|
/*
|--------------------------------------------------------------------------
| Rutas para obtener información relacionada con los socios
|--------------------------------------------------------------------------
|
| Estas rutas proporcionan información específica de los socios.
|
*/
Route::get('/servicios/socio/{id}', [ServiciosController::class, 'porSocio']);
Route::get('/servicios/usuario/{id}', [ServiciosController::class, 'porUsuario']);
Route::get('/calificaciones/socio/{id}', [CalificacionesController::class, 'porSocio']);
Route::get('/facturas/socio/{id}', [FacturasController::class, 'porSocio']);
Route::put('/servicios/aceptar/{id}', [ServiciosController::class, 'aceptarServicio']);
Route::put('/servicios/rechazar/{id}', [ServiciosController::class, 'rechazarServicio']);

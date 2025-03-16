<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\CalificacionesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('usuarios', UsuariosController::class);
Route::apiResource('servicios', ServiciosController::class);
Route::apiResource('facturas', FacturasController::class);
Route::apiResource('calificaciones', CalificacionesController::class);

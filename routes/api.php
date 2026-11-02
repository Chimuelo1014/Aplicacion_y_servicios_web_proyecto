<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VehiculoController;
use App\Models\Marca;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API REST (prefijo /api)
|--------------------------------------------------------------------------
| Públicas: login y consulta de marcas.
| Protegidas con Sanctum: enviar cabecera  Authorization: Bearer {token}
*/

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/marcas', fn () => Marca::orderBy('nombre')->get(['id', 'nombre', 'pais_origen']));

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('vehiculos', VehiculoController::class)->names('api.vehiculos');
});

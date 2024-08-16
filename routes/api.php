<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentasRestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clientes/dni/{dni}', [ClienteController::class, 'buscarPorDni']);
Route::get('/clientes/ruc/{nro_doc}', [ClienteController::class, 'buscarPorNroDoc']);
Route::resource('/venta-fecha',VentasRestController::class);


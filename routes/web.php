<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\PedidosController;

Route::resource('mesas', MesasController::class);
Route::resource('productos', ProductosController::class);
Route::resource('pedidos', PedidosController::class);

Route::get('/mesas/{id}/edit', [MesasController::class, 'edit'])->name('mesas.edit');
Route::post('/mesas/{id}', [MesasController::class, 'update'])->name('mesas.update');

Route::get('/map', function () {
    return view('pages.map');
})->name('pages.map');

Route::get('/', function () {
    return view('pages.inicio');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\PedidosController;

Route::resource('mesas', MesasController::class);
Route::resource('productos', ProductosController::class);
Route::resource('pedidos', PedidosController::class);

//mesas edit y update
Route::get('/mesas/{id}/edit', [MesasController::class, 'updateMesa'])->name('mesas.edit');
Route::post('/mesas/{id}', [MesasController::class, 'updateMesa'])->name('mesas.update');

// productos edit y update
Route::get('/productos/{id}/edit', [ProductosController::class, 'edit'])->name('productos.edit');
Route::post('/productos/{id}', [ProductosController::class, 'update'])->name('productos.update');

// pedidos
Route::get('/pedidos/create/{mesa}', [PedidosController::class, 'create'])->name('pedidos.create');
Route::get('/pedidos/{pedido}/edit-productos', [PedidosController::class, 'editProductos'])->name('pedidos.edit-productos');
Route::put('/pedidos/{pedido}/update-productos', [PedidosController::class, 'updateProductos'])->name('pedidos.update-productos');
Route::get('/mesas/{id}/pedido', [PedidosController::class, 'getPedidoByMesa'])->name('mesas.pedido');

Route::get('/map', [MesasController::class, 'map'])->name('map');

// Ruta para actualizar la posición de las mesas
Route::post('/mesas/{id}/update-position', [MesasController::class, 'updateMesa'])->name('mesas.updatePosition');

Route::get('/', function () {
    return view('pages.inicio');
});

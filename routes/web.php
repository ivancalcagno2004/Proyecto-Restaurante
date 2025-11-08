<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesasController;

Route::resource('mesas', MesasController::class);
Route::get('/', function () {
    return view('pages.inicio');
});

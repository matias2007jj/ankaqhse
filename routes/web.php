<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\AutenticacionController;

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas Públicas de Autenticación
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'autenticar'])->name('login.autenticar');
Route::post('/logout', [AutenticacionController::class, 'cerrarSesion'])->name('logout');

// Rutas Protegidas de la Plataforma
Route::middleware(['auth'])->group(function () {
    Route::get('/inicio', [CapacitacionController::class, 'inicio'])->name('capacitaciones.inicio');
    Route::get('/capacitaciones', [CapacitacionController::class, 'inicio']);
});
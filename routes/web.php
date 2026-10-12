<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AutenticacionController;

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación pública
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'autenticar'])->name('login.autenticar');
Route::post('/logout', [AutenticacionController::class, 'cerrarSesion'])->name('logout');

// Rutas Protegidas de la Plataforma
Route::middleware(['auth'])->group(function () {
    // Portal del Empleado
    Route::get('/inicio', [CapacitacionController::class, 'inicio'])->name('capacitaciones.inicio');
    Route::get('/capacitaciones', [CapacitacionController::class, 'inicio']);

    // Panel de Administración (ANKA ACADEMY ADMIN)
    Route::get('/administrador/panel', [AdministradorController::class, 'panel'])->name('administrador.panel');
    Route::post('/administrador/cursos/guardar', [AdministradorController::class, 'guardarCurso'])->name('administrador.cursos.guardar');
    Route::post('/administrador/asignar', [AdministradorController::class, 'asignarCurso'])->name('administrador.asignar');
});
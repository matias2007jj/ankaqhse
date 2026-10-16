<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
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

    // Portal del Empleado / Alumno
    Route::get('/inicio', [AlumnoController::class, 'inicio'])->name('inicio');
    Route::get('/capacitaciones', [CapacitacionController::class, 'inicio'])->name('capacitaciones.inicio');

    // Panel de Administración (ANKA ACADEMY ADMIN)
    // Sombra de ambas URLs para prevenir errores 404 al redirigir
    Route::get('/administrador', [AdministradorController::class, 'panel'])->name('admin.panel');
    Route::get('/administrador/panel', [AdministradorController::class, 'panel'])->name('administrador.panel');

    // Acciones del Administrador
    Route::post('/administrador/cursos/guardar', [AdministradorController::class, 'guardarCurso'])->name('administrador.cursos.guardar');
    Route::post('/administrador/asignar', [AdministradorController::class, 'asignarCurso'])->name('administrador.asignar');
    Route::post('/administrador/asignar-curso', [AdministradorController::class, 'asignarCurso'])->name('admin.asignar-curso');
});
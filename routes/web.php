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

    // Contenido del curso: módulos con video y evaluación
    Route::get('/cursos/{curso}', [AlumnoController::class, 'curso'])->name('alumno.curso');
    Route::post('/cursos/{curso}/modulos/{modulo}/completar', [AlumnoController::class, 'completarModulo'])->name('alumno.modulo.completar');
    Route::post('/cursos/{curso}/examen', [AlumnoController::class, 'rendirExamen'])->name('alumno.examen.rendir');

    // Panel de Administración (ANKA ACADEMY ADMIN)
    // Sombra de ambas URLs para prevenir errores 404 al redirigir
    Route::get('/administrador', [AdministradorController::class, 'panel'])->name('admin.panel');
    Route::get('/administrador/panel', [AdministradorController::class, 'panel'])->name('administrador.panel');

    // Acciones del Administrador
    Route::post('/administrador/cursos/guardar', [AdministradorController::class, 'guardarCurso'])->name('administrador.cursos.guardar');
    Route::post('/administrador/videos/subir', [AdministradorController::class, 'subirVideoTemporal'])->name('administrador.videos.subir');
    Route::post('/administrador/asignar', [AdministradorController::class, 'asignarCurso'])->name('administrador.asignar');
    Route::post('/administrador/cursos/actualizar', [AdministradorController::class, 'actualizarCurso'])->name('administrador.cursos.actualizar');
    Route::post('/administrador/asignar-curso', [AdministradorController::class, 'asignarCurso'])->name('admin.asignar-curso');
});
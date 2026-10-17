<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AutenticacionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'autenticar'])->name('login.autenticar');
Route::post('/logout', [AutenticacionController::class, 'cerrarSesion'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/inicio', [AlumnoController::class, 'inicio'])->name('inicio');
    Route::get('/capacitaciones', [CapacitacionController::class, 'inicio'])->name('capacitaciones.inicio');
    

    Route::get('/cursos/{curso}', [AlumnoController::class, 'curso'])->name('alumno.curso');
    Route::post('/cursos/{curso}/modulos/{modulo}/completar', [AlumnoController::class, 'completarModulo'])->name('alumno.modulo.completar');
    Route::post('/cursos/{curso}/examen', [AlumnoController::class, 'rendirExamen'])->name('alumno.examen.rendir');

    Route::get('/administrador', [AdministradorController::class, 'panel'])->name('admin.panel');
    Route::get('/administrador/panel', [AdministradorController::class, 'panel'])->name('administrador.panel');

    // Usuarios
    Route::post('/administrador/usuarios/guardar', [AdministradorController::class, 'guardarUsuario'])->name('administrador.usuarios.guardar');
    Route::post('/administrador/usuarios/eliminar', [AdministradorController::class, 'eliminarUsuario'])->name('administrador.usuarios.eliminar');
     Route::post('/administrador/usuarios/actualizar', [AdministradorController::class, 'actualizarUsuario'])->name('administrador.usuarios.actualizar');
     
    // Cursos
    Route::post('/administrador/cursos/guardar', [AdministradorController::class, 'guardarCurso'])->name('administrador.cursos.guardar');
    Route::post('/administrador/cursos/actualizar', [AdministradorController::class, 'actualizarCurso'])->name('administrador.cursos.actualizar');
    Route::post('/administrador/cursos/eliminar', [AdministradorController::class, 'eliminarCurso'])->name('administrador.cursos.eliminar');

    // Módulos
    Route::post('/administrador/modulos/guardar', [AdministradorController::class, 'guardarModulo'])->name('administrador.modulos.guardar');
    Route::post('/administrador/modulos/actualizar', [AdministradorController::class, 'actualizarModulo'])->name('administrador.modulos.actualizar');
    Route::post('/administrador/modulos/eliminar', [AdministradorController::class, 'eliminarModulo'])->name('administrador.modulos.eliminar');
   

    Route::post('/administrador/videos/subir', [AdministradorController::class, 'subirVideoTemporal'])->name('administrador.videos.subir');
    Route::post('/administrador/asignar', [AdministradorController::class, 'asignarCurso'])->name('administrador.asignar');
    Route::post('/administrador/asignar-curso', [AdministradorController::class, 'asignarCurso'])->name('admin.asignar-curso');
});
@extends('plantillas.aplicacion')

@section('titulo', 'Panel de Control General - ANKA ACADEMY')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/administrador.css') }}">
@endpush

@section('contenido')
<div id="panel-admin" class="panel-container space-y-8" data-alerta="{{ (session('success') || session('warning') || $errors->any()) ? '1' : '0' }}">

    <!-- CABECERA DE TITULO -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-200 pb-5">
        <div>
            <span class="inline-block bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-0.5 rounded-full mb-1">MÓDULO GESTIÓN</span>
            <h1 class="text-2xl font-bold text-neutral-900">Panel de Control General</h1>
            <p class="text-sm text-neutral-500">Monitorea el nivel de cumplimiento y gestiona los programas de capacitación corporativos.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/inicio" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-semibold rounded-xl text-sm transition-all flex items-center gap-2">
                <i data-lucide="eye" class="w-4 h-4"></i> Ver Vista Alumno
            </a>
        </div>
    </div>

    <!-- ALERTAS DE FEEDBACK EN EL PANEL -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl text-sm font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-600 font-bold cursor-pointer">✕</button>
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-sm font-semibold flex items-center justify-between">
            <span>{{ session('warning') }}</span>
            <button onclick="this.parentElement.remove()" class="text-amber-600 font-bold cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm space-y-1">
            <div class="flex items-center justify-between">
                <strong class="font-bold">No se pudo guardar. Revisa lo siguiente:</strong>
                <button onclick="this.closest('div.p-4').remove()" class="text-red-600 font-bold cursor-pointer">✕</button>
            </div>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- NAVEGACIÓN DE TABS -->
    <div class="flex border-b border-neutral-200 space-x-4">
        <button type="button" onclick="switchAdminTab('metrica-general')" id="btn-tab-metrica" class="tab-admin-btn pb-3 px-2 border-b-2 border-orange-500 font-bold text-orange-600 text-sm cursor-pointer">
            Métrica General
        </button>
        <button type="button" onclick="switchAdminTab('monitoreo-empleados')" id="btn-tab-monitoreo" class="tab-admin-btn pb-3 px-2 border-b-2 border-transparent font-medium text-neutral-500 hover:text-neutral-800 text-sm cursor-pointer">
            Monitoreo Empleados
        </button>
        <button type="button" onclick="switchAdminTab('gestion-cursos')" id="btn-tab-cursos" class="tab-admin-btn pb-3 px-2 border-b-2 border-transparent font-medium text-neutral-500 hover:text-neutral-800 text-sm cursor-pointer">
            Gestión de Cursos
        </button>
    </div>

    <!-- TARJETAS DE MÉTRICAS GLOBALES -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm">
            <span class="text-xs font-bold text-neutral-400 block tracking-wider">TOTAL COLABORADORES</span>
            <span class="text-2xl font-black text-neutral-800 mt-1 block">{{ $totalColaboradores ?? 0 }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm">
            <span class="text-xs font-bold text-neutral-400 block tracking-wider">CAPACITACIONES ACTIVAS</span>
            <span class="text-2xl font-black text-neutral-800 mt-1 block">{{ $capacitacionesActivas ?? 0 }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm">
            <span class="text-xs font-bold text-neutral-400 block tracking-wider">TASA DE CUMPLIMIENTO</span>
            <span class="text-2xl font-black text-orange-600 mt-1 block">{{ $tasaCumplimiento ?? 0 }}%</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm">
            <span class="text-xs font-bold text-neutral-400 block tracking-wider">CERTIFICADOS APROBADOS</span>
            <span class="text-2xl font-black text-neutral-800 mt-1 block">{{ $certificadosAprobados ?? 0 }}</span>
        </div>
    </section>

    <!-- CONTENIDO POR PESTAÑAS -->
    <main class="tabs-content">

        <!-- TAB 1: MÉTRICA GENERAL -->
        <section id="metrica-general" class="tab-pane-admin space-y-4">
            <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4">
                <div>
                    <h2 class="text-lg font-bold text-neutral-800">Avance por Área o Departamento</h2>
                    <p class="text-xs text-neutral-500">Porcentaje de cumplimiento de capacitaciones obligatorias por sección</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($avancePorArea ?? [] as $area)
                    <div class="p-4 rounded-xl border border-neutral-100 bg-neutral-50 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-neutral-800">{{ is_object($area) ? $area->area : ($area['area'] ?? 'Área') }}</span>
                            <span class="font-black text-orange-600">{{ is_object($area) ? ($area->porcentaje_cumplimiento ?? 0) : ($area['porcentaje_cumplimiento'] ?? 0) }}%</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ is_object($area) ? ($area->porcentaje_cumplimiento ?? 0) : ($area['porcentaje_cumplimiento'] ?? 0) }}%;"></div>
                        </div>
                        <span class="text-xs text-neutral-400 block">{{ is_object($area) ? ($area->total_empleados ?? 0) : ($area['total_empleados'] ?? 0) }} empleados asignados</span>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-neutral-400 text-sm">
                        No se encontraron áreas registradas en la base de datos.
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- TAB 2: MONITOREO EMPLEADOS -->
        <section id="monitoreo-empleados" class="tab-pane-admin space-y-4 hidden">
            <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-neutral-800">Estado de Avance de Colaboradores</h2>
                        <p class="text-xs text-neutral-500">Supervisa el progreso individual y aprueba asignaciones</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-600">
                        <thead class="bg-neutral-50 text-neutral-400 text-xs uppercase font-bold border-b border-neutral-200">
                            <tr>
                                <th class="p-3">COLABORADOR</th>
                                <th class="p-3">ÁREA / DEPARTAMENTO</th>
                                <th class="p-3">CURSOS ASIGNADOS</th>
                                <th class="p-3">PROGRESO</th>
                                <th class="p-3">ESTADO</th>
                                <th class="p-3 text-right">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($empleados ?? [] as $empleado)
                                @if(in_array(strtolower(trim($empleado->role ?? '')), ['operador', 'admin', 'administrador']))
                                    @continue
                                @endif

                            <tr class="hover:bg-neutral-50/50">
                                <td class="p-3 flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($empleado->name ?? $empleado->nombre ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="text-neutral-800 font-semibold block">{{ $empleado->name ?? $empleado->nombre ?? 'Usuario' }}</strong>
                                        <small class="text-neutral-400 text-xs">{{ $empleado->cargo ?? 'Empleado' }}</small>
                                    </div>
                                </td>
                                <td class="p-3">{{ $empleado->area ?? 'Sin área' }}</td>
                                <td class="p-3 font-semibold">{{ $empleado->cursos_completados_count ?? 0 }} / {{ $empleado->cursos_count ?? 0 }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-neutral-100 rounded-full h-2">
                                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $empleado->progreso ?? 0 }}%;"></div>
                                        </div>
                                        <span class="text-xs font-bold text-neutral-700">{{ $empleado->progreso ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="p-3">
                                    @if(($empleado->progreso ?? 0) == 100)
                                    <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold">Completado</span>
                                    @elseif(($empleado->progreso ?? 0) > 0)
                                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold">Al día</span>
                                    @else
                                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-bold">Pendiente</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <button type="button"
                                        data-id="{{ $empleado->id }}"
                                        data-nombre="{{ $empleado->name ?? $empleado->nombre }}"
                                        data-cursos='@json($empleado->cursos ? $empleado->cursos->pluck('id')->values() : [])'
                                        onclick="abrirModalAsignar(this)"
                                        class="px-3 py-1.5 border border-orange-500 text-orange-600 hover:bg-orange-50 text-xs font-bold rounded-lg transition-all cursor-pointer">
                                        Asignar Curso
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-neutral-400">No hay colaboradores registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- TAB 3: GESTIÓN DE CURSOS -->
        <section id="gestion-cursos" class="tab-pane-admin space-y-4 hidden">
            
            <!-- BOTÓN CREAR CURSO (EXCLUSIVO OPERADOR) -->
            @if(strtolower(trim(Auth::user()->role ?? '')) === 'operador')
            <div class="flex justify-end">
                <button type="button" onclick="abrirModalCrearCurso()" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center gap-2 cursor-pointer">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Crear Nuevo Curso
                </button>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cursos ?? [] as $curso)
                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        {{-- Imagen del Curso --}}
                        @if(!empty($curso->imagen))
                            <img src="{{ asset($curso->imagen) }}" alt="{{ $curso->titulo ?? $curso->nombre }}" class="w-full h-36 object-cover border-b border-neutral-100">
                        @else
                            <div class="w-full h-36 bg-orange-50/60 border-b border-neutral-100 flex items-center justify-center text-orange-400">
                                <i data-lucide="book-open" class="w-10 h-10"></i>
                            </div>
                        @endif

                        <div class="p-5 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $curso->categoria ?? 'General' }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-green-600">Publicado</span>
                                    
                                    {{-- Botón de Editar visible para roles autorizados del panel --}}
                                    @if(in_array(strtolower(trim(Auth::user()->role ?? '')), ['operador', 'admin', 'administrador']))
                                    <button type="button" 
                                        data-curso='@json($curso)' onclick="abrirModalEditarCurso(this)"
                                        class="px-2 py-1 bg-neutral-100 hover:bg-orange-100 text-neutral-600 hover:text-orange-700 text-xs font-bold rounded-lg transition-all cursor-pointer flex items-center gap-1"
                                        title="Editar Curso">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Editar
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <h3 class="font-bold text-neutral-800 text-base leading-snug">{{ $curso->titulo ?? $curso->nombre }}</h3>
                            <p class="text-xs text-neutral-400">{{ $curso->modulos_count ?? 0 }} Módulos • {{ $curso->usuarios_count ?? 0 }} Colaboradores inscritos</p>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <button type="button" class="w-full py-2 bg-neutral-900 text-white text-xs font-semibold rounded-xl hover:bg-neutral-800 transition-all cursor-pointer">
                            Gestionar Examen
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-neutral-200">
                    <i data-lucide="book-x" class="w-12 h-12 text-neutral-300 mx-auto mb-3"></i>
                    <h3 class="text-base font-bold text-neutral-700">No hay cursos cargados</h3>
                    <p class="text-xs text-neutral-400 mt-1">Los cursos que vayas registrando aparecerán aquí.</p>
                </div>
                @endforelse
            </div>
        </section>

    </main>
</div>

<!-- MODAL ASIGNAR CURSO -->
@include('administrador.modales.asignar-curso')

<!-- MODAL CREAR CURSO (EXCLUSIVO OPERADOR) : 3 pasos = Datos -> Módulos y videos -> Evaluación -->
@if(strtolower(trim(Auth::user()->role ?? '')) === 'operador')
<div id="modal-crear-curso" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl border border-neutral-200 max-w-3xl w-full max-h-[92vh] flex flex-col shadow-xl">

        <!-- Cabecera -->
        <div class="flex justify-between items-center border-b border-neutral-100 px-6 py-4">
            <h3 class="font-bold text-lg text-neutral-800 flex items-center gap-2">
                <i data-lucide="book-plus" class="text-orange-600 w-5 h-5"></i> Crear Nuevo Curso
            </h3>
            <button type="button" onclick="cerrarModalCrearCurso()" class="text-neutral-400 hover:text-neutral-700 font-bold text-lg cursor-pointer">✕</button>
        </div>

        <!-- Indicador de pasos -->
        <div class="flex items-center gap-2 px-6 py-3 border-b border-neutral-100 text-xs font-bold">
            <span class="indicador-paso flex items-center gap-2" data-paso="1"><span class="w-6 h-6 rounded-full flex items-center justify-center bg-orange-600 text-white">1</span> Datos</span>
            <span class="flex-1 h-px bg-neutral-200"></span>
            <span class="indicador-paso flex items-center gap-2" data-paso="2"><span class="w-6 h-6 rounded-full flex items-center justify-center bg-neutral-200 text-neutral-500">2</span> Módulos y videos</span>
            <span class="flex-1 h-px bg-neutral-200"></span>
            <span class="indicador-paso flex items-center gap-2" data-paso="3"><span class="w-6 h-6 rounded-full flex items-center justify-center bg-neutral-200 text-neutral-500">3</span> Evaluación</span>
        </div>

        <form id="form-crear-curso" action="{{ route('administrador.cursos.guardar') }}" method="POST" enctype="multipart/form-data" novalidate class="flex flex-col min-h-0 flex-1">
            @csrf

            <div id="cuerpo-crear-curso" class="overflow-y-auto px-6 py-5 flex-1">

                <!-- PASO 1: DATOS GENERALES -->
                <div id="paso-1" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Título del Curso</label>
                        <input type="text" name="titulo" required placeholder="Ej: Seguridad y Salud en el Trabajo" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Categoría</label>
                        <input type="text" name="categoria" required placeholder="Ej: Seguridad, Calidad, RRHH" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Imagen de Portada</label>
                        <input type="file" name="imagen" accept="image/*" class="w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer border border-neutral-200 rounded-xl p-1">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Descripción</label>
                        <textarea name="descripcion" rows="3" placeholder="Breve resumen del contenido del curso..." class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500"></textarea>
                    </div>
                </div>

                <!-- PASO 2: MÓDULOS Y VIDEOS -->
                <div id="paso-2" class="space-y-4 hidden">
                    <div class="flex items-end gap-4">
                        <div class="w-48">
                            <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Cantidad de módulos</label>
                            <input type="number" id="cantidad-modulos" min="1" max="20" value="1" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                        </div>
                        <p class="text-xs text-neutral-400 pb-2">Cada módulo lleva un título y un video (MP4, WebM o MOV, máx. 200 MB).</p>
                    </div>

                    <div id="contenedor-modulos" class="space-y-3"></div>
                </div>

                <!-- PASO 3: EVALUACIÓN -->
                <div id="paso-3" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Título de la evaluación</label>
                            <input type="text" name="examen[titulo]" required placeholder="Ej: Evaluación final del curso" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Nota mínima (1-20)</label>
                            <input type="number" name="examen[nota_minima]" required min="1" max="20" value="14" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
                        </div>
                    </div>

                    <div id="contenedor-preguntas" class="space-y-4"></div>

                    <button type="button" id="btn-agregar-pregunta" class="px-4 py-2 border border-orange-500 text-orange-600 hover:bg-orange-50 text-xs font-bold rounded-xl transition-all cursor-pointer flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Agregar pregunta
                    </button>
                </div>
            </div>

            <!-- Botonera -->
            <div class="flex justify-between items-center gap-2 border-t border-neutral-100 px-6 py-4">
                <button type="button" onclick="cerrarModalCrearCurso()" class="px-4 py-2 border border-neutral-200 text-neutral-600 rounded-xl text-xs font-bold hover:bg-neutral-50 transition-all cursor-pointer">
                    Cancelar
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-anterior" class="hidden px-4 py-2 border border-neutral-200 text-neutral-700 rounded-xl text-xs font-bold hover:bg-neutral-50 transition-all cursor-pointer">
                        ← Anterior
                    </button>
                    <button type="button" id="btn-siguiente" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Siguiente →
                    </button>
                    <button type="submit" id="btn-guardar-curso" class="hidden px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer">
                        Guardar Curso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- MODAL EDITAR CURSO (DISPONIBLE EN EL DOM) -->
<div id="modal-editar-curso" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl border border-neutral-200 max-w-lg w-full p-6 shadow-xl space-y-5">
        <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
            <h3 class="font-bold text-lg text-neutral-800 flex items-center gap-2">
                <i data-lucide="edit-3" class="text-orange-600 w-5 h-5"></i> Editar Curso
            </h3>
            <button type="button" onclick="cerrarModalEditarCurso()" class="text-neutral-400 hover:text-neutral-700 font-bold text-lg cursor-pointer">✕</button>
        </div>

        <form action="{{ route('administrador.cursos.actualizar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="curso_id" id="edit-curso-id">

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Título del Curso</label>
                <input type="text" name="titulo" id="edit-curso-titulo" required class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Categoría</label>
                <input type="text" name="categoria" id="edit-curso-categoria" required class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Cambiar Imagen de Portada (Opcional)</label>
                <input type="file" name="imagen" accept="image/*" class="w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer border border-neutral-200 rounded-xl p-1">
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Descripción</label>
                <textarea name="descripcion" id="edit-curso-descripcion" rows="3" class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-orange-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="cerrarModalEditarCurso()" class="px-4 py-2 border border-neutral-200 text-neutral-600 rounded-xl text-xs font-bold hover:bg-neutral-50 transition-all cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer">
                    Actualizar Curso
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/crear-curso.js') }}"></script>
<script src="{{ asset('js/panel-admin.js') }}"></script>
@endpush
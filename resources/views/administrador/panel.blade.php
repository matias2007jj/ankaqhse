@extends('plantillas.aplicacion')

@section('titulo', 'Panel de Control General - ANKA ACADEMY')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/administrador.css') }}">
@endpush

@section('contenido')
<div class="panel-container space-y-8">

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
                                {{-- Ocultar silenciosamente cualquier usuario operador/admin en la vista por redundancia --}}
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
                                        onclick="abrirModalAsignar({{ $empleado->id }}, '{{ $empleado->name ?? $empleado->nombre }}', {{ json_encode($empleado->cursos ? $empleado->cursos->pluck('id')->toArray() : []) }})"
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cursos ?? [] as $curso)
                <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $curso->categoria ?? 'General' }}</span>
                            <span class="text-xs font-semibold text-green-600">Publicado</span>
                        </div>
                        <h3 class="font-bold text-neutral-800 text-base leading-snug">{{ $curso->titulo ?? $curso->nombre }}</h3>
                        <p class="text-xs text-neutral-400">{{ $curso->modulos_count ?? 0 }} Módulos • {{ $curso->usuarios_count ?? 0 }} Colaboradores inscritos</p>
                    </div>
                    <button type="button" class="w-full py-2 bg-neutral-900 text-white text-xs font-semibold rounded-xl hover:bg-neutral-800 transition-all cursor-pointer">
                        Gestionar Examen
                    </button>
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

<!-- MODAL EN PARCIAL SEPARADO -->
@include('administrador.modales.asignar-curso')

@endsection

@push('scripts')
<script>
    window.switchAdminTab = function(tabId) {
        var panes = document.querySelectorAll('.tab-pane-admin');
        for (var i = 0; i < panes.length; i++) {
            panes[i].classList.add('hidden');
        }

        var btns = document.querySelectorAll('.tab-admin-btn');
        for (var j = 0; j < btns.length; j++) {
            btns[j].classList.remove('border-orange-500', 'font-bold', 'text-orange-600');
            btns[j].classList.add('border-transparent', 'font-medium', 'text-neutral-500');
        }

        var selectedPane = document.getElementById(tabId);
        if (selectedPane) {
            selectedPane.classList.remove('hidden');
        }

        var key = tabId.replace('metrica-general', 'metrica')
            .replace('monitoreo-empleados', 'monitoreo')
            .replace('gestion-cursos', 'cursos');

        var activeBtn = document.getElementById('btn-tab-' + key);
        if (activeBtn) {
            activeBtn.classList.add('border-orange-500', 'font-bold', 'text-orange-600');
            activeBtn.classList.remove('border-transparent', 'font-medium', 'text-neutral-500');
        }
    };

    window.abrirModalAsignar = function(userId, userName, cursosAsignados) {
        cursosAsignados = cursosAsignados || [];
        
        var inputId = document.getElementById('modal-user-id');
        var inputName = document.getElementById('modal-user-name');
        var modal = document.getElementById('modal-asignar-curso');
        var selectCurso = document.getElementById('modal-curso-id');

        if (inputId) inputId.value = userId;
        if (inputName) inputName.value = userName;

        if (selectCurso) {
            selectCurso.value = ""; // Resetear selección

            var options = selectCurso.querySelectorAll('option');
            options.forEach(function(option) {
                if (!option.value) return;

                var cursoId = parseInt(option.value);

                if (Array.isArray(cursosAsignados) && cursosAsignados.includes(cursoId)) {
                    option.style.display = 'none';
                    option.disabled = true;
                } else {
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
        }

        if (modal) modal.classList.remove('hidden');
    };

    window.cerrarModalAsignar = function() {
        var modal = document.getElementById('modal-asignar-curso');
        if (modal) modal.classList.add('hidden');
    };

    // Auto-mantener la pestaña activa tras enviar un formulario o recargar
    document.addEventListener("DOMContentLoaded", function() {
        var tieneAlerta = {{ (session('success') || session('warning')) ? 'true' : 'false' }};
        if (tieneAlerta) {
            switchAdminTab('monitoreo-empleados');
        }
    });
</script>
@endpush
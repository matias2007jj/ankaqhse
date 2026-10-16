@extends('plantillas.aplicacion')

@section('titulo', 'Panel de Administración - ANKA ACADEMY')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/administrador.css') }}">
@endpush

@section('navegacion')
    <nav class="hidden md:flex items-center space-x-1">
        <button type="button" onclick="switchAdminTab('metrics')" id="admin-tab-metrics" class="boton-pestana-admin boton-pestana-admin-activo">
            <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Métrica General
        </button>
        <button type="button" onclick="switchAdminTab('employees')" id="admin-tab-employees" class="boton-pestana-admin boton-pestana-admin-inactivo">
            <i data-lucide="users" class="w-4 h-4"></i> Monitoreo Empleados
        </button>
        <button type="button" onclick="switchAdminTab('courses-mgr')" id="admin-tab-courses-mgr" class="boton-pestana-admin boton-pestana-admin-inactivo">
            <i data-lucide="folder-kanban" class="w-4 h-4"></i> Gestión de Cursos
        </button>
        <a href="{{ route('capacitaciones.inicio') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-neutral-100 text-neutral-600 hover:bg-orange-50 hover:text-orange-600 transition-all flex items-center gap-1.5 ml-2">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Ver Vista Alumno
        </a>
    </nav>
@endsection

@section('contenido')

    <!-- ENCABEZADO DE SECCIÓN -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-neutral-200">
        <div>
            <div class="flex items-center gap-2">
            </div>
            <h1 class="text-2xl font-extrabold text-neutral-900 mt-1">Panel de Control General</h1>
            <p class="text-neutral-500 text-sm">Monitorea el nivel de cumplimiento y gestiona los programas de capacitación corporativos.</p>
        </div>
        <div class="flex items-center gap-3">
<button type="button" onclick="openModalCrearCurso()" class="px-4 py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold rounded-xl text-sm shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center gap-2 cursor-pointer">
    <i data-lucide="plus" class="w-4 h-4"></i> Crear Nuevo Curso
</button>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS GENERALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 my-6">
        <div class="tarjeta-kpi">
            <div class="icono-kpi bg-orange-100 text-orange-600">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="titulo-kpi">Total Colaboradores</p>
                <p class="valor-kpi">{{ $metricas['total_empleados'] }}</p>
            </div>
        </div>

        <div class="tarjeta-kpi">
            <div class="icono-kpi bg-blue-100 text-blue-600">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="titulo-kpi">Capacitaciones Activas</p>
                <p class="valor-kpi">{{ $metricas['cursos_activos'] }}</p>
            </div>
        </div>

        <div class="tarjeta-kpi">
            <div class="icono-kpi bg-green-100 text-green-600">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="titulo-kpi">Tasa de Cumplimiento</p>
                <p class="valor-kpi">{{ $metricas['tasa_cumplimiento'] }}%</p>
            </div>
        </div>

        <div class="tarjeta-kpi">
            <div class="icono-kpi bg-amber-100 text-amber-600">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="titulo-kpi">Certificados Aprobados</p>
                <p class="valor-kpi">{{ $metricas['certificados_emitidos'] }}</p>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 1: MÉTRICAS Y CUMPLIMIENTO POR DEPARTAMENTO -->
    <section id="admin-view-metrics" class="admin-tab-content space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-neutral-900">Avance por Área o Departamento</h2>
                    <p class="text-xs text-neutral-500">Porcentaje de cumplimiento de capacitaciones obligatorias por sección</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                @foreach ($departamentos as $dept)
                    <div class="p-4 rounded-xl border border-neutral-100 bg-neutral-50/60 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-neutral-800">{{ $dept['nombre'] }}</span>
                            <span class="font-bold text-orange-600">{{ $dept['completado'] }}%</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-red-600 to-orange-500 h-2.5 rounded-full" style="width: {{ $dept['completado'] . '%' }};"></div>
                        </div>
                        <p class="text-[11px] text-neutral-400">{{ $dept['empleados'] }} empleados asignados</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECCIÓN 2: MONITOREO DE EMPLEADOS -->
    <section id="admin-view-employees" class="admin-tab-content space-y-6 hidden">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-neutral-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-neutral-900">Estado de Avance de Colaboradores</h2>
                    <p class="text-xs text-neutral-500">Supervisa el progreso individual y aprueba asignaciones</p>
                </div>
                <div class="relative max-w-xs w-full">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400"></i>
                    <input type="text" id="input-buscar-empleado" onkeyup="filtrarTablaEmpleados()" placeholder="Buscar colaborador..." class="w-full pl-9 pr-4 py-2 border border-neutral-200 rounded-xl text-xs bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-neutral-600" id="tabla-empleados">
                    <thead class="bg-neutral-50 border-b border-neutral-200 uppercase font-bold text-neutral-400 text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Colaborador</th>
                            <th class="px-6 py-3.5">Área / Departamento</th>
                            <th class="px-6 py-3.5 text-center">Cursos Asignados</th>
                            <th class="px-6 py-3.5 text-center">Progreso</th>
                            <th class="px-6 py-3.5 text-center">Estado</th>
                            <th class="px-6 py-3.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach ($empleados as $emp)
                            <tr class="hover:bg-neutral-50/80 transition-all">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-neutral-800 text-white font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($emp['nombre'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-neutral-900 text-sm leading-tight">{{ $emp['nombre'] }}</p>
                                            <p class="text-[11px] text-neutral-400">{{ $emp['puesto'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-neutral-700">
                                    {{ $emp['departamento'] }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-neutral-800">
                                    {{ $emp['cursos_completados'] }} / {{ $emp['cursos_asignados'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 bg-neutral-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $emp['progreso_general'] . '%' }};"></div>
                                        </div>
                                        <span class="font-bold text-neutral-800">{{ $emp['progreso_general'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($emp['estado'] === 'Completado')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700">Completado</span>
                                    @elseif ($emp['estado'] === 'Al día')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700">Al día</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        type="button"
                                        data-id="{{ $emp['id'] }}" 
                                        data-nombre="{{ $emp['nombre'] }}" 
                                        onclick="openModalAsignar(this.dataset.id, this.dataset.nombre)" 
                                        class="px-3 py-1.5 border border-neutral-300 rounded-lg text-xs font-semibold hover:bg-orange-50 hover:text-orange-600 transition-all cursor-pointer">
                                        Asignar Curso
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- SECCIÓN 3: GESTIÓN DE CURSOS -->
    <section id="admin-view-courses-mgr" class="admin-tab-content space-y-6 hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($cursos as $c)
                <div class="bg-white p-5 rounded-2xl border border-neutral-200 shadow-sm space-y-4 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">{{ $c['categoria'] }}</span>
                            <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> {{ $c['estado'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-neutral-900 text-base leading-snug">{{ $c['titulo'] }}</h3>
                        <p class="text-xs text-neutral-500">{{ $c['modulos'] }} Módulos • {{ $c['inscritos'] }} Colaboradores inscritos</p>
                    </div>

                    <div class="pt-3 border-t border-neutral-100 flex items-center justify-between gap-2">
                        <button type="button" class="flex-1 py-2 text-xs font-semibold border border-neutral-200 rounded-xl hover:bg-neutral-50 text-neutral-700 transition-all">
                            Editar Contenidos
                        </button>
                        <button type="button" class="p-2 border border-neutral-200 rounded-xl text-neutral-400 hover:text-red-600 hover:bg-red-50 transition-all">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ========================================== -->
    <!-- MODAL 1: CREAR NUEVO CURSO                 -->
    <!-- ========================================== -->
    <div id="modal-crear-curso" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-neutral-200 space-y-4">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                <h3 class="font-bold text-neutral-900 text-base">Crear Nueva Capacitación</h3>
                <button type="button" onclick="closeModalAdmin('modal-crear-curso')" class="text-neutral-400 hover:text-neutral-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('administrador.cursos.guardar') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-neutral-700 uppercase">Título del Curso</label>
                    <input type="text" name="titulo" required placeholder="Ej. Liderazgo Operativo 2026" class="w-full p-2.5 border border-neutral-200 rounded-xl text-xs bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-neutral-700 uppercase">Categoría</label>
                    <select name="categoria" class="w-full p-2.5 border border-neutral-200 rounded-xl text-xs bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="Compliance & Seguridad">Compliance & Seguridad</option>
                        <option value="Habilidades Blandas">Habilidades Blandas</option>
                        <option value="Seguridad Laboral">Seguridad Laboral (SST)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-neutral-700 uppercase">Descripción Corta</label>
                    <textarea name="descripcion" rows="3" placeholder="Resumen de los objetivos del curso..." class="w-full p-2.5 border border-neutral-200 rounded-xl text-xs bg-neutral-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModalAdmin('modal-crear-curso')" class="px-4 py-2 border border-neutral-200 text-neutral-600 text-xs font-semibold rounded-xl">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-semibold rounded-xl shadow-md">
                        Guardar y Publicar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: ASIGNAR CURSO A EMPLEADO          -->
    <!-- ========================================== -->
    <div id="modal-asignar-curso" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-neutral-200 space-y-4">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                <h3 class="font-bold text-neutral-900 text-base">Asignar Capacitación</h3>
                <button type="button" onclick="closeModalAdmin('modal-asignar-curso')" class="text-neutral-400 hover:text-neutral-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('administrador.asignar') }}" method="POST" class="space-y-4">
                @csrf
                <p class="text-xs text-neutral-500">Asignando curso obligatorio a: <strong id="nombre-empleado-modal" class="text-neutral-800"></strong></p>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-neutral-700 uppercase">Selecciona el Curso</label>
                    <select name="course_id" class="w-full p-2.5 border border-neutral-200 rounded-xl text-xs bg-neutral-50">
                        @foreach ($cursos as $c)
                            <option value="{{ $c['id'] }}">{{ $c['titulo'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModalAdmin('modal-asignar-curso')" class="px-4 py-2 border border-neutral-200 text-neutral-600 text-xs font-semibold rounded-xl">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-semibold rounded-xl shadow-md">
                        Confirmar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Abrir Modal "Crear Nuevo Curso"
    function openModalCrearCurso() {
        const modal = document.getElementById('modal-crear-curso');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Abrir Modal "Asignar Curso"
    function openModalAsignar(id, nombre) {
        const lbl = document.getElementById('nombre-empleado-modal');
        if (lbl) lbl.innerText = nombre;

        const modal = document.getElementById('modal-asignar-curso');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Cerrar Modales
    function closeModalAdmin(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Cambiar Pestañas del Panel
    function switchAdminTab(tabId) {
        document.querySelectorAll('.admin-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.boton-pestana-admin').forEach(btn => {
            btn.classList.remove('boton-pestana-admin-activo');
            btn.classList.add('boton-pestana-admin-inactivo');
        });

        const targetView = document.getElementById(`admin-view-${tabId}`);
        if (targetView) targetView.classList.remove('hidden');

        const activeBtn = document.getElementById(`admin-tab-${tabId}`);
        if (activeBtn) {
            activeBtn.classList.add('boton-pestana-admin-activo');
            activeBtn.classList.remove('boton-pestana-admin-inactivo');
        }
    }

    // Filtrar Tabla de Empleados
    function filtrarTablaEmpleados() {
        const input = document.getElementById('input-buscar-empleado').value.toLowerCase();
        const rows = document.querySelectorAll('#tabla-empleados tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }

    // Cerrar al hacer clic en el fondo oscuro
    document.addEventListener('click', function(e) {
        const modalCrear = document.getElementById('modal-crear-curso');
        const modalAsignar = document.getElementById('modal-asignar-curso');

        if (e.target === modalCrear) closeModalAdmin('modal-crear-curso');
        if (e.target === modalAsignar) closeModalAdmin('modal-asignar-curso');
    });
</script>
@endpush
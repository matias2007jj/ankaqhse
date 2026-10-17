@extends('plantillas.aplicacion')

@section('titulo', 'Mi Panel de Aprendizaje - ANKA ACADEMY')

@section('contenido')
<div class="alumno-container space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- SALUDO Y RESUMEN DEL ALUMNO -->
    <div class="bg-gradient-to-r from-neutral-900 to-neutral-800 text-white p-6 sm:p-8 rounded-3xl shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="bg-orange-500/20 text-orange-400 border border-orange-500/30 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                PANEL DEL COLABORADOR
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">
                ¡Hola, {{ $user->name ?? $user->nombre ?? 'Colaborador' }}! 👋
            </h1>
            <p class="text-neutral-400 text-sm max-w-xl">
                Continúa con tus capacitaciones obligatorias y potencia tus habilidades profesionales.
            </p>
        </div>

        <!-- TARJETA RESUMEN DE PROGRESO GLOBAL -->
        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl flex items-center gap-4 min-w-[220px]">
            <div class="w-12 h-12 rounded-xl bg-orange-500 text-white font-black flex items-center justify-center text-lg shadow-md">
                {{ $progresoGlobal }}%
            </div>
            <div>
                <span class="text-xs font-semibold text-neutral-300 block">Progreso Global</span>
                <span class="text-sm font-bold text-white">{{ $cursosCompletados }} de {{ $cursos->count() }} completados</span>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE CURSOS ASIGNADOS -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
            <div>
                <h2 class="text-xl font-bold text-neutral-800">Mis Capacitaciones Asignadas</h2>
                <p class="text-xs text-neutral-500">Cursos disponibles para completar tu plan de formación</p>
            </div>
            <span class="text-xs font-bold bg-neutral-100 text-neutral-600 px-3 py-1 rounded-full">
                {{ $cursos->count() }} {{ Str::plural('Curso', $cursos->count()) }}
            </span>
        </div>

        <!-- GRID DE TARJETAS DE CURSOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($cursos as $curso)
                @php
                    $progreso = $curso->pivot->progreso ?? 0;
                    $esCompletado = $progreso == 100;
                @endphp
                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    
                    <div class="p-5 space-y-4">
                        <!-- CATEGORÍA Y BADGE DE ESTADO -->
                        <div class="flex items-center justify-between">
                            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $curso->categoria ?? 'Capacitación' }}
                            </span>
                            
                            @if($esCompletado)
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                                    ✓ Aprobado
                                </span>
                            @elseif($progreso > 0)
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                    En curso
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                    Pendiente
                                </span>
                            @endif
                        </div>

                        <!-- TÍTULO Y DESCRIPCIÓN -->
                        <div class="space-y-1">
                            <h3 class="font-bold text-neutral-800 text-lg leading-snug group-hover:text-orange-600 transition-colors">
                                {{ $curso->titulo ?? $curso->nombre }}
                            </h3>
                            <p class="text-xs text-neutral-500 line-clamp-2">
                                {{ $curso->descripcion ?? 'Capacitación corporativa obligatoria para tu área laboral.' }}
                            </p>
                        </div>

                        <!-- INFORMACIÓN DE MÓDULOS -->
                        <div class="text-xs font-semibold text-neutral-400 flex items-center gap-2">
                            <span>📚 {{ $curso->modulos_count ?? 0 }} Módulos</span>
                        </div>

                        <!-- BARRA DE PROGRESO INDIVIDUAL -->
                        <div class="space-y-1.5 pt-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-neutral-500">Avance</span>
                                <span class="font-bold text-neutral-800">{{ $progreso }}%</span>
                            </div>
                            <div class="w-full bg-neutral-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progreso }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN DE ACCIÓN -->
                    <div class="p-5 pt-0">
                        <a href="{{ route('alumno.curso', $curso->id) }}" class="w-full py-2.5 px-4 bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2 text-center">
                            @if($esCompletado)
                                Repasar Contenido
                            @elseif($progreso > 0)
                                Continuar Curso →
                            @else
                                Iniciar Capacitación →
                            @endif
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-neutral-200 p-8 space-y-3">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                        📖
                    </div>
                    <h3 class="text-lg font-bold text-neutral-800">Aún no tienes capacitaciones asignadas</h3>
                    <p class="text-xs text-neutral-400 max-w-md mx-auto">
                        Cuando el equipo de RRHH o Administración te asigne un curso, aparecerá automáticamente en esta sección.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
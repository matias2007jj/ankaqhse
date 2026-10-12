@extends('plantillas.aplicacion')

@section('titulo', 'Portal de Capacitaciones - CapacitaRed')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/capacitaciones.css') }}">
@endpush

@section('navegacion')
    <nav class="hidden md:flex items-center space-x-1">
        <button onclick="switchTab('my-courses')" id="tab-my-courses" class="boton-pestana boton-pestana-activo">
            <i data-lucide="book-open" class="w-4 h-4"></i> Mis Cursos
        </button>
        <button onclick="switchTab('catalog')" id="tab-catalog" class="boton-pestana boton-pestana-inactivo">
            <i data-lucide="layout-grid" class="w-4 h-4"></i> Explorar Cursos
        </button>

        <button onclick="switchTab('certificates')" id="tab-certificates" class="boton-pestana boton-pestana-inactivo">
            <i data-lucide="award" class="w-4 h-4"></i> Certificados
        </button>
    </nav>
@endsection

@section('contenido')

    <!-- SECCIÓN 1: MIS CURSOS -->
    <section id="view-my-courses" class="tab-content space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="tarjeta-metrica">
                <div class="icono-metrica icono-metrica-naranja">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="titulo-metrica">Cursos Activos</p>
                    <p class="valor-metrica" id="stat-active">0</p>
                </div>
            </div>

            <div class="tarjeta-metrica">
                <div class="icono-metrica icono-metrica-rojo">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="titulo-metrica">Completados</p>
                    <p class="valor-metrica" id="stat-completed">0</p>
                </div>
            </div>

            <div class="tarjeta-metrica">
                <div class="icono-metrica icono-metrica-ambar">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="titulo-metrica">Certificados</p>
                    <p class="valor-metrica" id="stat-certs">0</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Mis Capacitaciones</h1>
                <p class="text-neutral-500 text-sm">Continúa con tus cursos asignados para completar tu plan anual</p>
            </div>
        </div>

        <div id="my-courses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <!-- SECCIÓN 2: CATÁLOGO DE CURSOS -->
    <section id="view-catalog" class="tab-content space-y-6 hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Catálogo de Cursos</h1>
                <p class="text-neutral-500 text-sm">Explora las capacitaciones disponibles para la empresa</p>
            </div>
            <div class="contenedor-buscador">
                <i data-lucide="search" class="icono-buscador"></i>
                <input type="text" id="catalog-search" oninput="filterCatalog()" placeholder="Buscar curso..." class="input-buscador" />
            </div>
        </div>

        <div id="catalog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <!-- SECCIÓN 3: CERTIFICADOS -->
    <section id="view-certificates" class="tab-content space-y-6 hidden">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Mis Certificados</h1>
            <p class="text-neutral-500 text-sm">Descarga tus constancias de aprobación oficiales</p>
        </div>

        <div id="certificates-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    @include('capacitaciones.modales')

@endsection

@push('scripts')
    <div id="datos-capacitaciones" data-cursos="{{ json_encode($cursos) }}"></div>

    <script>
        const contenedor = document.getElementById('datos-capacitaciones');
        window.courses = JSON.parse(contenedor.dataset.cursos);
    </script>
    <script src="{{ asset('js/capacitaciones.js') }}"></script>
@endpush
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'ANKA ACADEMY - Plataforma de Capacitaciones')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('estilos')
</head>
<body class="bg-neutral-50 text-neutral-800 min-h-screen flex flex-col">

    <!-- CABECERA -->
    <header class="bg-white border-b border-neutral-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- LOGO CORPORATIVO -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Anka Academy" class="h-10 w-auto object-contain">
                    <div>
                        <span class="font-bold text-xl tracking-tight text-neutral-900">ANKA<span class="text-orange-600">ACADEMY</span></span>
                    </div>
                </div>

                <!-- MENU DE NAVEGACIÓN -->
                @yield('navegacion')

                <!-- PERFIL DE USUARIO CON DESPLEGABLE -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" id="user-menu-button" class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-neutral-100 transition-all focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-neutral-800 leading-tight">
                                {{ Auth::user()->name ?? 'Ana Martínez' }}
                            </p>
                            <p class="text-xs text-neutral-500">Analista de Procesos</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold flex items-center justify-center text-sm shadow-sm ring-2 ring-orange-100">
                            {{ strtoupper(substr(Auth::user()->name ?? 'AM', 0, 2)) }}
                        </div>
                        <i data-lucide="chevron-down" id="user-menu-chevron" class="w-4 h-4 text-neutral-400 transition-transform duration-200"></i>
                    </button>

                    <!-- MENÚ DESPLEGABLE -->
                    <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-neutral-200 py-2 hidden z-50">
                        <div class="px-4 py-2.5 border-b border-neutral-100">
                            <p class="text-xs font-bold text-neutral-800">{{ Auth::user()->name ?? 'Ana Martínez' }}</p>
                            <p class="text-[11px] text-neutral-400 truncate">{{ Auth::user()->email ?? 'usuario@empresa.com' }}</p>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2.5 transition-all">
                                <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- NOTIFICACIÓN TOAST -->
    <div id="toast" class="fixed top-20 right-5 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-neutral-900 text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3 border border-neutral-700 text-sm">
            <div id="toast-icon" class="text-orange-400">
                <i data-lucide="info" class="w-5 h-5"></i>
            </div>
            <p id="toast-message" class="font-medium">Mensaje de notificación</p>
        </div>
    </div>

    <!-- CONTENIDO -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        @yield('contenido')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-neutral-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-neutral-500">
            <p>© {{ date('Y') }} ANKA ACADEMY - Sistema de Gestión de Formación Empresarial. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            const chevron = document.getElementById('user-menu-chevron');
            if (menu) menu.classList.toggle('hidden');
            if (chevron) chevron.classList.toggle('rotate-180');
        }

        document.addEventListener('click', (event) => {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-dropdown-menu');
            const chevron = document.getElementById('user-menu-chevron');

            if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
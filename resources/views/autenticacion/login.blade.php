<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión - CapacitaRed</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="bg-neutral-100 min-h-screen flex items-center justify-center p-4">

    <div class="tarjeta-login">
        
        <div class="encabezado-login">
            <div class="icono-marca-login">
                <i data-lucide="graduation-cap" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-neutral-900 tracking-tight">Capacita<span class="text-orange-600">Red</span></h1>
            <p class="text-xs text-neutral-500 mt-1">Portal de Formación Empresarial</p>
        </div>

        <div class="cuerpo-login space-y-6">
            <div class="text-center">
                <h2 class="text-lg font-bold text-neutral-800">Bienvenido de nuevo</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Ingresa tus credenciales para acceder a tus capacitaciones</p>
            </div>

            @if ($errors->any())
                <div class="alerta-error-login space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 text-red-500"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.autenticar') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="email" class="etiqueta-campo">Correo Corporativo</label>
                    <div class="contenedor-input-relativo">
                        <i data-lucide="mail" class="icono-campo-input"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="usuario@empresa.com" class="input-login">
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="etiqueta-campo">Contraseña</label>
                    <div class="contenedor-input-relativo">
                        <i data-lucide="lock" class="icono-campo-input"></i>
                        <input type="password" name="password" id="password" required placeholder="••••••••" class="input-login">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-neutral-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 accent-orange-600 rounded cursor-pointer">
                        <span>Recordar mi sesión</span>
                    </label>
                </div>

                <button type="submit" class="boton-iniciar-sesion">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Iniciar Sesión
                </button>
            </form>
        </div>

        <div class="pie-login">
            © {{ date('Y') }} CapacitaRed • Sistema Seguro de Capacitación
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
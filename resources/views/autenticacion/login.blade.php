<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión - ANKA ACADEMY</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<main class="pagina-login">
    <div class="contenedor-login">

        <!-- ============ LADO IZQUIERDO: FORMULARIO ============ -->
        <section class="panel-formulario">
            <div class="marca">
                <span class="marca-icono"><i data-lucide="flame"></i></span>
                <span class="marca-nombre">ANKA<b>ACADEMY</b></span>
            </div>

            <h1 class="titulo-login">Bienvenido a<br> ANKA ACADEMY<br></h1>
            <p class="subtitulo-login"></p>

            @if ($errors->any())
                <div class="alerta-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <div class="alerta-linea">
                            <i data-lucide="alert-circle"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.autenticar') }}" method="POST" class="formulario">
                @csrf

                <div class="campo">
                    <label for="email">Correo corporativo</label>
                    <div class="campo-caja">
                        <i data-lucide="mail" class="campo-icono"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               autocomplete="email" placeholder="usuario@empresa.com">
                    </div>
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <div class="campo-caja">
                        <i data-lucide="lock" class="campo-icono"></i>
                        <input type="password" name="password" id="password" required
                               autocomplete="current-password" placeholder="••••••••">
                        <button type="button" class="ver-clave" id="ver-clave" aria-label="Mostrar contraseña">
                            <i data-lucide="eye" id="icono-ver"></i>
                            <i data-lucide="eye-off" id="icono-ocultar" style="display:none"></i>
                        </button>
                    </div>
                </div>

                <label class="recordar">
                    <input type="checkbox" name="remember">
                    <span>Recordar mi sesión</span>
                </label>

                <button type="submit" class="boton-principal">
                    <i data-lucide="log-in"></i> Iniciar sesión
                </button>
            </form>

        </section>

        <!-- ============ LADO DERECHO: ILUSTRACIÓN ============ -->
        <aside class="panel-visual" aria-hidden="true">
            <div class="resplandor"></div>
            <div class="anillo anillo-1"></div>
            <div class="anillo anillo-2"></div>

            <svg class="extintor" viewBox="0 0 300 440" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gCuerpo" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#991B1B"/>
                        <stop offset=".35" stop-color="#EF4444"/>
                        <stop offset=".6" stop-color="#DC2626"/>
                        <stop offset="1" stop-color="#7F1D1D"/>
                    </linearGradient>
                    <linearGradient id="gMetal" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#262626"/>
                        <stop offset=".4" stop-color="#525252"/>
                        <stop offset="1" stop-color="#171717"/>
                    </linearGradient>
                    <linearGradient id="gNaranja" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#FDBA74"/>
                        <stop offset="1" stop-color="#EA580C"/>
                    </linearGradient>
                    <radialGradient id="gSombra" cx=".5" cy=".5" r=".5">
                        <stop offset="0" stop-color="#7C2D12" stop-opacity=".35"/>
                        <stop offset="1" stop-color="#7C2D12" stop-opacity="0"/>
                    </radialGradient>
                </defs>

                <!-- sombra en el piso -->
                <ellipse cx="160" cy="418" rx="110" ry="16" fill="url(#gSombra)"/>

                <!-- manguera -->
                <path d="M112 96 C58 96 44 150 60 214" fill="none" stroke="url(#gMetal)" stroke-width="9" stroke-linecap="round"/>
                <rect x="50" y="208" width="22" height="34" rx="6" fill="url(#gMetal)" transform="rotate(-8 61 225)"/>

                <!-- cuerpo -->
                <rect x="92" y="132" width="136" height="276" rx="52" fill="url(#gCuerpo)"/>
                <rect x="110" y="150" width="14" height="236" rx="7" fill="#fff" opacity=".18"/>
                <rect x="92" y="352" width="136" height="14" fill="#991B1B" opacity=".55"/>
                <rect x="92" y="150" width="136" height="12" fill="#F97316" opacity=".95"/>

                <!-- cuello y válvula -->
                <rect x="132" y="104" width="56" height="40" rx="8" fill="url(#gMetal)"/>
                <rect x="112" y="76" width="96" height="34" rx="12" fill="url(#gMetal)"/>

                <!-- palanca -->
                <path d="M200 84 L262 104 L258 118 L196 100 Z" fill="url(#gNaranja)"/>
                <path d="M112 84 L68 70 L72 58 L116 72 Z" fill="url(#gNaranja)"/>

                <!-- manómetro -->
                <circle cx="160" cy="62" r="20" fill="#fff" stroke="#262626" stroke-width="5"/>
                <path d="M146 68 A15 15 0 0 1 174 68" fill="none" stroke="#16A34A" stroke-width="4"/>
                <path d="M160 62 L170 52" stroke="#DC2626" stroke-width="3" stroke-linecap="round"/>
                <circle cx="160" cy="62" r="3" fill="#262626"/>

                <!-- etiqueta -->
                <rect x="108" y="196" width="104" height="128" rx="10" fill="#fff"/>
                <path d="M160 208 C146 228 140 242 148 258 C153 267 167 267 172 258 C180 242 173 228 160 208 Z" fill="#F97316"/>
                <path d="M160 232 C154 242 153 250 157 256 C160 260 165 260 167 256 C170 250 167 242 160 232 Z" fill="#FDE68A"/>
                <text x="160" y="292" text-anchor="middle" font-family="Barlow, Arial, sans-serif" font-weight="700" font-size="20" fill="#DC2626">ANKA FIRE</text>
                <rect x="124" y="302" width="72" height="5" rx="2.5" fill="#D4D4D4"/>
                <rect x="134" y="312" width="52" height="5" rx="2.5" fill="#E5E5E5"/>
            </svg>

            <div class="chips">
                <span class="chip"><i data-lucide="shield-check"></i> Uso correcto de extintores</span>
                <span class="chip"><i data-lucide="users"></i> Brigadas de emergencia</span>
            </div>

            <p class="lema">Prepárate para actuar.<br><b>Capacítate para proteger.</b></p>
            <div class="franja"></div>
        </aside>

    </div>
</main>

<script>
    lucide.createIcons();

    // Mostrar u ocultar la contraseña
    const clave = document.getElementById('password');
    const boton = document.getElementById('ver-clave');
    boton.addEventListener('click', () => {
        const visible = clave.type === 'text';
        clave.type = visible ? 'password' : 'text';
        document.getElementById('icono-ver').style.display = visible ? '' : 'none';
        document.getElementById('icono-ocultar').style.display = visible ? 'none' : '';
        boton.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
    });
</script>
</body>
</html>
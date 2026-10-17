<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $user->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Montserrat:wght@500;700&display=swap');

        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Montserrat', 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .contenedor {
            position: relative;
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path('img/plantilla_certificado.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
        }

        /* TITULO OTORGADO A */
        .otorgado-a {
            position: absolute;
            top: 210px;
            left: 5%;
            right: 5%;
            font-size: 18px;
            font-weight: 700;
            color: #b8860b;
            letter-spacing: 1.5px;
        }

        /* NOMBRE DEL ALUMNO (Dancing Script 68px) */
        .nombre-alumno {
            position: absolute;
            top: 235px;
            left: 5%;
            right: 5%;
            font-family: 'Dancing Script', 'Georgia', cursive;
            font-size: 68px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1;
        }

        /* DNI CENTRAL (Reducido a 20.2px) */
        .dni-central {
            position: absolute;
            top: 320px;
            left: 5%;
            right: 5%;
            font-size: 20.2px;
            font-weight: 700;
            color: #222222;
            letter-spacing: 0.5px;
        }

        /* PARRAFO PRINCIPAL (Reducido de 23.2px a 20.2px) */
        .bloque-texto {
            position: absolute;
            top: 355px;
            left: 8%;
            right: 8%;
            font-size: 20.2px;
            line-height: 1.55;
            font-weight: 700;
            color: #222222;
            text-align: center;
            text-transform: uppercase;
        }

        /* DNI EN ESQUINA INFERIOR DERECHA */
        .dni-inferior-derecho {
            position: absolute;
            bottom: 35px;
            right: 90px;
            font-size: 15px;
            font-weight: 700;
            color: #222222;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        
        <div class="otorgado-a">
            OTORGADO A
        </div>

        <div class="nombre-alumno">
            {{ ucwords(mb_strtolower($user->name)) }}
        </div>

        <!-- DNI CENTRAL -->
        <div class="dni-central">
            IDENTIFICADO(A) CON DNI {{ $dniAlumno }}
        </div>

        <!-- PÁRRAFO DINÁMICO CENTRAL -->
        <div class="bloque-texto">
            REALIZADO EL {{ mb_strtoupper($fechaEmision) }}, POR SU PARTICIPACIÓN EN EL CURSO TEÓRICO-PRÁCTICO DE “{{ mb_strtoupper($curso->titulo) }}”, CON UNA DURACIÓN DE {{ sprintf('%02d', $curso->duracion_horas ?? 2) }} HORAS, SE EXPIDE EL SIGUIENTE CERTIFICADO PARA LOS FINES QUE ESTIME CONVENIENTE EL INTERESADO.
        </div>

        <!-- DNI / CÓDIGO INFERIOR DERECHO -->
        <div class="dni-inferior-derecho">
            {{ $dniAlumno ?: $codigoCertificado }}
        </div>

    </div>
</body>
</html>
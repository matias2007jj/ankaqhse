/**
 * Wizard "Crear Nuevo Curso" (solo operador)
 * Paso 1: datos  ->  Paso 2: módulos + un video por módulo  ->  Paso 3: evaluación
 */
(function () {
    var MAX_VIDEO_MB = 200;
    var MAX_MODULOS = 20;
    var TOTAL_PASOS = 3;
    var pasoActual = 1;

    function el(id) { return document.getElementById(id); }

    function iconos() {
        if (window.lucide) window.lucide.createIcons();
    }

    // ---------- Abrir / cerrar ----------
    window.abrirModalCrearCurso = function () {
        var modal = el('modal-crear-curso');
        if (!modal) return;
        modal.classList.remove('hidden');
        irAPaso(pasoActual);
        iconos();
    };

    window.cerrarModalCrearCurso = function () {
        var modal = el('modal-crear-curso');
        if (modal) modal.classList.add('hidden');
    };

    // ---------- Pasos ----------
    function irAPaso(n) {
        pasoActual = n;

        for (var i = 1; i <= TOTAL_PASOS; i++) {
            var pane = el('paso-' + i);
            if (pane) pane.classList.toggle('hidden', i !== n);
        }

        document.querySelectorAll('.indicador-paso').forEach(function (ind) {
            var num = parseInt(ind.getAttribute('data-paso'), 10);
            var bolita = ind.querySelector('span');
            var activo = num <= n;
            bolita.classList.toggle('bg-orange-600', activo);
            bolita.classList.toggle('text-white', activo);
            bolita.classList.toggle('bg-neutral-200', !activo);
            bolita.classList.toggle('text-neutral-500', !activo);
            ind.classList.toggle('text-neutral-800', num === n);
            ind.classList.toggle('text-neutral-400', num !== n);
        });

        el('btn-anterior').classList.toggle('hidden', n === 1);
        el('btn-siguiente').classList.toggle('hidden', n === TOTAL_PASOS);
        el('btn-guardar-curso').classList.toggle('hidden', n !== TOTAL_PASOS);

        var cuerpo = el('cuerpo-crear-curso');
        if (cuerpo) cuerpo.scrollTop = 0;
    }

    function campos(n) {
        return el('paso-' + n).querySelectorAll('input, textarea, select');
    }

    // Solo comprueba (sin mostrar tooltip)
    function pasoValido(n) {
        var lista = campos(n);
        for (var i = 0; i < lista.length; i++) {
            if (!lista[i].checkValidity()) return false;
        }
        return true;
    }

    // Muestra el tooltip del primer campo inválido del paso visible
    function validarPaso(n) {
        var lista = campos(n);
        for (var i = 0; i < lista.length; i++) {
            if (!lista[i].checkValidity()) {
                lista[i].reportValidity();
                return false;
            }
        }
        return true;
    }

    // ---------- Módulos ----------
    function crearModulo(i) {
        var div = document.createElement('div');
        div.className = 'modulo-bloque border border-neutral-200 rounded-xl p-4 space-y-3 bg-neutral-50';
        div.innerHTML =
            '<span class="modulo-num text-xs font-bold text-orange-600 uppercase">Módulo ' + (i + 1) + '</span>' +
            '<input type="text" name="modulos[' + i + '][titulo]" required maxlength="255" placeholder="Título del módulo ' + (i + 1) + '" ' +
                'class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:border-orange-500">' +
            '<div>' +
                '<label class="block text-[11px] font-bold text-neutral-500 uppercase mb-1">Video del módulo</label>' +
                '<input type="file" name="modulos[' + i + '][video]" required accept="video/mp4,video/webm,video/quicktime" ' +
                    'class="input-video w-full text-xs text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer border border-neutral-200 rounded-xl p-1 bg-white">' +
            '</div>';
        return div;
    }

    function moduloTieneContenido(bloque) {
        var titulo = bloque.querySelector('input[type="text"]').value.trim();
        var video = bloque.querySelector('input[type="file"]').files.length > 0;
        return titulo !== '' || video;
    }

    function actualizarModulos() {
        var input = el('cantidad-modulos');
        var cont = el('contenedor-modulos');
        var n = parseInt(input.value, 10);

        if (isNaN(n) || n < 1) n = 1;
        if (n > MAX_MODULOS) n = MAX_MODULOS;

        var actuales = cont.querySelectorAll('.modulo-bloque').length;

        // Si va a quitar módulos que ya tienen datos, pedir confirmación
        if (n < actuales) {
            var bloques = cont.querySelectorAll('.modulo-bloque');
            var conDatos = false;
            for (var k = n; k < actuales; k++) {
                if (moduloTieneContenido(bloques[k])) conDatos = true;
            }
            if (conDatos && !window.confirm('Vas a quitar módulos que ya tienen título o video. ¿Continuar?')) {
                input.value = actuales;
                return;
            }
        }

        input.value = n;
        while (actuales < n) { cont.appendChild(crearModulo(actuales)); actuales++; }
        while (actuales > n) { cont.removeChild(cont.lastElementChild); actuales--; }
    }

    // Validar tamaño del video apenas se elige
    function alElegirVideo(e) {
        var input = e.target;
        if (!input.classList.contains('input-video') || !input.files.length) return;

        var mb = input.files[0].size / (1024 * 1024);
        if (mb > MAX_VIDEO_MB) {
            window.alert('El video "' + input.files[0].name + '" pesa ' + mb.toFixed(0) + ' MB. El máximo permitido es ' + MAX_VIDEO_MB + ' MB.');
            input.value = '';
        }
    }

    // ---------- Preguntas ----------
    var LETRAS = ['A', 'B', 'C', 'D'];

    function crearPregunta(i) {
        var div = document.createElement('div');
        div.className = 'pregunta-bloque border border-neutral-200 rounded-xl p-4 space-y-3 bg-neutral-50';

        var opciones = '';
        for (var o = 0; o < 4; o++) {
            opciones +=
                '<label class="flex items-center gap-2">' +
                    '<input type="radio" name="preguntas[' + i + '][correcta]" value="' + o + '" required class="accent-orange-600 cursor-pointer" title="Marcar como correcta">' +
                    '<input type="text" name="preguntas[' + i + '][opciones][' + o + ']" maxlength="255" ' + (o < 2 ? 'required ' : '') +
                        'placeholder="Opción ' + LETRAS[o] + (o < 2 ? '' : ' (opcional)') + '" ' +
                        'class="flex-1 border border-neutral-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:border-orange-500">' +
                '</label>';
        }

        div.innerHTML =
            '<div class="flex justify-between items-center">' +
                '<span class="pregunta-num text-xs font-bold text-orange-600 uppercase">Pregunta ' + (i + 1) + '</span>' +
                '<button type="button" class="btn-quitar-pregunta text-xs font-semibold text-red-500 hover:text-red-700 cursor-pointer">Quitar</button>' +
            '</div>' +
            '<input type="text" name="preguntas[' + i + '][texto]" required placeholder="Escribe el enunciado de la pregunta" ' +
                'class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:border-orange-500">' +
            '<div class="space-y-2">' + opciones + '</div>' +
            '<p class="text-[11px] text-neutral-400">Marca el círculo de la respuesta correcta.</p>';
        return div;
    }

    function renumerarPreguntas() {
        el('contenedor-preguntas').querySelectorAll('.pregunta-bloque').forEach(function (bloque, i) {
            bloque.querySelector('.pregunta-num').textContent = 'Pregunta ' + (i + 1);
            bloque.querySelectorAll('[name]').forEach(function (campo) {
                campo.name = campo.name.replace(/^preguntas\[\d+\]/, 'preguntas[' + i + ']');
            });
        });
    }

    function agregarPregunta() {
        var cont = el('contenedor-preguntas');
        cont.appendChild(crearPregunta(cont.querySelectorAll('.pregunta-bloque').length));
        renumerarPreguntas();
    }

    // La opción marcada como correcta no puede estar vacía
    function correctasConTexto() {
        var bloques = el('contenedor-preguntas').querySelectorAll('.pregunta-bloque');
        for (var i = 0; i < bloques.length; i++) {
            var marcada = bloques[i].querySelector('input[type="radio"]:checked');
            if (!marcada) continue;
            var texto = bloques[i].querySelector('input[name$="[opciones][' + marcada.value + ']"]');
            if (texto && texto.value.trim() === '') {
                window.alert('En la pregunta ' + (i + 1) + ' marcaste como correcta una opción vacía.');
                return false;
            }
        }
        return true;
    }

    // ---------- Inicio ----------
    document.addEventListener('DOMContentLoaded', function () {
        var form = el('form-crear-curso');
        if (!form) return; // el usuario no es operador

        actualizarModulos();
        agregarPregunta();
        iconos();

        el('cantidad-modulos').addEventListener('change', actualizarModulos);
        el('contenedor-modulos').addEventListener('change', alElegirVideo);
        el('btn-agregar-pregunta').addEventListener('click', agregarPregunta);

        el('contenedor-preguntas').addEventListener('click', function (e) {
            if (!e.target.classList.contains('btn-quitar-pregunta')) return;
            var cont = el('contenedor-preguntas');
            if (cont.querySelectorAll('.pregunta-bloque').length <= 1) {
                window.alert('La evaluación debe tener al menos una pregunta.');
                return;
            }
            e.target.closest('.pregunta-bloque').remove();
            renumerarPreguntas();
        });

        el('btn-anterior').addEventListener('click', function () { irAPaso(pasoActual - 1); });
        el('btn-siguiente').addEventListener('click', function () {
            if (validarPaso(pasoActual)) irAPaso(pasoActual + 1);
        });

        form.addEventListener('submit', function (e) {
            // Enter en pasos 1-2: avanzar en vez de enviar
            if (pasoActual < TOTAL_PASOS) {
                e.preventDefault();
                if (validarPaso(pasoActual)) irAPaso(pasoActual + 1);
                return;
            }

            // Paso final: revisar todos los pasos
            for (var p = 1; p <= TOTAL_PASOS; p++) {
                if (!pasoValido(p)) {
                    e.preventDefault();
                    irAPaso(p);
                    validarPaso(p);
                    return;
                }
            }
            if (!correctasConTexto()) {
                e.preventDefault();
                return;
            }

            // Subida en curso (los videos pueden tardar)
            var btn = el('btn-guardar-curso');
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            btn.textContent = 'Subiendo videos… no cierres esta ventana';
        });
    });
})();

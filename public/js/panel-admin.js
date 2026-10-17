// Funciones del panel de administración (pestañas, asignar, registrar usuario, editar/eliminar curso y módulos)

window.switchAdminTab = function (tabId) {
    document.querySelectorAll('.tab-pane-admin').forEach(function (pane) {
        pane.classList.add('hidden');
    });

    document.querySelectorAll('.tab-admin-btn').forEach(function (btn) {
        btn.classList.remove('border-orange-500', 'font-bold', 'text-orange-600');
        btn.classList.add('border-transparent', 'font-medium', 'text-neutral-500');
    });

    var pane = document.getElementById(tabId);
    if (pane) pane.classList.remove('hidden');

    var key = tabId.replace('metrica-general', 'metrica')
        .replace('monitoreo-empleados', 'monitoreo')
        .replace('gestion-cursos', 'cursos');

    var btnActivo = document.getElementById('btn-tab-' + key);
    if (btnActivo) {
        btnActivo.classList.add('border-orange-500', 'font-bold', 'text-orange-600');
        btnActivo.classList.remove('border-transparent', 'font-medium', 'text-neutral-500');
    }
};

// REGISTRAR NUEVO COLABORADOR
window.abrirModalCrearUsuario = function () {
    var modal = document.getElementById('modal-crear-usuario');
    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalCrearUsuario = function () {
    var modal = document.getElementById('modal-crear-usuario');
    if (modal) modal.classList.add('hidden');
};

// Recibe el botón; los datos vienen en data-id, data-nombre y data-cursos
window.abrirModalAsignar = function (btn) {
    var userId = btn.dataset.id;
    var userName = btn.dataset.nombre;
    var asignados = [];
    try { asignados = JSON.parse(btn.dataset.cursos || '[]'); } catch (e) { asignados = []; }

    var inputId = document.getElementById('modal-user-id');
    var inputName = document.getElementById('modal-user-name');
    var modal = document.getElementById('modal-asignar-curso');
    var select = document.getElementById('modal-curso-id');

    if (inputId) inputId.value = userId;
    if (inputName) inputName.value = userName;

    if (select) {
        select.value = '';
        select.querySelectorAll('option').forEach(function (option) {
            if (!option.value) return;
            var ya = asignados.indexOf(parseInt(option.value, 10)) !== -1;
            option.style.display = ya ? 'none' : 'block';
            option.disabled = ya;
        });
    }

    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalAsignar = function () {
    var modal = document.getElementById('modal-asignar-curso');
    if (modal) modal.classList.add('hidden');
};

// Recibe el botón; el curso viene en data-curso (JSON)
window.abrirModalEditarCurso = function (btn) {
    var modal = document.getElementById('modal-editar-curso');
    var curso = null;
    try { curso = JSON.parse(btn.dataset.curso); } catch (e) { return; }
    if (!modal || !curso) return;

    document.getElementById('edit-curso-id').value = curso.id;
    document.getElementById('edit-curso-titulo').value = curso.titulo || curso.nombre || '';
    document.getElementById('edit-curso-categoria').value = curso.categoria || '';
    document.getElementById('edit-curso-descripcion').value = curso.descripcion || '';

    modal.classList.remove('hidden');
};

window.cerrarModalEditarCurso = function () {
    var modal = document.getElementById('modal-editar-curso');
    if (modal) modal.classList.add('hidden');
};

// GESTIÓN DE MÓDULOS EN EL MODAL
window.abrirModalVerModulos = function (btn) {
    var modal = document.getElementById('modal-ver-modulos');
    var curso = null;
    try { curso = JSON.parse(btn.dataset.curso); } catch (e) { return; }
    if (!modal || !curso) return;

    document.getElementById('modulos-curso-titulo').innerText = curso.titulo || 'Módulos del Curso';
    document.getElementById('modulos-curso-categoria').innerText = curso.categoria || 'General';

    var inputNuevoMod = document.getElementById('nuevo-modulo-curso-id');
    if (inputNuevoMod) inputNuevoMod.value = curso.id;

    var container = document.getElementById('lista-modulos-container');
    container.innerHTML = '';

    if (curso.modulos && curso.modulos.length > 0) {
        curso.modulos.forEach(function (mod, index) {
            var pathVideo = mod.video_url || mod.video || '';
            var esUrlExterna = pathVideo.startsWith('http://') || pathVideo.startsWith('https://');
            var srcVideo = esUrlExterna ? pathVideo : '/' + pathVideo;

            var html = `
                <div class="p-3.5 bg-neutral-50 border border-neutral-200 rounded-xl space-y-2">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <h4 class="font-bold text-xs text-neutral-800">${index + 1}. ${mod.titulo}</h4>
                            <p class="text-[11px] text-neutral-500">${mod.descripcion || 'Sin descripción'}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" onclick='abrirModalEditarModulo(${JSON.stringify(mod)})' class="text-xs font-bold text-orange-600 hover:text-orange-700 bg-orange-50 px-2 py-1 rounded-md cursor-pointer">Editar</button>
                            <button type="button" onclick="eliminarModulo(${mod.id})" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 px-2 py-1 rounded-md cursor-pointer">Eliminar</button>
                        </div>
                    </div>
                    ${pathVideo ? `
                        <div class="mt-2 rounded-lg overflow-hidden bg-black">
                            <video controls class="w-full max-h-48">
                                <source src="${srcVideo}">
                                Tu navegador no soporta el reproductor de vídeo.
                            </video>
                        </div>
                    ` : '<span class="text-[10px] text-amber-600 font-semibold block">Sin vídeo subido</span>'}
                </div>
            `;
            container.innerHTML += html;
        });
    } else {
        container.innerHTML = '<p class="text-xs text-neutral-400 text-center py-6">Este curso aún no cuenta con módulos registrados.</p>';
    }

    modal.classList.remove('hidden');
};

window.cerrarModalVerModulos = function () {
    var modal = document.getElementById('modal-ver-modulos');
    if (modal) modal.classList.add('hidden');
};

window.abrirModalEditarModulo = function (mod) {
    document.getElementById('edit-modulo-id').value = mod.id;
    document.getElementById('edit-modulo-titulo').value = mod.titulo || '';
    document.getElementById('edit-modulo-descripcion').value = mod.descripcion || '';
    document.getElementById('modal-editar-modulo').classList.remove('hidden');
};

window.cerrarModalEditarModulo = function () {
    document.getElementById('modal-editar-modulo').classList.add('hidden');
};

window.eliminarModulo = function (moduloId) {
    if (confirm('¿Estás seguro de eliminar este módulo?')) {
        document.getElementById('eliminar-modulo-id').value = moduloId;
        document.getElementById('form-eliminar-modulo').submit();
    }
};

// ELIMINAR CURSO (MÉTODO SEGURO VÍA DATA-ATTRIBUTES)
window.abrirModalEliminarCurso = function (btn) {
    var id = btn.dataset.id;
    var titulo = btn.dataset.titulo;

    var inputId = document.getElementById('eliminar-curso-id');
    var txtTitulo = document.getElementById('eliminar-curso-titulo');
    var modal = document.getElementById('modal-eliminar-curso');

    if (inputId) inputId.value = id;
    if (txtTitulo) txtTitulo.innerText = titulo;
    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalEliminarCurso = function () {
    var modal = document.getElementById('modal-eliminar-curso');
    if (modal) modal.classList.add('hidden');
};

document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) window.lucide.createIcons();

    // Si venimos de guardar algo (éxito, aviso o errores), volver a "Gestión de Cursos"
    var panel = document.getElementById('panel-admin');
    if (panel && panel.dataset.alerta === '1') {
        window.switchAdminTab('gestion-cursos');
    }
});
// --- EDITAR COLABORADOR ---
window.abrirModalEditarUsuario = function (btn) {
    var userId = btn.dataset.id;
    var userName = btn.dataset.nombre;
    var userDni = btn.dataset.dni;

    var inputId = document.getElementById('edit-user-id');
    var inputName = document.getElementById('edit-user-name');
    var inputDni = document.getElementById('edit-user-dni');
    var inputPass = document.getElementById('edit-user-password');
    var modal = document.getElementById('modal-editar-usuario');

    if (inputId) inputId.value = userId;
    if (inputName) inputName.value = userName || '';
    if (inputDni) inputDni.value = userDni || '';
    if (inputPass) inputPass.value = ''; // Limpiar campo de contraseña

    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalEditarUsuario = function () {
    var modal = document.getElementById('modal-editar-usuario');
    if (modal) modal.classList.add('hidden');
};
// --- ELIMINAR COLABORADOR ---
window.abrirModalEliminarUsuario = function (btn) {
    var userId = btn.dataset.id;
    var userName = btn.dataset.nombre;

    var inputId = document.getElementById('eliminar-usuario-id');
    var txtNombre = document.getElementById('eliminar-usuario-nombre');
    var modal = document.getElementById('modal-eliminar-usuario');

    if (inputId) inputId.value = userId;
    if (txtNombre) txtNombre.textContent = userName || 'este colaborador';

    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalEliminarUsuario = function () {
    var modal = document.getElementById('modal-eliminar-usuario');
    if (modal) modal.classList.add('hidden');
};
// Funciones del panel de administración (pestañas, asignar y editar curso)

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

document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) window.lucide.createIcons();

    // Si venimos de guardar algo (éxito, aviso o errores), volver a "Gestión de Cursos"
    var panel = document.getElementById('panel-admin');
    if (panel && panel.dataset.alerta === '1') {
        window.switchAdminTab('gestion-cursos');
    }
});

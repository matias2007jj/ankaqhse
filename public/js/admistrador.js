// Funciones expuestas globalmente en window
window.switchAdminTab = function(tabId) {
    // 1. Ocultar todos los paneles
    document.querySelectorAll('.tab-pane-admin').forEach(function(pane) {
        pane.classList.add('hidden');
    });

    // 2. Resetear estilos de botones
    document.querySelectorAll('.tab-admin-btn').forEach(function(btn) {
        btn.classList.remove('border-orange-500', 'font-bold', 'text-orange-600');
        btn.classList.add('border-transparent', 'font-medium', 'text-neutral-500');
    });

    // 3. Mostrar panel seleccionado
    var selectedPane = document.getElementById(tabId);
    if (selectedPane) {
        selectedPane.classList.remove('hidden');
    }

    // 4. Activar pestaña visualmente
    var key = tabId.replace('metrica-general', 'metrica')
                     .replace('monitoreo-empleados', 'monitoreo')
                     .replace('gestion-cursos', 'cursos');

    var activeBtn = document.getElementById('btn-tab-' + key);
    if (activeBtn) {
        activeBtn.classList.add('border-orange-500', 'font-bold', 'text-orange-600');
        activeBtn.classList.remove('border-transparent', 'font-medium', 'text-neutral-500');
    }
};

window.abrirModalAsignar = function(userId, userName) {
    var inputId = document.getElementById('modal-user-id');
    var inputName = document.getElementById('modal-user-name');
    var modal = document.getElementById('modal-asignar-curso');

    if (inputId) inputId.value = userId;
    if (inputName) inputName.value = userName;
    if (modal) modal.classList.remove('hidden');
};

window.cerrarModalAsignar = function() {
    var modal = document.getElementById('modal-asignar-curso');
    if (modal) modal.classList.add('hidden');
};

// Cargar íconos Lucide
document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) {
        window.lucide.createIcons();
    }
});
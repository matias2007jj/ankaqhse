// Cargar iconos de Lucide al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        lucide.createIcons();
    }
});

// Función para cambiar entre pestañas del panel de administración
window.switchAdminTab = function(tabId) {
    document.querySelectorAll('.admin-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.boton-pestana-admin').forEach(btn => {
        btn.classList.remove('boton-pestana-admin-activo');
        btn.classList.add('boton-pestana-admin-inactivo');
    });

    const targetView = document.getElementById(`admin-view-${tabId}`);
    if (targetView) targetView.classList.remove('hidden');

    const activeBtn = document.getElementById(`admin-tab-${tabId}`);
    if (activeBtn) {
        activeBtn.classList.add('boton-pestana-admin-activo');
        activeBtn.classList.remove('boton-pestana-admin-inactivo');
    }
};

// Abrir modal "Crear Nuevo Curso"
window.openModalCrearCurso = function() {
    const modal = document.getElementById('modal-crear-curso');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

// Abrir modal "Asignar Curso"
window.openModalAsignar = function(empId, empNombre) {
    const lblNombre = document.getElementById('nombre-empleado-modal');
    if (lblNombre) lblNombre.innerText = empNombre;

    const modal = document.getElementById('modal-asignar-curso');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

// Cerrar cualquier modal por su ID
window.closeModalAdmin = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
};

// Cerrar modal al hacer clic en el fondo oscuro
document.addEventListener('click', (e) => {
    ['modal-crear-curso', 'modal-asignar-curso'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal && e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
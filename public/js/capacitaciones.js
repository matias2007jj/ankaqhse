let activeCourseId = null;
let selectedExamAnswers = {};

document.addEventListener('DOMContentLoaded', () => {
    if (!window.courses || !Array.isArray(window.courses)) {
        window.courses = [];
    }

    renderMyCourses();
    renderCatalog();
    renderCertificates();
    populateExamSelect();
    updateStats();

    if (window.lucide) {
        lucide.createIcons();
    }
});

function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.boton-pestana').forEach(btn => {
        btn.classList.remove('boton-pestana-activo');
        btn.classList.add('boton-pestana-inactivo');
    });

    const targetView = document.getElementById(`view-${tabId}`);
    if (targetView) targetView.classList.remove('hidden');

    const activeBtn = document.getElementById(`tab-${tabId}`);
    if (activeBtn) {
        activeBtn.classList.add('boton-pestana-activo');
        activeBtn.classList.remove('boton-pestana-inactivo');
    }

    if (tabId === 'certificates') {
        renderCertificates();
    }
}

function getCourseProgress(course) {
    if (!course) return 0;
    if (course.progreso !== undefined) return course.progreso;
    if (course.pivot && course.pivot.progreso !== undefined) return course.pivot.progreso;

    const lessons = course.lessons || course.lecciones || [];
    if (lessons.length === 0) return 0;
    const completed = lessons.filter(l => l.completed || l.completado).length;
    return Math.round((completed / lessons.length) * 100);
}

function updateStats() {
    const courses = window.courses || [];
    
    const activeCount = courses.filter(c => (c.enrolled || c.inscrito || c.pivot) && !c.examPassed).length;
    const completedCount = courses.filter(c => c.examPassed || getCourseProgress(c) === 100).length;

    const elActive = document.getElementById('stat-active');
    const elCompleted = document.getElementById('stat-completed');
    const elCerts = document.getElementById('stat-certs');

    if (elActive) elActive.innerText = activeCount;
    if (elCompleted) elCompleted.innerText = completedCount;
    if (elCerts) elCerts.innerText = completedCount;
}

// ==========================================
// RENDER: MIS CURSOS (TPL: #tpl-curso-card)
// ==========================================
function renderMyCourses() {
    const grid = document.getElementById('my-courses-grid');
    if (!grid) return;

    grid.innerHTML = '';
    const courses = window.courses || [];
    const enrolledCourses = courses.filter(c => c.enrolled || c.inscrito || c.pivot);

    // Estado Vacío
    if (enrolledCourses.length === 0) {
        renderEstadoVacio(grid, 'book-open', 'No tienes capacitaciones asignadas', 'Los cursos asignados por tu empresa aparecerán aquí.');
        return;
    }

    const tplCard = document.getElementById('tpl-curso-card');

    enrolledCourses.forEach(course => {
        const clone = tplCard.content.cloneNode(true);
        const progress = getCourseProgress(course);
        const is100 = progress === 100;
        const totalModulos = (course.lessons || course.modulos || course.lecciones || []).length;
        const titulo = course.title || course.titulo || 'Curso sin título';
        const categoria = course.category || course.categoria || 'General';
        const imagen = course.image || course.imagen || '/img/logo.png';

        clone.querySelector('.img-portada').src = imagen;
        clone.querySelector('.img-portada').alt = titulo;
        clone.querySelector('.badge-categoria').textContent = categoria;
        clone.querySelector('.txt-titulo').textContent = titulo;
        clone.querySelector('.txt-modulos').textContent = `${totalModulos} Módulos interactivos`;
        clone.querySelector('.txt-progreso').textContent = `${progress}%`;
        clone.querySelector('.bar-progreso').style.width = `${progress}%`;

        // Eventos de Botones
        clone.querySelector('.btn-modulos').onclick = () => openCourseModal(course.id);

        const btnPrueba = clone.querySelector('.btn-prueba');
        const iconoPrueba = clone.querySelector('.icono-prueba');

        if (is100) {
            btnPrueba.className = 'btn-prueba bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-1.5';
            btnPrueba.onclick = () => openExamModalForCourse(course.id);
            if (iconoPrueba) iconoPrueba.setAttribute('data-lucide', 'file-text');
        } else {
            btnPrueba.className = 'btn-prueba bg-neutral-100 text-neutral-400 cursor-not-allowed px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-1.5';
            btnPrueba.onclick = () => showToast('Completa los módulos al 100% para iniciar el examen', 'info');
        }

        grid.appendChild(clone);
    });

    if (window.lucide) lucide.createIcons();
}

// ==========================================
// RENDER: CATÁLOGO (TPL: #tpl-catalogo-card)
// ==========================================
function renderCatalog() {
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

    grid.innerHTML = '';
    const courses = window.courses || [];

    if (courses.length === 0) {
        renderEstadoVacio(grid, 'layout-grid', 'No hay cursos disponibles', 'Actualmente no existen programas cargados en el catálogo.');
        return;
    }

    const tplCard = document.getElementById('tpl-catalogo-card');

    courses.forEach(course => {
        const clone = tplCard.content.cloneNode(true);
        const totalModulos = (course.lessons || course.modulos || course.lecciones || []).length;
        const titulo = course.title || course.titulo || 'Curso sin título';
        const categoria = course.category || course.categoria || 'General';
        const imagen = course.image || course.imagen || '/img/logo.png';
        const isInscrito = course.enrolled || course.inscrito || course.pivot;

        clone.querySelector('.img-portada').src = imagen;
        clone.querySelector('.img-portada').alt = titulo;
        clone.querySelector('.badge-categoria').textContent = categoria;
        clone.querySelector('.txt-titulo').textContent = titulo;
        clone.querySelector('.txt-modulos').textContent = `${totalModulos} Módulos lectivos`;

        const contenedorAccion = clone.querySelector('.contenedor-accion');

        if (isInscrito) {
            const divInscrito = document.createElement('div');
            divInscrito.className = 'bg-orange-50 text-orange-700 p-2.5 rounded-xl text-xs font-semibold flex items-center justify-center gap-2';
            divInscrito.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Ya estás inscrito';
            contenedorAccion.appendChild(divInscrito);
        } else {
            const btnInscribirse = document.createElement('button');
            btnInscribirse.className = 'w-full py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold rounded-xl text-sm shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center justify-center gap-2';
            btnInscribirse.onclick = () => enrollCourse(course.id);
            btnInscribirse.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Inscribirse Gratis';
            contenedorAccion.appendChild(btnInscribirse);
        }

        grid.appendChild(clone);
    });

    if (window.lucide) lucide.createIcons();
}

// ===============================================
// RENDER: CERTIFICADOS (TPL: #tpl-certificado-card)
// ===============================================
function renderCertificates() {
    const grid = document.getElementById('certificates-grid');
    if (!grid) return;

    grid.innerHTML = '';
    const courses = window.courses || [];
    const passed = courses.filter(c => c.examPassed || getCourseProgress(c) === 100);

    if (passed.length === 0) {
        renderEstadoVacio(grid, 'award', 'Sin certificados emitidos', 'Completa tus evaluaciones al 100% para obtener tu constancia.');
        return;
    }

    const tplCard = document.getElementById('tpl-certificado-card');

    passed.forEach(c => {
        const clone = tplCard.content.cloneNode(true);
        const titulo = c.title || c.titulo || 'Curso';
        const fecha = c.examDate || new Date().toLocaleDateString('es-ES');

        clone.querySelector('.txt-titulo').textContent = titulo;
        clone.querySelector('.txt-fecha').textContent = `Aprobado el ${fecha}`;
        clone.querySelector('.btn-ver-certificado').onclick = () => openCertificateModal(titulo);

        grid.appendChild(clone);
    });

    if (window.lucide) lucide.createIcons();
}

// HELPER: RENDERIZAR ESTADO VACÍO MEDIANTE PLANTILLA
function renderEstadoVacio(contenedor, icono, titulo, subtitulo) {
    const tplVacio = document.getElementById('tpl-vacio');
    if (!tplVacio) return;

    const clone = tplVacio.content.cloneNode(true);
    const elIcono = clone.querySelector('.icono-vacio');
    if (elIcono) elIcono.setAttribute('data-lucide', icono);

    clone.querySelector('.titulo-vacio').textContent = titulo;
    clone.querySelector('.subtitulo-vacio').textContent = subtitulo;

    contenedor.appendChild(clone);
    if (window.lucide) lucide.createIcons();
}

function enrollCourse(id) {
    const course = (window.courses || []).find(c => c.id === id);
    if (course) {
        course.enrolled = true;
        course.inscrito = true;
        renderMyCourses();
        renderCatalog();
        populateExamSelect();
        updateStats();
        showToast(`Te has inscrito exitosamente en "${course.title || course.titulo}"`, 'success');
    }
}

function openCourseModal(id) {
    activeCourseId = id;
    const course = (window.courses || []).find(c => c.id === id);
    if (!course) return;

    const elTitle = document.getElementById('modal-title');
    const elCat = document.getElementById('modal-category');

    if (elTitle) elTitle.innerText = course.title || course.titulo || 'Curso';
    if (elCat) elCat.innerText = course.category || course.categoria || 'General';

    renderModalLessons(course);
    updateModalProgress(course);

    const modal = document.getElementById('course-modal');
    if (modal) modal.classList.remove('hidden');
}

function renderModalLessons(course) {
    const list = document.getElementById('modal-lessons-list');
    if (!list) return;

    list.innerHTML = '';
    const lessons = course.lessons || course.modulos || course.lecciones || [];

    if (lessons.length === 0) {
        list.innerHTML = `<p class="text-xs text-neutral-400 py-4 text-center">Este curso no contiene módulos registrados actualmente.</p>`;
        return;
    }

    lessons.forEach(lesson => {
        const isComplete = lesson.completed || lesson.completado;
        const item = document.createElement('div');
        item.className = `flex items-center justify-between p-3.5 rounded-xl border ${isComplete ? 'bg-orange-50/50 border-orange-200' : 'bg-white border-neutral-200'} transition-all`;

        const leftDiv = document.createElement('div');
        leftDiv.className = 'flex items-center gap-3';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = isComplete;
        checkbox.className = 'w-5 h-5 accent-orange-600 rounded cursor-pointer';
        checkbox.onchange = () => toggleLesson(course.id, lesson.id);

        const textSpan = document.createElement('span');
        textSpan.className = `text-sm font-medium ${isComplete ? 'line-through text-neutral-400' : 'text-neutral-800'}`;
        textSpan.textContent = lesson.title || lesson.titulo || 'Módulo';

        leftDiv.appendChild(checkbox);
        leftDiv.appendChild(textSpan);

        const statusSpan = document.createElement('span');
        statusSpan.className = `text-xs ${isComplete ? 'text-orange-600 font-semibold' : 'text-neutral-400'}`;
        statusSpan.textContent = isComplete ? 'Completado' : 'Pendiente';

        item.appendChild(leftDiv);
        item.appendChild(statusSpan);
        list.appendChild(item);
    });

    const elActiveTitle = document.getElementById('modal-active-lesson-title');
    if (elActiveTitle && lessons.length > 0) {
        elActiveTitle.innerText = lessons[0].title || lessons[0].titulo || 'Módulo 1';
    }
}

function toggleLesson(courseId, lessonId) {
    const course = (window.courses || []).find(c => c.id === courseId);
    if (!course) return;

    const lessons = course.lessons || course.modulos || course.lecciones || [];
    const lesson = lessons.find(l => l.id === lessonId);
    
    if (lesson) {
        lesson.completed = !lesson.completed;
        lesson.completado = lesson.completed;
        renderModalLessons(course);
        updateModalProgress(course);
        renderMyCourses();
        updateStats();
    }
}

function updateModalProgress(course) {
    const progress = getCourseProgress(course);
    
    const elText = document.getElementById('modal-progress-text');
    const elBar = document.getElementById('modal-progress-bar');

    if (elText) elText.innerText = `${progress}% Completado`;
    if (elBar) elBar.style.width = `${progress}%`;

    const btnExam = document.getElementById('modal-btn-start-exam');
    const iconExam = document.getElementById('modal-btn-exam-icon');
    const textExam = document.getElementById('modal-btn-exam-text');

    if (btnExam) {
        if (progress === 100) {
            btnExam.disabled = false;
            if (iconExam) iconExam.setAttribute('data-lucide', 'file-text');
            if (textExam) textExam.innerText = 'Comenzar prueba';
        } else {
            btnExam.disabled = true;
            if (iconExam) iconExam.setAttribute('data-lucide', 'lock');
            if (textExam) textExam.innerText = 'Comenzar prueba';
        }
    }
    if (window.lucide) lucide.createIcons();
}

function populateExamSelect() {
    const select = document.getElementById('exam-course-select');
    if (!select) return;

    const enrolled = (window.courses || []).filter(c => c.enrolled || c.inscrito || c.pivot);

    select.innerHTML = '';
    if (enrolled.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'No estás inscrito en ningún curso';
        select.appendChild(option);
        return;
    }

    enrolled.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = `${c.title || c.titulo} (${getCourseProgress(c)}% completado)`;
        select.appendChild(option);
    });
}

function openExamModalForCourse(courseId) {
    activeCourseId = courseId;
    const course = (window.courses || []).find(c => c.id === courseId);
    if (!course) return;

    const elTitle = document.getElementById('exam-modal-title');
    if (elTitle) elTitle.innerText = `Evaluación: ${course.title || course.titulo}`;
    
    selectedExamAnswers = {};
    const container = document.getElementById('exam-questions-container');
    if (!container) return;

    container.innerHTML = '';
    const questions = course.examQuestions || course.preguntas || [];

    if (questions.length === 0) {
        container.innerHTML = `<p class="text-xs text-neutral-400 py-6 text-center">Este examen no cuenta con preguntas configuradas aún.</p>`;
    } else {
        questions.forEach((q, qIndex) => {
            const qBox = document.createElement('div');
            qBox.className = 'space-y-3 border-b border-neutral-100 pb-4';

            const pText = document.createElement('p');
            pText.className = 'font-semibold text-neutral-800 text-sm';
            pText.textContent = `${qIndex + 1}. ${q.q || q.pregunta}`;

            const optsDiv = document.createElement('div');
            optsDiv.className = 'space-y-2';

            (q.options || q.opciones || []).forEach((opt, oIndex) => {
                const label = document.createElement('label');
                label.className = 'flex items-center gap-3 p-3 rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-orange-50/50 cursor-pointer text-sm text-neutral-700 transition-all';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = `q_${qIndex}`;
                radio.value = oIndex;
                radio.className = 'accent-orange-600';
                radio.onchange = () => selectedExamAnswers[qIndex] = oIndex;

                const optSpan = document.createElement('span');
                optSpan.textContent = opt;

                label.appendChild(radio);
                label.appendChild(optSpan);
                optsDiv.appendChild(label);
            });

            qBox.appendChild(pText);
            qBox.appendChild(optsDiv);
            container.appendChild(qBox);
        });
    }

    const modal = document.getElementById('exam-modal');
    if (modal) modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
}

function submitExam() {
    const course = (window.courses || []).find(c => c.id === activeCourseId);
    if (!course) return;

    const questions = course.examQuestions || course.preguntas || [];
    if (questions.length === 0) {
        showToast("No hay preguntas para calificar en este examen.", 'error');
        return;
    }

    let correctCount = 0;
    questions.forEach((q, index) => {
        if (selectedExamAnswers[index] === (q.correct !== undefined ? q.correct : q.respuesta_correcta)) {
            correctCount++;
        }
    });

    const score = Math.round((correctCount / questions.length) * 100);

    if (score >= 80) {
        course.examPassed = true;
        course.examDate = new Date().toLocaleDateString('es-ES');
        closeModal('exam-modal');
        renderMyCourses();
        renderCertificates();
        updateStats();
        populateExamSelect();
        showToast(`¡Felicidades! Aprobaste con ${score}%. Tu certificado ha sido generado.`, 'success');
        openCertificateModal(course.title || course.titulo);
    } else {
        showToast(`Obtuviste ${score}%. Necesitas mínimo 80% para aprobar. Inténtalo nuevamente.`, 'error');
    }
}

function openCertificateModal(title) {
    const elName = document.getElementById('cert-modal-course-name');
    if (elName) elName.innerText = title;

    const modal = document.getElementById('certificate-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('hidden');
}

function showToast(msg, type = 'info') {
    const toast = document.getElementById('toast');
    const text = document.getElementById('toast-message');
    if (!toast || !text) return;

    text.innerText = msg;
    toast.classList.remove('translate-y-[-100px]', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-[-100px]', 'opacity-0');
    }, 3500);
}

function filterCatalog() {
    const input = document.getElementById('catalog-search');
    if (!input) return;

    const query = input.value.toLowerCase();
    const courses = window.courses || [];
    const filtered = courses.filter(c => {
        const t = (c.title || c.titulo || '').toLowerCase();
        const cat = (c.category || c.categoria || '').toLowerCase();
        return t.includes(query) || cat.includes(query);
    });
    
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

    grid.innerHTML = '';
    if (filtered.length === 0) {
        renderEstadoVacio(grid, 'search-x', 'Sin coincidencias', 'No se encontraron cursos coincidentes.');
        return;
    }

    const tplCard = document.getElementById('tpl-catalogo-card');

    filtered.forEach(course => {
        const clone = tplCard.content.cloneNode(true);
        const totalModulos = (course.lessons || course.modulos || course.lecciones || []).length;
        const titulo = course.title || course.titulo || 'Curso sin título';
        const categoria = course.category || course.categoria || 'General';
        const imagen = course.image || course.imagen || '/img/logo.png';
        const isInscrito = course.enrolled || course.inscrito || course.pivot;

        clone.querySelector('.img-portada').src = imagen;
        clone.querySelector('.img-portada').alt = titulo;
        clone.querySelector('.badge-categoria').textContent = categoria;
        clone.querySelector('.txt-titulo').textContent = titulo;
        clone.querySelector('.txt-modulos').textContent = `${totalModulos} Módulos lectivos`;

        const contenedorAccion = clone.querySelector('.contenedor-accion');

        if (isInscrito) {
            const divInscrito = document.createElement('div');
            divInscrito.className = 'bg-orange-50 text-orange-700 p-2.5 rounded-xl text-xs font-semibold flex items-center justify-center gap-2';
            divInscrito.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Ya estás inscrito';
            contenedorAccion.appendChild(divInscrito);
        } else {
            const btnInscribirse = document.createElement('button');
            btnInscribirse.className = 'w-full py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold rounded-xl text-sm shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center justify-center gap-2';
            btnInscribirse.onclick = () => enrollCourse(course.id);
            btnInscribirse.innerHTML = '<i data-lucide="plus-circle" class="w-4 h-4"></i> Inscribirse Gratis';
            contenedorAccion.appendChild(btnInscribirse);
        }

        grid.appendChild(clone);
    });

    if (window.lucide) lucide.createIcons();
}
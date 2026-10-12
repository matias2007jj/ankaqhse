let activeCourseId = null;
let selectedExamAnswers = {};

document.addEventListener('DOMContentLoaded', () => {
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

    document.getElementById(`view-${tabId}`).classList.remove('hidden');
    const activeBtn = document.getElementById(`tab-${tabId}`);
    if (activeBtn) {
        activeBtn.classList.add('boton-pestana-activo');
        activeBtn.classList.remove('boton-pestana-inactivo');
    }

    if (tabId === 'exam-tab') {
        updateExamTabState();
    }
}

function getCourseProgress(course) {
    if (!course.lessons || course.lessons.length === 0) return 0;
    const completed = course.lessons.filter(l => l.completed).length;
    return Math.round((completed / course.lessons.length) * 100);
}

function updateStats() {
    const activeCount = window.courses.filter(c => c.enrolled && !c.examPassed).length;
    const completedCount = window.courses.filter(c => c.examPassed).length;

    document.getElementById('stat-active').innerText = activeCount;
    document.getElementById('stat-completed').innerText = completedCount;
    document.getElementById('stat-certs').innerText = completedCount;
}

function renderMyCourses() {
    const grid = document.getElementById('my-courses-grid');
    const enrolledCourses = window.courses.filter(c => c.enrolled);

    if (enrolledCourses.length === 0) {
        grid.innerHTML = `<p class="col-span-3 text-center text-neutral-400 py-10">No estás inscrito en ningún curso actualmente.</p>`;
        return;
    }

    grid.innerHTML = enrolledCourses.map(course => {
        const progress = getCourseProgress(course);
        const is100 = progress === 100;

        return `
            <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col">
                <div class="h-40 overflow-hidden relative">
                    <img src="${course.image}" alt="${course.title}" class="w-full h-full object-cover">
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-semibold text-neutral-800 shadow-sm">
                        ${course.category}
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-bold text-neutral-900 text-base leading-snug">${course.title}</h3>
                        <p class="text-xs text-neutral-500 mt-1">${course.lessons.length} Módulos interactivos</p>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs font-medium mb-1">
                                <span class="text-neutral-500">Progreso lectivo</span>
                                <span class="text-orange-600 font-bold">${progress}%</span>
                            </div>
                            <div class="w-full bg-neutral-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-red-600 to-orange-500 h-2 rounded-full transition-all duration-300" style="width: ${progress}%"></div>
                            </div>
                        </div>
                        <div class="pt-2 flex items-center justify-between gap-2">
                            <button onclick="openCourseModal(${course.id})" class="flex-1 px-4 py-2 border border-orange-500 text-orange-600 hover:bg-orange-50 rounded-xl text-sm font-semibold transition-all text-center">
                                Ver Módulos
                            </button>
                            <button 
                                onclick="${is100 ? `openExamModalForCourse(${course.id})` : "showToast('Completa los módulos al 100% para iniciar el examen', 'info')"}" 
                                class="${is100 ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md' : 'bg-neutral-100 text-neutral-400 cursor-not-allowed'} px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-1.5"
                                ${!is100 ? 'disabled' : ''}>
                                <i data-lucide="${is100 ? 'file-text' : 'lock'}" class="w-4 h-4"></i>
                                <span>Prueba</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    lucide.createIcons();
}

function renderCatalog() {
    const grid = document.getElementById('catalog-grid');
    grid.innerHTML = window.courses.map(course => `
        <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col">
            <div class="h-40 overflow-hidden relative">
                <img src="${course.image}" alt="${course.title}" class="w-full h-full object-cover">
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-semibold text-neutral-800 shadow-sm">
                    ${course.category}
                </span>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="font-bold text-neutral-900 text-base">${course.title}</h3>
                    <p class="text-xs text-neutral-500 mt-1">${course.lessons.length} Módulos lectivos</p>
                </div>
                ${course.enrolled ? `
                    <div class="bg-orange-50 text-orange-700 p-2.5 rounded-xl text-xs font-semibold flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Ya estás inscrito
                    </div>
                ` : `
                    <button onclick="enrollCourse(${course.id})" class="w-full py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold rounded-xl text-sm shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Inscribirse Gratis
                    </button>
                `}
            </div>
        </div>
    `).join('');

    lucide.createIcons();
}

function renderCertificates() {
    const grid = document.getElementById('certificates-grid');
    const passed = window.courses.filter(c => c.examPassed);

    if (passed.length === 0) {
        grid.innerHTML = `<p class="col-span-3 text-center text-neutral-400 py-10">Completa tus evaluaciones al 100% para obtener tu certificado.</p>`;
        return;
    }

    grid.innerHTML = passed.map(c => `
        <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-neutral-400">Certificado Oficial</span>
                    <h3 class="font-bold text-neutral-900 text-base leading-snug">${c.title}</h3>
                    <p class="text-xs text-neutral-500 mt-1">Aprobado el ${c.examDate || new Date().toLocaleDateString('es-ES')}</p>
                </div>
            </div>
            <button onclick="openCertificateModal('${c.title}')" class="w-full py-2 border border-neutral-300 text-neutral-700 hover:bg-neutral-50 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <i data-lucide="eye" class="w-4 h-4"></i> Ver Certificado
            </button>
        </div>
    `).join('');

    lucide.createIcons();
}

function enrollCourse(id) {
    const course = window.courses.find(c => c.id === id);
    if (course) {
        course.enrolled = true;
        renderMyCourses();
        renderCatalog();
        populateExamSelect();
        updateStats();
        showToast(`Te has inscrito exitosamente en "${course.title}"`, 'success');
    }
}

function openCourseModal(id) {
    activeCourseId = id;
    const course = window.courses.find(c => c.id === id);
    if (!course) return;

    document.getElementById('modal-title').innerText = course.title;
    document.getElementById('modal-category').innerText = course.category;

    renderModalLessons(course);
    updateModalProgress(course);

    document.getElementById('course-modal').classList.remove('hidden');
}

function renderModalLessons(course) {
    const list = document.getElementById('modal-lessons-list');
    list.innerHTML = course.lessons.map(lesson => `
        <div class="flex items-center justify-between p-3.5 rounded-xl border ${lesson.completed ? 'bg-orange-50/50 border-orange-200' : 'bg-white border-neutral-200'} transition-all">
            <div class="flex items-center gap-3">
                <input 
                    type="checkbox" 
                    ${lesson.completed ? 'checked' : ''} 
                    onchange="toggleLesson(${course.id}, ${lesson.id})"
                    class="w-5 h-5 accent-orange-600 rounded cursor-pointer"
                >
                <span class="text-sm font-medium ${lesson.completed ? 'line-through text-neutral-400' : 'text-neutral-800'}">
                    ${lesson.title}
                </span>
            </div>
            <span class="text-xs ${lesson.completed ? 'text-orange-600 font-semibold' : 'text-neutral-400'}">
                ${lesson.completed ? 'Completado' : 'Pendiente'}
            </span>
        </div>
    `).join('');

    if (course.lessons.length > 0) {
        document.getElementById('modal-active-lesson-title').innerText = course.lessons[0].title;
    }
}

function toggleLesson(courseId, lessonId) {
    const course = window.courses.find(c => c.id === courseId);
    if (!course) return;

    const lesson = course.lessons.find(l => l.id === lessonId);
    if (lesson) {
        lesson.completed = !lesson.completed;
        renderModalLessons(course);
        updateModalProgress(course);
        renderMyCourses();
        updateStats();
    }
}

function updateModalProgress(course) {
    const progress = getCourseProgress(course);
    
    document.getElementById('modal-progress-text').innerText = `${progress}% Completado`;
    document.getElementById('modal-progress-bar').style.width = `${progress}%`;

    const btnExam = document.getElementById('modal-btn-start-exam');
    const iconExam = document.getElementById('modal-btn-exam-icon');
    const textExam = document.getElementById('modal-btn-exam-text');

    if (progress === 100) {
        btnExam.disabled = false;
        iconExam.setAttribute('data-lucide', 'file-text');
        textExam.innerText = 'Comenzar prueba';
    } else {
        btnExam.disabled = true;
        iconExam.setAttribute('data-lucide', 'lock');
        textExam.innerText = 'Comenzar prueba';
    }
    lucide.createIcons();
}

function startCourseExamFromModal() {
    if (!activeCourseId) return;
    closeModal('course-modal');
    openExamModalForCourse(activeCourseId);
}

function populateExamSelect() {
    const select = document.getElementById('exam-course-select');
    const enrolled = window.courses.filter(c => c.enrolled);

    if (enrolled.length === 0) {
        select.innerHTML = `<option value="">No estás inscrito en ningún curso</option>`;
        return;
    }

    select.innerHTML = enrolled.map(c => `
        <option value="${c.id}">${c.title} (${getCourseProgress(c)}% completado)</option>
    `).join('');
}

function updateExamTabState() {
    const select = document.getElementById('exam-course-select');
    const box = document.getElementById('exam-status-box');
    const courseId = parseInt(select.value);

    if (!courseId) {
        box.innerHTML = `<p class="text-center text-neutral-400 text-sm">Debes estar inscrito en un curso para evaluar tus conocimientos.</p>`;
        return;
    }

    const course = window.courses.find(c => c.id === courseId);
    const progress = getCourseProgress(course);

    if (course.examPassed) {
        box.innerHTML = `
            <div class="text-center space-y-3">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-neutral-800 text-lg">¡Evaluación Aprobada!</h3>
                <p class="text-sm text-neutral-500">Ya has superado este examen satisfactoriamente y tu certificado está disponible.</p>
                <button onclick="openCertificateModal('${course.title}')" class="px-5 py-2.5 bg-neutral-900 text-white font-semibold rounded-xl text-sm hover:bg-neutral-800 transition-all">
                    Ver Certificado
                </button>
            </div>
        `;
    } else if (progress < 100) {
        box.innerHTML = `
            <div class="text-center space-y-3">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-neutral-800 text-base">Prueba Bloqueada (${progress}%)</h3>
                <p class="text-xs text-neutral-500 max-w-md mx-auto">Debes completar el 100% de los módulos lectivos del curso antes de habilitar el examen.</p>
                <button disabled class="mt-2 px-6 py-2.5 bg-neutral-200 text-neutral-400 rounded-xl text-sm font-semibold cursor-not-allowed flex items-center justify-center gap-2 mx-auto">
                    <i data-lucide="lock" class="w-4 h-4"></i> Comenzar prueba
                </button>
            </div>
        `;
    } else {
        box.innerHTML = `
            <div class="text-center space-y-3">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="unlock" class="w-6 h-6"></i>
                </div>
                <h3 class="font-bold text-neutral-800 text-base">¡Módulos Completados al 100%!</h3>
                <p class="text-xs text-neutral-500 max-w-md mx-auto">Estás listo para rendir la prueba oficial del curso y obtener tu certificado.</p>
                <button onclick="openExamModalForCourse(${course.id})" class="mt-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-xl text-sm font-semibold shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center justify-center gap-2 mx-auto">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Comenzar prueba
                </button>
            </div>
        `;
    }

    lucide.createIcons();
}

function openExamModalForCourse(courseId) {
    activeCourseId = courseId;
    const course = window.courses.find(c => c.id === courseId);
    if (!course) return;

    document.getElementById('exam-modal-title').innerText = `Evaluación: ${course.title}`;
    selectedExamAnswers = {};

    const container = document.getElementById('exam-questions-container');
    container.innerHTML = course.examQuestions.map((q, qIndex) => `
        <div class="space-y-3 border-b border-neutral-100 pb-4">
            <p class="font-semibold text-neutral-800 text-sm">${qIndex + 1}. ${q.q}</p>
            <div class="space-y-2">
                ${q.options.map((opt, oIndex) => `
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-orange-50/50 cursor-pointer text-sm text-neutral-700 transition-all">
                        <input type="radio" name="q_${qIndex}" value="${oIndex}" onchange="selectedExamAnswers[${qIndex}] =${oIndex}" class="accent-orange-600">
                        <span>${opt}</span>
                    </label>
                `).join('')}
            </div>
        </div>
    `).join('');

    document.getElementById('exam-modal').classList.remove('hidden');
    lucide.createIcons();
}

function submitExam() {
    const course = window.courses.find(c => c.id === activeCourseId);
    if (!course) return;

    let correctCount = 0;
    course.examQuestions.forEach((q, index) => {
        if (selectedExamAnswers[index] === q.correct) {
            correctCount++;
        }
    });

    const score = Math.round((correctCount / course.examQuestions.length) * 100);

    if (score >= 80) {
        course.examPassed = true;
        course.examDate = new Date().toLocaleDateString('es-ES');
        closeModal('exam-modal');
        renderMyCourses();
        renderCertificates();
        updateStats();
        populateExamSelect();
        showToast(`¡Felicidades! Aprobaste con ${score}%. Tu certificado ha sido generado.`, 'success');
        openCertificateModal(course.title);
    } else {
        showToast(`Obtuviste ${score}%. Necesitas mínimo 80% para aprobar. Inténtalo nuevamente.`, 'error');
    }
}

function openCertificateModal(title) {
    document.getElementById('cert-modal-course-name').innerText = title;
    document.getElementById('certificate-modal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function showToast(msg, type = 'info') {
    const toast = document.getElementById('toast');
    const text = document.getElementById('toast-message');
    text.innerText = msg;

    toast.classList.remove('translate-y-[-100px]', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-[-100px]', 'opacity-0');
    }, 3500);
}

function filterCatalog() {
    const query = document.getElementById('catalog-search').value.toLowerCase();
    const filtered = window.courses.filter(c => c.title.toLowerCase().includes(query) || c.category.toLowerCase().includes(query));
    
    const grid = document.getElementById('catalog-grid');
    grid.innerHTML = filtered.map(course => `
        <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col">
            <div class="h-40 overflow-hidden relative">
                <img src="${course.image}" alt="${course.title}" class="w-full h-full object-cover">
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-semibold text-neutral-800 shadow-sm">
                    ${course.category}
                </span>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="font-bold text-neutral-900 text-base">${course.title}</h3>
                    <p class="text-xs text-neutral-500 mt-1">${course.lessons.length} Módulos lectivos</p>
                </div>
                ${course.enrolled ? `
                    <div class="bg-orange-50 text-orange-700 p-2.5 rounded-xl text-xs font-semibold flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Ya estás inscrito
                    </div>
                ` : `
                    <button onclick="enrollCourse(${course.id})" class="w-full py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white font-semibold rounded-xl text-sm shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Inscribirse Gratis
                    </button>
                `}
            </div>
        </div>
    `).join('');

    lucide.createIcons();
}
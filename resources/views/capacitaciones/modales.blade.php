<!-- MODAL: REPRODUCTOR DE CURSO Y MÓDULOS -->
<div id="course-modal" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-neutral-200">
        <div class="p-5 border-b border-neutral-200 flex items-center justify-between bg-neutral-50">
            <div>
                <span id="modal-category" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">Categoría</span>
                <h2 id="modal-title" class="text-lg font-bold text-neutral-900 mt-1">Título del Curso</h2>
            </div>
            <button onclick="closeModal('course-modal')" class="p-2 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-200 transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6 barra-desplazamiento-personalizada">
            <div class="bg-neutral-900 rounded-xl overflow-hidden aspect-video relative flex items-center justify-center shadow-inner group">
                <div class="text-center p-6">
                    <div class="w-16 h-16 rounded-full bg-white/10 border border-white/20 text-white flex items-center justify-center mx-auto mb-3 backdrop-blur-md group-hover:scale-110 transition-transform">
                        <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                    </div>
                    <p id="modal-active-lesson-title" class="text-white font-medium text-base">Cargando lección...</p>
                    <p class="text-neutral-400 text-xs mt-1">Video HD • Reproductor de Capacitación</p>
                </div>
            </div>

            <div class="bg-neutral-50 p-4 rounded-xl border border-neutral-200 space-y-2">
                <div class="flex justify-between text-xs font-medium text-neutral-600">
                    <span>Progreso del Curso</span>
                    <span id="modal-progress-text">0% Completado</span>
                </div>
                <div class="w-full bg-neutral-200 rounded-full h-2.5 overflow-hidden">
                    <div id="modal-progress-bar" class="bg-gradient-to-r from-red-600 to-orange-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-neutral-800 uppercase tracking-wider mb-3">Módulos del Curso</h3>
                <div id="modal-lessons-list" class="space-y-2"></div>
            </div>
        </div>

        <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs text-neutral-500 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-orange-500"></i>
                <span>Debes completar el 100% de los módulos para rendir el examen.</span>
            </div>
            <button id="modal-btn-start-exam" disabled onclick="startCourseExamFromModal()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 disabled:bg-neutral-200 disabled:text-neutral-400 disabled:cursor-not-allowed bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md hover:shadow-lg hover:from-red-700 hover:to-orange-600">
                <i data-lucide="lock" id="modal-btn-exam-icon" class="w-4 h-4"></i>
                <span id="modal-btn-exam-text">Comenzar prueba</span>
            </button>
        </div>
    </div>
</div>

<!-- MODAL: EVALUACIÓN / EXAMEN -->
<div id="exam-modal" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-neutral-200">
        <div class="p-5 border-b border-neutral-200 flex items-center justify-between bg-neutral-50">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Evaluación Oficial</span>
                <h2 id="exam-modal-title" class="text-lg font-bold text-neutral-900 mt-1">Examen de Conocimientos</h2>
            </div>
            <button onclick="closeModal('exam-modal')" class="p-2 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6 barra-desplazamiento-personalizada" id="exam-questions-container"></div>

        <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex items-center justify-between">
            <span class="text-xs text-neutral-500">Mínimo para aprobar: 80%</span>
            <button onclick="submitExam()" class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-md hover:from-red-700 hover:to-orange-600 transition-all flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i> Enviar Respuestas
            </button>
        </div>
    </div>
</div>

<!-- MODAL: CERTIFICADO -->
<div id="certificate-modal" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-3xl w-full flex flex-col shadow-2xl overflow-hidden border border-neutral-200">
        <div class="p-4 border-b border-neutral-200 flex items-center justify-between bg-neutral-50">
            <h3 class="text-sm font-bold text-neutral-700">Vista Previa de Certificado</h3>
            <button onclick="closeModal('certificate-modal')" class="p-1.5 text-neutral-400 hover:text-neutral-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-8 bg-neutral-100 flex items-center justify-center">
            <div id="printableCertificate" class="bg-white p-8 sm:p-12 rounded-xl border-8 border-double border-neutral-200 text-center max-w-2xl w-full shadow-lg relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-100 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-red-100 rounded-full blur-2xl"></div>

                <div class="relative z-10 space-y-4">
                    <div class="flex justify-center mb-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 p-2 text-white flex items-center justify-center shadow-md">
                            <svg viewBox="0 0 100 120" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 95 C 10 75, 25 50, 42 28 C 50 18, 58 10, 68 8 C 65 20, 58 32, 50 42 C 60 30, 75 18, 85 28 C 92 35, 88 48, 82 58 C 76 68, 70 78, 88 95 C 65 95, 55 78, 48 68 C 40 58, 35 72, 20 95 Z" fill="#FFF"/>
                                <g>
                                    <polygon points="56,15 76,15 73,23 59,23" fill="#1E293B"/>
                                    <polygon points="66,4 92,13 66,21 40,13" fill="#0F172A" stroke="#FFFFFF" stroke-width="1.5"/>
                                    <circle cx="66" cy="12.5" r="2.5" fill="#F97316"/>
                                    <path d="M 66 12.5 L 48 18 L 45 28" stroke="#F97316" stroke-width="2"/>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <p class="text-xs font-bold uppercase tracking-widest text-orange-600">Certificado de Aprobación</p>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-neutral-900">CapacitaRed Enterprise</h2>
                    <p class="text-xs text-neutral-400">Otorga la presente acreditación a:</p>
                    
                    <h3 class="text-2xl font-bold text-neutral-800 underline decoration-orange-500 decoration-2 underline-offset-4 py-2">
                        {{ Auth::user()->name ?? 'Ana Martínez' }}
                    </h3>

                    <p class="text-xs text-neutral-600 max-w-md mx-auto leading-relaxed">
                        Por haber completado con éxito el 100% del programa lectivo y haber aprobado satisfactoriamente la evaluación del curso:
                    </p>

                    <p id="cert-modal-course-name" class="text-lg font-bold text-orange-600">Nombre del Curso</p>

                    <div class="pt-6 border-t border-neutral-200 grid grid-cols-2 gap-4 text-left text-xs text-neutral-500">
                        <div>
                            <p class="font-semibold text-neutral-700">Fecha de Emisión:</p>
                            <p id="cert-modal-date">{{ date('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-neutral-700">Código de Verificación:</p>
                            <p id="cert-modal-code" class="font-mono text-neutral-800">CR-{{ date('Y') }}-8891</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex justify-end gap-3">
            <button onclick="window.print()" class="px-5 py-2 bg-neutral-900 text-white text-sm font-semibold rounded-xl hover:bg-neutral-800 transition-all flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimir / PDF
            </button>
        </div>
    </div>
</div>
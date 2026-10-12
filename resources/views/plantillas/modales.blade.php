<!-- MODAL: CREAR / EDITAR CURSO -->
<div id="modal-curso-admin" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-neutral-200 space-y-4">
        <div class="flex justify-between items-center border-b border-neutral-200 pb-3">
            <h3 class="text-lg font-bold text-neutral-900">Crear Nuevo Curso</h3>
            <button onclick="cerrarModalAdmin('modal-curso-admin')" class="text-neutral-400 hover:text-neutral-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('administrador.guardarCurso') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Título del Curso</label>
                <input type="text" name="title" required placeholder="Ej. Primeros Auxilios en Planta" class="w-full p-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Categoría</label>
                <select name="category" class="w-full p-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="Seguridad Laboral">Seguridad Laboral / SST</option>
                    <option value="Compliance & Seguridad">Compliance & Seguridad</option>
                    <option value="Habilidades Blandas">Habilidades Blandas</option>
                    <option value="Operaciones">Operaciones</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">URL de Imagen Portada</label>
                <input type="url" name="image" placeholder="https://..." class="w-full p-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <button type="button" onclick="cerrarModalAdmin('modal-curso-admin')" class="px-4 py-2 border border-neutral-300 text-neutral-700 rounded-xl text-sm font-semibold hover:bg-neutral-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white rounded-xl text-sm font-semibold shadow-md">Guardar Curso</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ASIGNACIÓN A ÁREAS -->
<div id="modal-asignar-area" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-neutral-200 space-y-4">
        <div class="flex justify-between items-center border-b border-neutral-200 pb-3">
            <h3 class="text-lg font-bold text-neutral-900">Asignar Capacitación a Departamento</h3>
            <button onclick="cerrarModalAdmin('modal-asignar-area')" class="text-neutral-400 hover:text-neutral-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('administrador.asignarCurso') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Selecciona el Curso</label>
                <select name="curso_id" class="w-full p-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    @foreach($cursos as $c)
                        <option value="{{ $c['id'] }}">{{ $c['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-neutral-700 uppercase tracking-wider mb-1">Área o Departamento</label>
                <select name="area" class="w-full p-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="todas">Todos los Empleados</option>
                    <option value="Operaciones">Operaciones y Logística</option>
                    <option value="TI">Tecnologías de la Información</option>
                    <option value="RRHH">Recursos Humanos</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <button type="button" onclick="cerrarModalAdmin('modal-asignar-area')" class="px-4 py-2 border border-neutral-300 text-neutral-700 rounded-xl text-sm font-semibold hover:bg-neutral-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-neutral-900 text-white rounded-xl text-sm font-semibold">Asignar Ahora</button>
            </div>
        </form>
    </div>
</div>
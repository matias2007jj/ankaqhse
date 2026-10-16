<!-- MODAL: ASIGNAR CURSO A COLABORADOR -->
<div id="modal-asignar-curso" class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl border border-neutral-200 space-y-4">
        
        <!-- CABECERA DEL MODAL -->
        <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
            <h3 class="font-bold text-neutral-800 text-lg">Asignar Curso a Colaborador</h3>
            <button onclick="cerrarModalAsignar()" type="button" class="text-neutral-400 hover:text-neutral-600 font-bold transition-all text-lg cursor-pointer">✕</button>
        </div>

        <!-- FORMULARIO DE ASIGNACIÓN -->
        <form id="form-asignar-curso" action="{{ route('admin.asignar-curso') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="user_id" id="modal-user-id">

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Colaborador</label>
                <input type="text" id="modal-user-name" readonly class="w-full bg-neutral-100 border border-neutral-200 rounded-xl px-3 py-2 text-sm text-neutral-700 font-semibold cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-neutral-600 uppercase mb-1">Seleccionar Capacitación</label>
                <select name="curso_id" id="modal-curso-id" required class="w-full border border-neutral-200 rounded-xl px-3 py-2 text-sm text-neutral-800 focus:outline-none focus:border-orange-500 bg-white">
                    <option value="">-- Selecciona un curso --</option>
                    @forelse($cursos ?? [] as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->titulo ?? $curso->nombre }}</option>
                    @empty
                        <option value="" disabled>No hay cursos registrados</option>
                    @endforelse
                </select>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="button" onclick="cerrarModalAsignar()" class="flex-1 py-2.5 border border-neutral-200 text-neutral-600 rounded-xl text-sm font-semibold hover:bg-neutral-50 transition-all cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md cursor-pointer">
                    Confirmar Asignación
                </button>
            </div>
        </form>
    </div>
</div>
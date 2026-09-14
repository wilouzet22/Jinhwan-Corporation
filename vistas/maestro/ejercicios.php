<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Biblioteca de Ejercicios
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Catálogo central compartido de ejercicios clasificados para sesiones de entrenamiento.
                </p>
            </div>
            <div>
                <button onclick="openModal('modal-nuevo-ejercicio')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                    <span class="material-icons-outlined text-base">add</span>
                    <span>Nuevo Ejercicio</span>
                </button>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-icons-outlined text-sm">search</span>
                </div>
                <input type="text" id="filter-search" placeholder="Buscar por nombre o explicación..." 
                       class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Capacidad / Tipo:</span>
                <select id="filter-tipo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors w-full md:w-56">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Tabla de Ejercicios -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse" id="tabla-ejercicios">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3">Tipo / Capacidad</th>
                            <th scope="col" class="px-4 py-3">Nombre</th>
                            <th scope="col" class="px-4 py-3">Explicación / Metodología</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors text-slate-700 dark:text-slate-300">
                        <?php if (empty($ejercicios)): ?>
                            <tr id="row-empty">
                                <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-outlined text-4xl block mb-2 opacity-50">fitness_center</span>
                                    No hay ejercicios registrados aún en la biblioteca.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ejercicios as $ej): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all item-ejercicio" 
                                    data-tipo="<?php echo htmlspecialchars($ej['tipo']); ?>"
                                    data-text="<?php echo htmlspecialchars(strtolower($ej['nombre'] . ' ' . $ej['tipo'] . ' ' . $ej['explicacion'])); ?>">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 rounded-xl text-xs font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                            <?php echo htmlspecialchars($ej['tipo']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                        <?php echo htmlspecialchars($ej['nombre']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 max-w-md">
                                        <div class="line-clamp-2"><?php echo nl2br(htmlspecialchars($ej['explicacion'] ?? '')); ?></div>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick='editarEjercicio(<?php echo json_encode($ej); ?>)' 
                                                    class="p-1.5 text-blue-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition" title="Editar">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <form method="POST" action="<?php echo base_url('maestro/ejercicios/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este ejercicio? Se desvinculará de las clases.')" class="inline">
                                                <input type="hidden" name="id_ejercicio" value="<?php echo $ej['id_ejercicio']; ?>">
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition" title="Eliminar">
                                                    <span class="material-icons-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Modal Nuevo Ejercicio -->
<div id="modal-nuevo-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-purple-600 dark:text-purple-400">fitness_center</span>
                Nuevo Ejercicio
            </h3>
            <button onclick="closeModal('modal-nuevo-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/create'); ?>" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tipo / Capacidad *</label>
                <select name="tipo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione un tipo...</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" required placeholder="Ej. Sentadillas con salto, Bandal Chagui..." 
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Explicación / Metodología</label>
                <textarea name="explicacion" rows="4" placeholder="Detalla la ejecución técnica y precauciones..."
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-nuevo-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    Guardar Ejercicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Ejercicio -->
<div id="modal-editar-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-blue-500">edit</span>
                Editar Ejercicio
            </h3>
            <button onclick="closeModal('modal-editar-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/update'); ?>" class="p-6 space-y-4">
            <input type="hidden" name="id_ejercicio" id="edit-id">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tipo / Capacidad *</label>
                <select name="tipo" id="edit-tipo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" id="edit-nombre" required 
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Explicación / Metodología</label>
                <textarea name="explicacion" id="edit-explicacion" rows="4"
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-editar-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editarEjercicio(ej) {
    document.getElementById('edit-id').value = ej.id_ejercicio;
    document.getElementById('edit-tipo').value = ej.tipo;
    document.getElementById('edit-nombre').value = ej.nombre;
    document.getElementById('edit-explicacion').value = ej.explicacion || '';
    openModal('modal-editar-ejercicio');
}

const filterSearch = document.getElementById('filter-search');
const filterTipo = document.getElementById('filter-tipo');
const items = document.querySelectorAll('.item-ejercicio');

function filtrar() {
    const q = filterSearch.value.toLowerCase().trim();
    const tipo = filterTipo.value;

    items.forEach(el => {
        const text = el.getAttribute('data-text');
        const t = el.getAttribute('data-tipo');

        const matchSearch = !q || text.includes(q);
        const matchTipo = !tipo || t === tipo;

        el.style.display = (matchSearch && matchTipo) ? '' : 'none';
    });
}

if (filterSearch) filterSearch.addEventListener('input', filtrar);
if (filterTipo) filterTipo.addEventListener('change', filtrar);
</script>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

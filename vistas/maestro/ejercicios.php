<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Biblioteca de Ejercicios</h1>
            <p class="text-sm text-gray-500">Catálogo central compartido de ejercicios clasificados para sesiones de entrenamiento.</p>
        </div>
        <button onclick="openModal('modal-nuevo-ejercicio')" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition">
            <span class="material-icons-outlined text-sm">add</span>
            Nuevo Ejercicio
        </button>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 mb-6 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex-1 w-full md:w-auto relative">
            <span class="material-icons-outlined absolute left-3 top-2.5 text-gray-400">search</span>
            <input type="text" id="filter-search" placeholder="Buscar ejercicio por nombre o explicación..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none text-sm">
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Capacidad / Tipo:</span>
            <select id="filter-tipo" class="w-full md:w-56 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                <option value="">Todos los tipos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Tabla de Ejercicios -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabla-ejercicios">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Tipo / Capacidad</th>
                        <th class="py-3 px-4">Nombre</th>
                        <th class="py-3 px-4">Explicación / Metodología</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (empty($ejercicios)): ?>
                        <tr id="row-empty">
                            <td colspan="4" class="py-8 text-center text-gray-400">
                                <span class="material-icons-outlined text-4xl block mb-2 text-gray-300">fitness_center</span>
                                No hay ejercicios registrados aún en la biblioteca.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ejercicios as $ej): ?>
                            <tr class="hover:bg-gray-50 transition item-ejercicio" 
                                data-tipo="<?php echo htmlspecialchars($ej['tipo']); ?>"
                                data-text="<?php echo htmlspecialchars(strtolower($ej['nombre'] . ' ' . $ej['tipo'] . ' ' . $ej['explicacion'])); ?>">
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                        <?php echo htmlspecialchars($ej['tipo']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($ej['nombre']); ?>
                                </td>
                                <td class="py-3 px-4 text-gray-600 max-w-md">
                                    <div class="line-clamp-2"><?php echo nl2br(htmlspecialchars($ej['explicacion'] ?? '')); ?></div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick='editarEjercicio(<?php echo json_encode($ej); ?>)' 
                                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition" title="Editar">
                                            <span class="material-icons-outlined text-lg">edit</span>
                                        </button>
                                        <form method="POST" action="<?php echo base_url('maestro/ejercicios/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este ejercicio? Se desvinculará de las clases.')" class="inline">
                                            <input type="hidden" name="id_ejercicio" value="<?php echo $ej['id_ejercicio']; ?>">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition" title="Eliminar">
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

<!-- Modal Nuevo Ejercicio -->
<div id="modal-nuevo-ejercicio" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons-outlined text-purple-600">fitness_center</span>
                Nuevo Ejercicio
            </h3>
            <button onclick="closeModal('modal-nuevo-ejercicio')" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/create'); ?>" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo / Capacidad *</label>
                <select name="tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione un tipo...</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" required placeholder="Ej. Sentadillas con salto, Patada Bandal Chagui..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Explicación / Metodología</label>
                <textarea name="explicacion" rows="4" placeholder="Detalla la ejecución, técnica correcta, precauciones..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-nuevo-ejercicio')" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                    Guardar Ejercicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Ejercicio -->
<div id="modal-editar-ejercicio" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons-outlined text-blue-600">edit</span>
                Editar Ejercicio
            </h3>
            <button onclick="closeModal('modal-editar-ejercicio')" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/update'); ?>" class="p-6 space-y-4">
            <input type="hidden" name="id_ejercicio" id="edit-id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo / Capacidad *</label>
                <select name="tipo" id="edit-tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" id="edit-nombre" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Explicación / Metodología</label>
                <textarea name="explicacion" id="edit-explicacion" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-editar-ejercicio')" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
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

// Filtro en tiempo real
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

filterSearch.addEventListener('input', filtrar);
filterTipo.addEventListener('change', filtrar);
</script>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

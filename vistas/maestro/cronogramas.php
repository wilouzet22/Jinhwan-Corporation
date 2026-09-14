<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cronogramas de Clase</h1>
            <p class="text-sm text-gray-500">Planificación metodológica de sesiones por grupo y fechas.</p>
        </div>
        <button onclick="openModal('modal-nuevo-cronograma')" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition">
            <span class="material-icons-outlined text-sm">add</span>
            Crear Cronograma
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 mb-6 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex-1 w-full md:w-auto relative">
            <span class="material-icons-outlined absolute left-3 top-2.5 text-gray-400">search</span>
            <input type="text" id="filter-search" placeholder="Buscar por objetivo u observaciones..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none text-sm">
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Grupo:</span>
            <select id="filter-grupo" class="w-full md:w-56 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                <option value="">Todos los grupos</option>
                <?php foreach ($grupos as $g): ?>
                    <option value="<?php echo htmlspecialchars($g['id_grupo']); ?>">
                        <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['sede'] ?? '') . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Tabla Cronogramas -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4">Grupo / Sede</th>
                        <th class="py-3 px-4">Maestro Responsable</th>
                        <th class="py-3 px-4">Objetivo de la Clase</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <?php if (empty($cronogramas)): ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <span class="material-icons-outlined text-4xl block mb-2 text-gray-300">event_note</span>
                                No hay cronogramas creados aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cronogramas as $c): ?>
                            <tr class="hover:bg-gray-50 transition item-cronograma"
                                data-grupo="<?php echo htmlspecialchars($c['id_grupo']); ?>"
                                data-text="<?php echo htmlspecialchars(strtolower(($c['objetivo'] ?? '') . ' ' . ($c['observaciones'] ?? '') . ' ' . ($c['maestro_nombre'] ?? ''))); ?>">
                                <td class="py-3 px-4 font-semibold text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons-outlined text-purple-600 text-sm">calendar_today</span>
                                        <?php echo date('d/m/Y', strtotime($c['fecha'])); ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($c['grupo_nombre'] ?? 'Sin grupo'); ?></span>
                                    <?php if (!empty($c['grupo_sede'])): ?>
                                        <span class="text-xs text-gray-400 block"><?php echo htmlspecialchars($c['grupo_sede']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    <?php echo htmlspecialchars($c['maestro_nombre'] ?? 'No asignado'); ?>
                                </td>
                                <td class="py-3 px-4 text-gray-700 max-w-md">
                                    <p class="line-clamp-2"><?php echo htmlspecialchars($c['objetivo'] ?? 'Sin objetivo específico'); ?></p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo base_url('maestro/cronogramas/' . $c['id_cronograma']); ?>" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg text-xs font-semibold transition" title="Ver fases y ejercicios">
                                            <span class="material-icons-outlined text-sm">visibility</span>
                                            Ver Plan
                                        </a>
                                        <form method="POST" action="<?php echo base_url('maestro/cronogramas/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este cronograma completo?')" class="inline">
                                            <input type="hidden" name="id_cronograma" value="<?php echo $c['id_cronograma']; ?>">
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

<!-- Modal Nuevo Cronograma -->
<div id="modal-nuevo-cronograma" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons-outlined text-purple-600">event_note</span>
                Crear Nuevo Cronograma de Clase
            </h3>
            <button onclick="closeModal('modal-nuevo-cronograma')" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/cronogramas/create'); ?>" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo *</label>
                    <select name="id_grupo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="">Seleccione grupo...</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id_grupo']; ?>">
                                <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['sede'] ?? '') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de la Clase *</label>
                    <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo de la Sesión</label>
                <input type="text" name="objetivo" placeholder="Ej: Mejorar potencia de pateo y resistencia aeróbica..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones Generales</label>
                <textarea name="observaciones" rows="3" placeholder="Requerimientos de material, notas previas..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-nuevo-cronograma')" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                    Crear y Configurar Fases
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

const filterSearch = document.getElementById('filter-search');
const filterGrupo = document.getElementById('filter-grupo');
const itemsCronograma = document.querySelectorAll('.item-cronograma');

function filtrarCronogramas() {
    const q = filterSearch.value.toLowerCase().trim();
    const g = filterGrupo.value;

    itemsCronograma.forEach(el => {
        const text = el.getAttribute('data-text');
        const grupo = el.getAttribute('data-grupo');

        const matchSearch = !q || text.includes(q);
        const matchGrupo = !g || grupo === g;

        el.style.display = (matchSearch && matchGrupo) ? '' : 'none';
    });
}

filterSearch.addEventListener('input', filtrarCronogramas);
filterGrupo.addEventListener('change', filtrarCronogramas);
</script>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

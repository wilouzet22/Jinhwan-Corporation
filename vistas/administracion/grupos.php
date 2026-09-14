<?php
include __DIR__ . '/../layout/administracion_cabecera.php'; 

$total_grupos = count($grupos);
$total_activos = count(array_filter($grupos, fn($g) => ($g['activo'] ?? 0) == 1));
$total_alumnos_grupos = array_sum(array_column($grupos, 'total_estudiantes'));
?>

<main class="flex-grow w-full px-4 py-6 sm:px-6 relative transition-colors duration-300">
    <div class="space-y-6">
        
        <!-- Encabezado y Acciones -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Gestión de Grupos
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Administra los grupos de entrenamiento, asignación de instructores, horarios y sedes.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="openModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">group_add</span>
                    <span>Nuevo Grupo</span>
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Grupos</p>
                    <p class="text-2xl font-display font-bold text-slate-900 dark:text-white mt-1"><?= $total_grupos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-tkd-blue flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">groups</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Grupos Activos</p>
                    <p class="text-2xl font-display font-bold text-emerald-600 dark:text-emerald-400 mt-1"><?= $total_activos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">check_circle</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Total Alumnos Asignados</p>
                    <p class="text-2xl font-display font-bold text-indigo-600 dark:text-indigo-400 mt-1"><?= $total_alumnos_grupos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">school</span>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition-colors">
            <div class="flex flex-wrap items-center gap-3 w-full">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="group-search" placeholder="Buscar grupo u horario..." 
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>

                <select id="filter-sede" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todas las Sedes</option>
                    <?php foreach($sedes_list as $sede): ?>
                        <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="filter-estado" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Estados</option>
                    <option value="Activo">🟢 Activo</option>
                    <option value="Inactivo">🔴 Inactivo</option>
                </select>

                <button id="btn-reset-filters" class="hidden text-xs text-rose-500 hover:text-rose-700 font-bold px-2 py-1 transition-colors focus:outline-none cursor-pointer">
                    ✕ Limpiar Filtros
                </button>
            </div>
        </div>

        <!-- Tabla de Grupos -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full">
                <table class="w-full text-sm text-left border-collapse table-fixed" id="groups-table">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="w-[24%] px-2 py-3">Grupo</th>
                            <th scope="col" class="w-[20%] px-2 py-3">Sede</th>
                            <th scope="col" class="w-[18%] px-2 py-3">Maestro</th>
                            <th scope="col" class="w-[20%] px-2 py-3">Horario</th>
                            <th scope="col" class="w-[5%] min-w-[45px] px-1 py-3 text-center">Alumnos</th>
                            <th scope="col" class="w-[6%] min-w-[65px] px-1 py-3 text-center">Estado</th>
                            <th scope="col" class="w-[7%] min-w-[60px] px-2 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="groups-tbody">
                        <?php if (empty($grupos)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    No hay grupos registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($grupos as $g): ?>
                                <?php 
                                    $sedeCorta = str_replace(['Sede Principal ', 'Sede '], '', $g['nombre_sede'] ?? 'Sin Asignar');
                                ?>
                                <tr class="group-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all" 
                                    data-id="<?= $g['id_grupo'] ?>"
                                    data-nombre="<?= htmlspecialchars(strtolower($g['nombre'] . ' ' . ($g['descripcion'] ?? '') . ' ' . ($g['horario'] ?? ''))) ?>"
                                    data-sede="<?= htmlspecialchars($g['nombre_sede'] ?? '') ?>"
                                    data-estado="<?= ($g['activo'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>">
                                    
                                    <!-- Grupo -->
                                    <td class="px-2 py-2 font-semibold">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-tkd-blue flex items-center justify-center font-bold text-xs shrink-0">
                                                <span class="material-icons-outlined text-base">groups</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-slate-900 dark:text-white font-bold text-xs truncate leading-tight" title="<?= htmlspecialchars($g['nombre']) ?>">
                                                    <?= htmlspecialchars($g['nombre']) ?>
                                                </div>
                                                <?php if (!empty($g['descripcion'])): ?>
                                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate" title="<?= htmlspecialchars($g['descripcion']) ?>">
                                                        <?= htmlspecialchars($g['descripcion']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Sede -->
                                    <td class="px-2 py-2 text-xs min-w-0">
                                        <div class="flex items-center gap-1 font-medium text-slate-800 dark:text-slate-200 truncate" title="<?= htmlspecialchars($g['nombre_sede'] ?? '') ?>">
                                            <span class="material-icons-outlined text-xs text-tkd-blue shrink-0">place</span>
                                            <span class="truncate"><?= htmlspecialchars($sedeCorta) ?></span>
                                        </div>
                                    </td>

                                    <!-- Maestro -->
                                    <td class="px-2 py-2 text-xs min-w-0">
                                        <?php if (!empty($g['nombre_maestro'])): ?>
                                            <div class="flex items-center gap-1 text-slate-800 dark:text-slate-200 font-semibold truncate" title="<?= htmlspecialchars($g['nombre_maestro']) ?>">
                                                <span class="material-icons-outlined text-xs text-purple-600 shrink-0">sports_martial_arts</span>
                                                <span class="truncate"><?= htmlspecialchars($g['nombre_maestro']) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic text-[11px]">Sin asignar</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Horario -->
                                    <td class="px-2 py-2 text-xs text-slate-600 dark:text-slate-300 min-w-0">
                                        <div class="flex items-center gap-1 truncate" title="<?= htmlspecialchars($g['horario'] ?? 'Por definir') ?>">
                                            <span class="material-icons-outlined text-xs text-slate-400 shrink-0">schedule</span>
                                            <span class="truncate text-[11px]"><?= htmlspecialchars($g['horario'] ?? 'Por definir') ?></span>
                                        </div>
                                    </td>

                                    <!-- Total Alumnos -->
                                    <td class="px-1 py-2 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/30">
                                            <span class="material-icons-outlined text-xs">person</span>
                                            <?= (int)($g['total_estudiantes'] ?? 0) ?>
                                        </span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-1 py-2 text-center whitespace-nowrap">
                                        <?php if (($g['activo'] ?? 0) == 1): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800/30">
                                                Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-800/30">
                                                Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-2 py-2 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-0.5">
                                            <!-- Editar -->
                                            <button type="button" onclick='openModal("edit", <?= json_encode($g) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Editar">
                                                <span class="material-icons-outlined text-base">edit</span>
                                            </button>
                                            
                                            <!-- Eliminar -->
                                            <form action="<?= base_url('/admin/grupos/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este grupo?');">
                                                <input type="hidden" name="id" value="<?= $g['id_grupo'] ?>">
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all cursor-pointer" title="Eliminar">
                                                    <span class="material-icons-outlined text-base">delete</span>
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

<!-- MODAL CREAR / EDITAR GRUPO -->
<div id="group-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden transition-all">
        <form id="group-form" method="POST" class="flex flex-col">
            <input type="hidden" name="id" id="modal-id">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-bold text-slate-900 dark:text-white">Nuevo Grupo</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nombre del Grupo *</label>
                    <input type="text" name="nombre" id="modal-nombre" required placeholder="Ej: Grupo A, Semillero..." class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Sede *</label>
                        <select name="id_sede" id="modal-id-sede" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                            <?php foreach($sedes_list as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Maestro Asignado</label>
                        <select name="id_maestro" id="modal-id-maestro" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                            <option value="">Sin Asignar</option>
                            <?php foreach($maestros_list as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre_completo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Horario</label>
                    <input type="text" name="horario" id="modal-horario" placeholder="Ej: Lunes y Miércoles 6:00 PM - 7:30 PM" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Descripción</label>
                    <textarea name="descripcion" id="modal-desc" rows="2" placeholder="Detalles de niveles, edades o enfoque del grupo" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none"></textarea>
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" id="modal-activo" value="1" checked class="w-4 h-4 rounded text-tkd-blue">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Grupo Activo</span>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3 bg-slate-50/50 dark:bg-slate-950/50">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-bold uppercase text-slate-500 hover:text-slate-700 dark:hover:text-white">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold uppercase bg-tkd-blue hover:bg-blue-700 text-white rounded-xl shadow-md">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Filtros
const searchInput = document.getElementById('group-search');
const filterSede = document.getElementById('filter-sede');
const filterEstado = document.getElementById('filter-estado');
const btnReset = document.getElementById('btn-reset-filters');
const rows = document.querySelectorAll('.group-row');

function filterTable() {
    const text = searchInput.value.toLowerCase().trim();
    const sede = filterSede.value;
    const estado = filterEstado.value;

    let hasFilter = text || sede !== 'all' || estado !== 'all';
    btnReset.classList.toggle('hidden', !hasFilter);

    rows.forEach(r => {
        const rNombre = r.dataset.nombre;
        const rSede = r.dataset.sede;
        const rEstado = r.dataset.estado;

        const matchText = !text || rNombre.includes(text);
        const matchSede = sede === 'all' || rSede === sede;
        const matchEstado = estado === 'all' || rEstado === estado;

        r.style.display = (matchText && matchSede && matchEstado) ? '' : 'none';
    });
}

[searchInput, filterSede, filterEstado].forEach(el => {
    el.addEventListener('input', filterTable);
    el.addEventListener('change', filterTable);
});

btnReset.addEventListener('click', () => {
    searchInput.value = '';
    filterSede.value = 'all';
    filterEstado.value = 'all';
    filterTable();
});

// Modal
function openModal(mode, data = null) {
    const modal = document.getElementById('group-modal');
    const form = document.getElementById('group-form');
    const title = document.getElementById('modal-title');

    form.reset();

    if (mode === 'edit' && data) {
        title.textContent = 'Editar Grupo';
        form.action = '<?= base_url('/admin/grupos/update') ?>';
        document.getElementById('modal-id').value = data.id_grupo;
        document.getElementById('modal-nombre').value = data.nombre || '';
        document.getElementById('modal-id-sede').value = data.id_sede || '';
        document.getElementById('modal-id-maestro').value = data.id_maestro || '';
        document.getElementById('modal-horario').value = data.horario || '';
        document.getElementById('modal-desc').value = data.descripcion || '';
        document.getElementById('modal-activo').checked = data.activo == 1;
    } else {
        title.textContent = 'Nuevo Grupo';
        form.action = '<?= base_url('/admin/grupos/create') ?>';
        document.getElementById('modal-id').value = '';
        document.getElementById('modal-activo').checked = true;
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('group-modal').classList.add('hidden');
}
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

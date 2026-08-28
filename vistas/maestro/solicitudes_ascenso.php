<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10 gap-4">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                Gestión de Ascensos de Grado
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">
                Postula alumnos para evaluación de cinturón y consulta el estado de las solicitudes enviadas.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="openMultiPromoModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-display font-bold uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none shrink-0 cursor-pointer">
                <span class="material-icons-outlined text-lg">group_add</span>
                <span>Proponer Ascenso Múltiple</span>
            </button>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 rounded-xl text-emerald-800 dark:text-emerald-400 text-sm font-semibold flex items-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-lg">check_circle</span>
            ¡Solicitud de ascenso enviada con éxito! Queda a la espera de la aprobación del administrador.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900 rounded-xl text-rose-800 dark:text-rose-400 text-sm font-semibold flex items-center gap-2 shadow-sm">
            <span class="material-icons-outlined text-lg">error_outline</span>
            <?php if ($_GET['error'] === 'no_selection'): ?>
                Por favor, selecciona al menos un alumno para proponer el ascenso.
            <?php else: ?>
                No se pudo procesar la solicitud. Asegúrate de haber seleccionado el cinturón solicitado para cada alumno.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- SECCIÓN 1: Listado de Alumnos Disponibles para Ascenso -->
    <div class="mb-10 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-600 animate-pulse"></span>
                <h2 class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Alumnos de la Academia (<?= count($alumnos) ?>)
                </h2>
            </div>
            
            <!-- Buscador en tiempo real de alumnos -->
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-icons-outlined text-sm">search</span>
                </div>
                <input type="text" id="alumno-search-main" placeholder="Buscar alumno por nombre..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none transition-colors">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
            <div class="overflow-x-auto overflow-y-auto max-h-[420px]">
                <table class="w-full text-sm text-left border-collapse" id="alumnos-main-table">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Deportista</th>
                            <th scope="col" class="px-6 py-3.5">Documento</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Cinturón Actual</th>
                            <th scope="col" class="px-6 py-3.5">Sede</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                        <?php if (empty($alumnos)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    <span class="material-icons-outlined text-4xl block mb-2 text-slate-300 dark:text-slate-700">person_off</span>
                                    No hay alumnos activos disponibles.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($alumnos as $alumno): ?>
                                <?php 
                                    $nivelActualId = !empty($alumno['nivel_id']) ? (int)$alumno['nivel_id'] : 1;
                                    $nombreNivelActual = !empty($alumno['nombre_nivel']) ? $alumno['nombre_nivel'] : 'Blanco';
                                ?>
                                <tr class="alumno-row hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all" data-name="<?= htmlspecialchars(strtolower($alumno['nombre'] . ' ' . $alumno['apellido'])) ?>">
                                    <td class="px-6 py-3.5 font-semibold whitespace-nowrap">
                                        <div class="flex items-center">
                                            <?php if (!empty($alumno['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $alumno['foto_perfil']) ?>" class="w-8 h-8 rounded-full object-cover shadow-sm mr-3 shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="h-8 w-8 rounded-full bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-tkd-purple flex items-center justify-center mr-3 font-bold shrink-0 text-xs">
                                                    <?= strtoupper(substr($alumno['nombre'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <span class="text-slate-800 dark:text-white"><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400 text-xs">
                                        <span class="font-mono"><?= htmlspecialchars($alumno['numero_documento'] ?? '-') ?></span>
                                    </td>
                                    <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            <?= htmlspecialchars($nombreNivelActual) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-600 dark:text-slate-400 text-xs">
                                        <?= htmlspecialchars($alumno['nombre_sede'] ?? 'Sin sede') ?>
                                    </td>
                                    <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                        <button type="button" onclick='openSinglePromoModal(<?= json_encode($alumno) ?>)' class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-purple-50 dark:bg-purple-950/40 hover:bg-purple-600 hover:text-white border border-purple-200 dark:border-purple-800/50 text-purple-700 dark:text-purple-300 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                                            <span class="material-icons-outlined text-sm">trending_up</span>
                                            <span>Proponer Ascenso</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: Historial de Solicitudes Enviadas -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            <h2 class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                Historial de Solicitudes Enviadas (<?= count($solicitudes) ?>)
            </h2>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                        <tr>
                            <th scope="col" class="px-6 py-4">Deportista</th>
                            <th scope="col" class="px-6 py-4 text-center">Cambio de Cinturón</th>
                            <th scope="col" class="px-6 py-4">Fecha Solicitud</th>
                            <th scope="col" class="px-6 py-4">Observaciones</th>
                            <th scope="col" class="px-6 py-4 text-center">Estado</th>
                            <th scope="col" class="px-6 py-4">Resolución</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                        <?php if (empty($solicitudes)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    <span class="material-icons-outlined text-4xl block mb-2 text-slate-300 dark:text-slate-700">history_toggle_off</span>
                                    No has enviado ninguna solicitud de ascenso de grado todavía.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitudes as $s): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all">
                                    <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-tkd-purple flex items-center justify-center mr-3 font-bold shrink-0">
                                                <?= strtoupper(substr($s['nombre_alumno'], 0, 1)) ?>
                                            </div>
                                            <span class="text-slate-800 dark:text-white"><?= htmlspecialchars($s['nombre_alumno'] . ' ' . $s['apellido_alumno']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2 text-xs font-semibold">
                                            <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300"><?= htmlspecialchars($s['grado_actual']) ?></span>
                                            <span class="material-icons-outlined text-slate-400 text-sm">arrow_forward</span>
                                            <span class="px-2.5 py-1 rounded bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400"><?= htmlspecialchars($s['grado_solicitado']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <?= date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-300 max-w-[200px] truncate" title="<?= htmlspecialchars($s['observaciones'] ?? '') ?>">
                                        <?= htmlspecialchars($s['observaciones'] ?? 'Sin observaciones') ?>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <?php if ($s['estado'] === 'pendiente'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 uppercase tracking-wider">
                                                Pendiente
                                            </span>
                                        <?php elseif ($s['estado'] === 'aprobado'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 uppercase tracking-wider">
                                                Aprobado
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 uppercase tracking-wider">
                                                Rechazado
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <?= $s['fecha_resolucion'] ? date('d/m/Y', strtotime($s['fecha_resolucion'])) : '---' ?>
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

<!-- Modal Fuera del Main con Alta Prioridad Z-Index -->
<div id="multiPromoModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Overlay Oscuro Sin Blur para máxima nitidez -->
    <div class="fixed inset-0 bg-slate-950/80 transition-opacity" onclick="closeMultiPromoModal()"></div>

    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="relative z-10 bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-4xl w-full border border-slate-200 dark:border-slate-800">
            <form action="<?= base_url('/maestro/solicitudes-ascenso/create') ?>" method="POST" onsubmit="return validateMultiPromo()">
                
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-950/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-icons-outlined text-purple-600">group_add</span>
                            <span id="modal-title-text">Proponer Ascenso Múltiple</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Selecciona los alumnos que deseas postular y elige su nuevo cinturón.</p>
                    </div>
                    <button type="button" onclick="closeMultiPromoModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none cursor-pointer">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    
                    <!-- Buscador rápido dentro del modal -->
                    <div class="relative" id="modal-search-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-icons-outlined text-sm">search</span>
                        </div>
                        <input type="text" id="modal-search-alumno" placeholder="Filtrar alumnos en la lista..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none transition-colors">
                    </div>

                    <div class="max-h-80 overflow-y-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-sm text-left border-collapse" id="promo-table">
                            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-purple-600 focus:ring-purple-500 cursor-pointer" onchange="toggleAll(this)">
                                    </th>
                                    <th class="px-4 py-3">Alumno</th>
                                    <th class="px-4 py-3">Cinturón Actual</th>
                                    <th class="px-4 py-3">Cinturón Solicitado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="promo-tbody">
                                <?php foreach ($alumnos as $alumno): ?>
                                    <?php 
                                        $nivelActualId = !empty($alumno['nivel_id']) ? (int)$alumno['nivel_id'] : 1;
                                        $nombreNivelActual = !empty($alumno['nombre_nivel']) ? $alumno['nombre_nivel'] : 'Blanco';
                                    ?>
                                    <tr class="promo-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors" data-id="<?= $alumno['id'] ?>" data-name="<?= htmlspecialchars(strtolower($alumno['nombre'] . ' ' . $alumno['apellido'])) ?>">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="alumnos_seleccionados[]" value="<?= $alumno['id'] ?>" class="promo-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-purple-600 focus:ring-purple-500 cursor-pointer" onchange="toggleSelect(this, <?= $alumno['id'] ?>)">
                                            <input type="hidden" name="grados_actuales[<?= $alumno['id'] ?>]" value="<?= $nivelActualId ?>">
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                                            <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                <?= htmlspecialchars($nombreNivelActual) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="grados_solicitados[<?= $alumno['id'] ?>]" id="grado_<?= $alumno['id'] ?>" disabled class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none text-xs p-2.5 disabled:opacity-40 disabled:bg-slate-100 dark:disabled:bg-slate-900 transition-colors">
                                                <option value="">Seleccione grado a ascender...</option>
                                                <?php foreach ($grados_list as $grado): ?>
                                                    <?php if ($grado['id'] != $nivelActualId && $grado['id'] != 20): ?>
                                                        <option value="<?= $grado['id'] ?>"><?= htmlspecialchars($grado['nombre']) ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if(empty($alumnos)): ?>
                                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No hay alumnos activos disponibles para ascensos.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <label for="observaciones" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Observaciones Generales (Aplica para todas las propuestas)</label>
                        <textarea id="observaciones" name="observaciones" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none text-sm p-3 resize-none transition-colors" placeholder="Ej: Alumno(s) preparado(s) técnicamente para la evaluación de ascenso..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950/60 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" onclick="closeMultiPromoModal()" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="material-icons-outlined text-sm">send</span>
                        Enviar Propuestas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Buscador principal de alumnos en la tabla superior
    const mainSearchInput = document.getElementById('alumno-search-main');
    if (mainSearchInput) {
        mainSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.alumno-row');
            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                if (!query || name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    function openMultiPromoModal() {
        document.getElementById('modal-title-text').textContent = 'Proponer Ascenso Múltiple';
        
        // Reset rows para que se muestren todos
        const rows = document.querySelectorAll('.promo-row');
        rows.forEach(row => {
            row.style.display = '';
            const cb = row.querySelector('.promo-checkbox');
            if (cb) {
                cb.checked = false;
                toggleSelect(cb, cb.value);
            }
        });
        
        const selectAll = document.getElementById('selectAll');
        if (selectAll) selectAll.checked = false;

        const modalSearch = document.getElementById('modal-search-alumno');
        if (modalSearch) modalSearch.value = '';

        document.getElementById('multiPromoModal').classList.remove('hidden');
    }

    function openSinglePromoModal(alumno) {
        document.getElementById('modal-title-text').textContent = 'Proponer Ascenso: ' + alumno.nombre + ' ' + alumno.apellido;
        
        const rows = document.querySelectorAll('.promo-row');
        rows.forEach(row => {
            const rowId = row.getAttribute('data-id');
            const cb = row.querySelector('.promo-checkbox');
            
            if (rowId == alumno.id) {
                row.style.display = '';
                if (cb) {
                    cb.checked = true;
                    toggleSelect(cb, cb.value);
                    
                    // Preseleccionar el siguiente cinturón
                    const select = document.getElementById('grado_' + alumno.id);
                    if (select && select.options.length > 1) {
                        select.selectedIndex = 1;
                    }
                }
            } else {
                row.style.display = 'none';
                if (cb) {
                    cb.checked = false;
                    toggleSelect(cb, cb.value);
                }
            }
        });

        document.getElementById('multiPromoModal').classList.remove('hidden');
    }

    function closeMultiPromoModal() {
        document.getElementById('multiPromoModal').classList.add('hidden');
    }

    function toggleSelect(checkbox, id) {
        const select = document.getElementById('grado_' + id);
        const row = checkbox.closest('tr');

        if (checkbox.checked) {
            select.disabled = false;
            select.setAttribute('required', 'required');
            row.classList.add('bg-purple-50/80', 'dark:bg-purple-950/40');
        } else {
            select.disabled = true;
            select.removeAttribute('required');
            select.value = '';
            row.classList.remove('bg-purple-50/80', 'dark:bg-purple-950/40');
        }
    }

    function toggleAll(selectAllCheckbox) {
        const rows = document.querySelectorAll('.promo-row');
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.promo-checkbox');
                if (cb) {
                    cb.checked = selectAllCheckbox.checked;
                    toggleSelect(cb, cb.value);
                }
            }
        });
    }

    // Buscador dentro del modal
    const modalSearchInput = document.getElementById('modal-search-alumno');
    if (modalSearchInput) {
        modalSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.promo-row');
            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                if (!query || name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    function validateMultiPromo() {
        const checked = document.querySelectorAll('.promo-checkbox:checked');
        if (checked.length === 0) {
            alert('Por favor, selecciona al menos un alumno para proponer el ascenso.');
            return false;
        }

        for (let cb of checked) {
            const select = document.getElementById('grado_' + cb.value);
            if (!select || !select.value) {
                alert('Por favor, selecciona el cinturón solicitado para todos los alumnos marcados.');
                if (select) select.focus();
                return false;
            }
        }
        return true;
    }
</script>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

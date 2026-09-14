
<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Alumnos de Jinhwan
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Listado completo de deportistas de la academia. Puedes consultar sus detalles y proponer ascensos de grado.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                    <span class="material-icons-outlined text-base">timeline</span>
                    <span>Historial de Solicitudes</span>
                </a>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="flex flex-wrap items-center gap-3 w-full">
                <!-- Buscador por texto -->
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="alumno-search" placeholder="Buscar por nombre o doc..." 
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                </div>

                <!-- Filtro Sede -->
                <?php if (!empty($sedes_list)): ?>
                <select id="filter-sede" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                    <option value="all">Todas las Sedes</option>
                    <?php foreach($sedes_list as $sede): ?>
                        <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <!-- Filtro Cinturón -->
                <?php if (!empty($grados_list)): ?>
                <select id="filter-cinturon" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                    <option value="all">Todos los Cinturones</option>
                    <?php foreach($grados_list as $grado): ?>
                        <option value="<?= htmlspecialchars($grado['nombre']) ?>"><?= htmlspecialchars($grado['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <!-- Filtro Grupo -->
                <?php if (!empty($grupos_list)): ?>
                <select id="filter-grupo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                    <option value="all">Todos los Grupos</option>
                    <?php foreach($grupos_list as $grupo): ?>
                        <option value="<?= htmlspecialchars($grupo['nombre']) ?>"><?= htmlspecialchars($grupo['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <!-- Botón Reset -->
                <button id="btn-reset-filters" class="hidden text-xs text-rose-500 hover:text-rose-700 font-bold px-2 py-1 transition-colors focus:outline-none cursor-pointer">
                    ✕ Limpiar Filtros
                </button>
            </div>
        </div>

        <!-- Tabla de Alumnos de Jinhwan (Compacta y sin scroll horizontal) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full">
                <table class="w-full text-sm text-left border-collapse" id="alumnos-table">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3">Alumno</th>
                            <th scope="col" class="px-3 py-3 text-center">Cinturón Actual</th>
                            <th scope="col" class="px-3 py-3">Sede & Contacto</th>
                            <th scope="col" class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="alumnos-tbody">
                        <?php if(!empty($alumnos)): ?>
                            <?php foreach ($alumnos as $alumno): ?>
                                <?php
                                    $nivel = strtolower($alumno['nombre_nivel'] ?? '');
                                    $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400'; 
                                    
                                    if (str_contains($nivel, 'blanco')) {
                                        $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-600 shadow-xs';
                                    } elseif (str_contains($nivel, 'amarillo')) {
                                        $beltClass = 'bg-yellow-50 text-yellow-800 border border-yellow-300 dark:bg-yellow-950/50 dark:text-yellow-400';
                                    } elseif (str_contains($nivel, 'verde')) {
                                        $beltClass = 'bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-400';
                                    } elseif (str_contains($nivel, 'azul')) {
                                        $beltClass = 'bg-blue-50 text-blue-800 border border-blue-300 dark:bg-blue-950/50 dark:text-blue-400';
                                    } elseif (str_contains($nivel, 'rojo')) {
                                        $beltClass = 'bg-red-50 text-red-800 border border-red-300 dark:bg-red-950/50 dark:text-red-400';
                                    } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                                        $beltClass = 'bg-slate-900 text-amber-400 border border-amber-500/40 dark:bg-slate-950 dark:border-amber-500/50 shadow-xs';
                                    }
                                ?>
                                <tr class="alumno-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all"
                                    data-nombre="<?= htmlspecialchars(strtolower($alumno['nombre'] . ' ' . $alumno['apellido'])) ?>"
                                    data-doc="<?= htmlspecialchars($alumno['numero_documento'] ?? '') ?>"
                                    data-sede="<?= htmlspecialchars($alumno['nombre_sede'] ?? 'Sin Asignar') ?>"
                                    data-cinturon="<?= htmlspecialchars($alumno['nombre_nivel'] ?? 'Sin Asignar') ?>"
                                    data-grupo="<?= htmlspecialchars($alumno['nombre_grupo'] ?? '') ?>">
                                    
                                    <!-- Alumno (Avatar + Nombre + Documento) -->
                                    <td class="px-4 py-2.5 font-semibold">
                                        <div class="flex items-center gap-2.5">
                                            <?php if (!empty($alumno['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $alumno['foto_perfil']) ?>" class="w-8 h-8 rounded-full object-cover shadow-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    <?= strtoupper(substr($alumno['nombre'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="min-w-0">
                                                <div class="text-slate-900 dark:text-white font-bold text-sm truncate leading-tight">
                                                    <?= htmlspecialchars($alumno['nombre']) . ' ' . htmlspecialchars($alumno['apellido']) ?>
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1 mt-0.5">
                                                    <span class="font-mono bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-1 py-0.2 rounded text-[10px] text-slate-500 dark:text-slate-400">CC</span>
                                                    <span><?= htmlspecialchars($alumno['numero_documento'] ?? 'S/D') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Cinturón -->
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                            <?= htmlspecialchars($alumno['nombre_nivel'] ?? 'Sin Asignar') ?>
                                        </span>
                                    </td>

                                    <!-- Sede & Contacto -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="flex items-center gap-1 text-slate-800 dark:text-slate-200 font-medium">
                                            <span class="material-icons-outlined text-sm text-purple-600 shrink-0">place</span>
                                            <span class="truncate"><?= htmlspecialchars($alumno['nombre_sede'] ?? 'Sin Asignar') ?></span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5" title="<?= htmlspecialchars($alumno['correo'] ?? '') ?>">
                                            <?= htmlspecialchars($alumno['telefono'] ? $alumno['telefono'] : ($alumno['correo'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <!-- Acción -->
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 dark:bg-purple-950/40 hover:bg-purple-600 hover:text-white border border-purple-200 dark:border-purple-800/50 text-purple-700 dark:text-purple-300 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                                            <span class="material-icons-outlined text-sm">trending_up</span>
                                            <span>Ascender</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                    No hay alumnos registrados en la academia actualmente.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginación (10 por página) -->
            <div id="pagination-controls" class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Mostrando <span id="page-start-idx" class="font-bold text-slate-800 dark:text-slate-200">1</span> a <span id="page-end-idx" class="font-bold text-slate-800 dark:text-slate-200">10</span> de <span id="total-matching-records" class="font-bold text-slate-800 dark:text-slate-200"><?= count($alumnos) ?></span> alumnos
                </div>

                <div class="flex items-center gap-1.5" id="pagination-buttons">
                    <button type="button" id="btn-prev-page" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span class="material-icons-outlined text-sm">chevron_left</span>
                        <span>Anterior</span>
                    </button>
                    
                    <div id="page-number-buttons" class="flex items-center gap-1">
                        <!-- Botones numéricos generados por JS -->
                    </div>

                    <button type="button" id="btn-next-page" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span>Siguiente</span>
                        <span class="material-icons-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('alumno-search');
    const sedeFilter   = document.getElementById('filter-sede');
    const cinturonFilter = document.getElementById('filter-cinturon');
    const grupoFilter  = document.getElementById('filter-grupo');
    const resetBtn    = document.getElementById('btn-reset-filters');
    const rows        = document.querySelectorAll('.alumno-row');

    let currentPage = 1;
    const pageSize = 10;
    let matchingRows = [];

    function renderPagination() {
        const totalMatching = matchingRows.length;
        const totalPages = Math.max(1, Math.ceil(totalMatching / pageSize));

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }
        if (currentPage < 1) {
            currentPage = 1;
        }

        const startIdx = totalMatching === 0 ? 0 : (currentPage - 1) * pageSize + 1;
        const endIdx = Math.min(currentPage * pageSize, totalMatching);

        const startEl = document.getElementById('page-start-idx');
        const endEl = document.getElementById('page-end-idx');
        const totalEl = document.getElementById('total-matching-records');

        if (startEl) startEl.textContent = startIdx;
        if (endEl) endEl.textContent = endIdx;
        if (totalEl) totalEl.textContent = totalMatching;

        rows.forEach(r => r.style.display = 'none');
        const pageSlice = matchingRows.slice((currentPage - 1) * pageSize, currentPage * pageSize);
        pageSlice.forEach(r => r.style.display = '');

        const btnPrev = document.getElementById('btn-prev-page');
        const btnNext = document.getElementById('btn-next-page');
        if (btnPrev) btnPrev.disabled = (currentPage === 1 || totalMatching === 0);
        if (btnNext) btnNext.disabled = (currentPage === totalPages || totalMatching === 0);

        const pageButtonsContainer = document.getElementById('page-number-buttons');
        if (pageButtonsContainer) {
            pageButtonsContainer.innerHTML = '';
            
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            if (startPage > 1) {
                pageButtonsContainer.appendChild(createPageBtn(1));
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    pageButtonsContainer.appendChild(dots);
                }
            }

            for (let p = startPage; p <= endPage; p++) {
                pageButtonsContainer.appendChild(createPageBtn(p));
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    pageButtonsContainer.appendChild(dots);
                }
                pageButtonsContainer.appendChild(createPageBtn(totalPages));
            }
        }
    }

    function createPageBtn(pageNumber) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = pageNumber;
        const isActive = pageNumber === currentPage;
        btn.className = `w-8 h-8 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center ${
            isActive 
                ? 'bg-purple-600 text-white shadow-md scale-105' 
                : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
        }`;
        btn.addEventListener('click', () => {
            currentPage = pageNumber;
            renderPagination();
            document.getElementById('alumnos-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
        return btn;
    }

    document.getElementById('btn-prev-page')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPagination();
            document.getElementById('alumnos-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    document.getElementById('btn-next-page')?.addEventListener('click', () => {
        const totalPages = Math.ceil(matchingRows.length / pageSize) || 1;
        if (currentPage < totalPages) {
            currentPage++;
            renderPagination();
            document.getElementById('alumnos-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    function filterAlumnos() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const sede  = sedeFilter ? sedeFilter.value : 'all';
        const cinturon = cinturonFilter ? cinturonFilter.value : 'all';
        const grupo = grupoFilter ? grupoFilter.value : 'all';

        matchingRows = [];

        rows.forEach(r => {
            const rNombre   = r.getAttribute('data-nombre') || '';
            const rDoc      = r.getAttribute('data-doc') || '';
            const rSede     = r.getAttribute('data-sede') || '';
            const rCinturon = r.getAttribute('data-cinturon') || '';
            const rGrupo    = r.getAttribute('data-grupo') || '';

            const matchesQuery = !query || rNombre.includes(query) || rDoc.includes(query);
            const matchesSede  = sede === 'all' || rSede === sede;
            const matchesCinturon = cinturon === 'all' || rCinturon === cinturon;
            const matchesGrupo = grupo === 'all' || rGrupo === grupo;

            if (matchesQuery && matchesSede && matchesCinturon && matchesGrupo) {
                matchingRows.push(r);
            }
        });

        if (resetBtn) {
            const isFiltered = query || sede !== 'all' || cinturon !== 'all' || grupo !== 'all';
            resetBtn.classList.toggle('hidden', !isFiltered);
        }

        renderPagination();
    }

    searchInput?.addEventListener('input', () => {
        currentPage = 1;
        filterAlumnos();
    });

    sedeFilter?.addEventListener('change', () => {
        currentPage = 1;
        filterAlumnos();
    });

    cinturonFilter?.addEventListener('change', () => {
        currentPage = 1;
        filterAlumnos();
    });

    grupoFilter?.addEventListener('change', () => {
        currentPage = 1;
        filterAlumnos();
    });

    resetBtn?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (sedeFilter) sedeFilter.value = 'all';
        if (cinturonFilter) cinturonFilter.value = 'all';
        if (grupoFilter) grupoFilter.value = 'all';
        currentPage = 1;
        filterAlumnos();
    });

    filterAlumnos();
});
</script>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

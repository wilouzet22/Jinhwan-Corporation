<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Encabezado con Estadísticas -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Control de Visualización de Perfiles
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Gestiona qué instructores, directivos o alumnos se muestran públicamente en el portal web.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('/miembros') ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 shadow-sm transition-all">
                    <span class="material-icons-outlined text-base text-tkd-blue">visibility</span>
                    Ver Web Pública
                </a>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas Rápidas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Registrados</p>
                    <p class="text-2xl font-display font-bold text-slate-900 dark:text-white mt-1" id="stat-total"><?= $stats['total'] ?? count($miembros) ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-tkd-blue flex items-center justify-center">
                    <span class="material-icons-outlined text-2xl">people</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Visibles en la Web</p>
                    <p class="text-2xl font-display font-bold text-emerald-600 dark:text-emerald-400 mt-1" id="stat-visibles"><?= $stats['visibles'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-2xl">public</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Ocultos / Privados</p>
                    <p class="text-2xl font-display font-bold text-slate-600 dark:text-slate-400 mt-1" id="stat-ocultos"><?= $stats['ocultos'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center">
                    <span class="material-icons-outlined text-2xl">visibility_off</span>
                </div>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto flex-1">
                <!-- Buscador -->
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="filter-search" placeholder="Buscar por nombre..." class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>

                <!-- Filtro de Visibilidad -->
                <select id="filter-visibility" class="w-full sm:w-44 text-sm rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Toda la Visibilidad</option>
                    <option value="1">🟢 Solo Visibles</option>
                    <option value="0">⚪ Solo Ocultos</option>
                </select>

                <!-- Filtro de Rol -->
                <select id="filter-role" class="w-full sm:w-44 text-sm rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Roles</option>
                    <option value="Administracion">Administración</option>
                    <option value="Maestros">Maestros</option>
                    <option value="Profesores">Profesores</option>
                    <option value="Monitores">Monitores</option>
                    <option value="Deportistas">Deportistas</option>
                </select>
            </div>

            <!-- Acciones Masivas -->
            <div id="bulk-controls" class="hidden items-center gap-2 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/50 px-3 py-1.5 rounded-xl">
                <span id="bulk-selected-count" class="text-xs font-bold text-tkd-blue dark:text-blue-400">0 sel.</span>
                <button type="button" onclick="submitBulkVisibility('show')" class="px-2.5 py-1 text-xs font-bold uppercase bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                    Hacer Visibles
                </button>
                <button type="button" onclick="submitBulkVisibility('hide')" class="px-2.5 py-1 text-xs font-bold uppercase bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                    Ocultar
                </button>
            </div>
        </div>

        <!-- Tabla de Control de Perfiles -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="profiles-table">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/60 text-[11px] uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800 transition-colors">
                            <th class="p-4 w-10 text-center">
                                <input type="checkbox" id="check-all" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue cursor-pointer">
                            </th>
                            <th class="p-4 font-semibold">Miembro</th>
                            <th class="p-4 font-semibold text-center">Rol en Web</th>
                            <th class="p-4 font-semibold text-center">Video / Multimedia</th>
                            <th class="p-4 font-semibold text-center">Estado Web</th>
                            <th class="p-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                        <?php if(!empty($miembros)): ?>
                            <?php foreach($miembros as $m): ?>
                                <?php 
                                    $isVisible = (int)($m['mostrar_en_web'] ?? 0) === 1;
                                    $rol = $m['rol_id'] ?? 'Deportistas';
                                ?>
                                <tr class="profile-row hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors" 
                                    data-id="<?= $m['id'] ?>"
                                    data-name="<?= htmlspecialchars(strtolower($m['nombre'] . ' ' . $m['apellido'])) ?>"
                                    data-role="<?= htmlspecialchars($rol) ?>"
                                    data-visible="<?= $isVisible ? '1' : '0' ?>">
                                    
                                    <td class="p-4 text-center">
                                        <input type="checkbox" class="profile-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue cursor-pointer" value="<?= $m['id'] ?>" onchange="updateBulkUI()">
                                    </td>

                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($m['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $m['foto_perfil']) ?>" class="w-10 h-10 rounded-full object-cover shadow-sm shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-tkd-blue to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm shrink-0">
                                                    <?= strtoupper(substr($m['nombre'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                                    <?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?>
                                                </div>
                                                <?php if(!empty($m['descripcion_perfil'])): ?>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-sm" title="<?= htmlspecialchars($m['descripcion_perfil']) ?>">
                                                        <?= htmlspecialchars($m['descripcion_perfil']) ?>
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-xs text-slate-400 dark:text-slate-500 italic">Sin biografía</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            <?= htmlspecialchars($rol) ?>
                                        </span>
                                    </td>

                                    <td class="p-4 text-center">
                                        <?php if(!empty($m['instagram_url'])): ?>
                                            <a href="<?= htmlspecialchars($m['instagram_url']) ?>" target="_blank" class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 dark:text-red-400 font-medium text-xs bg-red-50 dark:bg-red-950/40 px-2.5 py-1 rounded-lg border border-red-200 dark:border-red-800/40 transition-colors" title="Ver video vinculado">
                                                <span class="material-icons-outlined text-sm">play_circle</span>
                                                <span>Video</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 dark:text-slate-600 text-xs italic">Ninguno</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Switch de Visibilidad Instantánea -->
                                    <td class="p-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <button type="button" 
                                                    onclick="toggleMemberVisibility(<?= $m['id'] ?>, this)" 
                                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none <?= $isVisible ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' ?>"
                                                    role="switch" 
                                                    aria-checked="<?= $isVisible ? 'true' : 'false' ?>"
                                                    title="<?= $isVisible ? 'Clic para ocultar de la web' : 'Clic para mostrar en la web' ?>">
                                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out <?= $isVisible ? 'translate-x-5' : 'translate-x-0' ?>"></span>
                                            </button>
                                            <span class="status-label text-[11px] font-bold uppercase tracking-wider <?= $isVisible ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' ?>">
                                                <?= $isVisible ? 'Visible' : 'Oculto' ?>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="p-4 text-right">
                                        <button onclick='openEditModal(<?= json_encode($m) ?>)' class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-tkd-blue hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-transparent hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors focus:outline-none" title="Editar datos del perfil">
                                            <span class="material-icons-outlined text-sm">edit</span>
                                            <span>Editar</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">No hay miembros registrados para gestionar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginación (10 por página) -->
            <div id="pagination-controls" class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Mostrando <span id="page-start-idx" class="font-bold text-slate-800 dark:text-slate-200">1</span> a <span id="page-end-idx" class="font-bold text-slate-800 dark:text-slate-200">10</span> de <span id="total-matching-records" class="font-bold text-slate-800 dark:text-slate-200"><?= count($miembros) ?></span> perfiles
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

<!-- Modal de Edición de Perfil Público -->
<div id="modalEditarPerfil" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-slate-200 dark:border-slate-800 duration-300">
            <form action="<?= base_url('/admin/perfiles-publicos/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-tkd-blue/10 text-tkd-blue flex items-center justify-center">
                            <span class="material-icons-outlined text-lg">badge</span>
                        </div>
                        <h3 class="text-lg font-display font-bold text-slate-900 dark:text-white" id="modal-member-name">
                            Editar Perfil Público
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    
                    <!-- Switch de Visibilidad -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Mostrar en el Portal Web</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">El miembro aparecerá en la sección pública de Miembros.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="mostrar_en_web" id="edit_mostrar" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Rol Público en la Web -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Rol en la Web</label>
                        <select name="rol" id="edit_rol" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:outline-none transition-colors">
                            <option value="Administracion">Administración</option>
                            <option value="Maestros">Maestros</option>
                            <option value="Profesores">Profesores</option>
                            <option value="Monitores">Monitores</option>
                            <option value="Deportistas">Deportistas</option>
                        </select>
                    </div>

                    <!-- Enlace de Video (YouTube/Vimeo) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Enlace de Video (YouTube / Vimeo)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <span class="material-icons-outlined text-sm">ondemand_video</span>
                            </div>
                            <input type="url" name="url_instagram" id="edit_instagram" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:outline-none transition-colors" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">Pega un enlace de YouTube, YouTube Shorts o Vimeo para embeberlo en su tarjeta pública.</p>
                    </div>

                    <!-- Biografía y Logros -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Biografía / Logros en el Club</label>
                        <textarea name="descripcion_perfil" id="edit_descripcion" rows="4" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:outline-none transition-colors resize-none" placeholder="Escribe la reseña deportiva, logros destacados o biografía del miembro..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 transition-colors">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-bold uppercase text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white transition-colors focus:outline-none">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-tkd-blue hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all focus:outline-none">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario para acciones masivas oculto -->
<form id="bulk-form" action="<?= base_url('/admin/perfiles-publicos/bulk') ?>" method="POST" class="hidden">
    <input type="hidden" name="action" id="bulk-action-input">
    <div id="bulk-inputs-container"></div>
</form>

<script>
    // Variables y Paginación (10 por página)
    const searchInput = document.getElementById('filter-search');
    const visibilityFilter = document.getElementById('filter-visibility');
    const roleFilter = document.getElementById('filter-role');
    const rows = document.querySelectorAll('.profile-row');

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

        // Actualizar estadísticas de texto
        const startEl = document.getElementById('page-start-idx');
        const endEl = document.getElementById('page-end-idx');
        const totalEl = document.getElementById('total-matching-records');

        if (startEl) startEl.textContent = startIdx;
        if (endEl) endEl.textContent = endIdx;
        if (totalEl) totalEl.textContent = totalMatching;

        // Ocultar todas las filas y mostrar solo la página activa
        rows.forEach(r => r.style.display = 'none');
        const pageSlice = matchingRows.slice((currentPage - 1) * pageSize, currentPage * pageSize);
        pageSlice.forEach(r => r.style.display = '');

        // Actualizar botones Anterior / Siguiente
        const btnPrev = document.getElementById('btn-prev-page');
        const btnNext = document.getElementById('btn-next-page');
        if (btnPrev) btnPrev.disabled = (currentPage === 1 || totalMatching === 0);
        if (btnNext) btnNext.disabled = (currentPage === totalPages || totalMatching === 0);

        // Generar botones numéricos de página
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
                ? 'bg-tkd-blue text-white shadow-md scale-105' 
                : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
        }`;
        btn.addEventListener('click', () => {
            currentPage = pageNumber;
            renderPagination();
            document.getElementById('profiles-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
        return btn;
    }

    document.getElementById('btn-prev-page')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPagination();
            document.getElementById('profiles-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    document.getElementById('btn-next-page')?.addEventListener('click', () => {
        const totalPages = Math.ceil(matchingRows.length / pageSize) || 1;
        if (currentPage < totalPages) {
            currentPage++;
            renderPagination();
            document.getElementById('profiles-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    function filterProfiles() {
        const query = searchInput.value.toLowerCase().trim();
        const visVal = visibilityFilter.value;
        const roleVal = roleFilter.value;

        matchingRows = [];

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const role = row.getAttribute('data-role') || '';
            const visible = row.getAttribute('data-visible') || '0';

            const matchesQuery = !query || name.includes(query);
            const matchesVis = visVal === 'all' || visible === visVal;
            const matchesRole = roleVal === 'all' || role === roleVal;

            if (matchesQuery && matchesVis && matchesRole) {
                matchingRows.push(row);
            }
        });

        renderPagination();
    }

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        filterProfiles();
    });
    visibilityFilter.addEventListener('change', () => {
        currentPage = 1;
        filterProfiles();
    });
    roleFilter.addEventListener('change', () => {
        currentPage = 1;
        filterProfiles();
    });

    // Inicializar filtrado y paginación
    filterProfiles();

    // Toggle Instantáneo con AJAX
    async function toggleMemberVisibility(memberId, buttonElement) {
        buttonElement.disabled = true;
        
        try {
            const response = await fetch('<?= base_url('/admin/perfiles-publicos/toggle') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: memberId })
            });

            const data = await response.json();
            if (data.success) {
                const isVis = data.visible === 1;
                const row = buttonElement.closest('tr');
                const dot = buttonElement.querySelector('span');
                const label = buttonElement.parentElement.querySelector('.status-label');

                row.setAttribute('data-visible', isVis ? '1' : '0');
                buttonElement.setAttribute('aria-checked', isVis ? 'true' : 'false');
                
                if (isVis) {
                    buttonElement.classList.remove('bg-slate-300', 'dark:bg-slate-700');
                    buttonElement.classList.add('bg-emerald-500');
                    dot.classList.remove('translate-x-0');
                    dot.classList.add('translate-x-5');
                    label.textContent = 'Visible';
                    label.className = 'status-label text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400';
                } else {
                    buttonElement.classList.remove('bg-emerald-500');
                    buttonElement.classList.add('bg-slate-300', 'dark:bg-slate-700');
                    dot.classList.remove('translate-x-5');
                    dot.classList.add('translate-x-0');
                    label.textContent = 'Oculto';
                    label.className = 'status-label text-[11px] font-bold uppercase tracking-wider text-slate-400';
                }

                updateStats();
            } else {
                alert('No se pudo actualizar el estado: ' + (data.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión al actualizar la visibilidad.');
        } finally {
            buttonElement.disabled = false;
        }
    }

    function updateStats() {
        let countVis = 0;
        let countHide = 0;
        rows.forEach(r => {
            if (r.getAttribute('data-visible') === '1') countVis++;
            else countHide++;
        });

        const statVis = document.getElementById('stat-visibles');
        const statHide = document.getElementById('stat-ocultos');
        if (statVis) statVis.textContent = countVis;
        if (statHide) statHide.textContent = countHide;
    }

    // Modal
    function openEditModal(miembro) {
        document.getElementById('edit_id').value = miembro.id;
        document.getElementById('modal-member-name').textContent = (miembro.nombre + ' ' + miembro.apellido).trim();
        document.getElementById('edit_mostrar').checked = (miembro.mostrar_en_web == 1);
        document.getElementById('edit_rol').value = miembro.rol_id || 'Deportistas';
        document.getElementById('edit_instagram').value = miembro.instagram_url || '';
        document.getElementById('edit_descripcion').value = miembro.descripcion_perfil || '';
        
        document.getElementById('modalEditarPerfil').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalEditarPerfil').classList.add('hidden');
    }

    // Checkbox masivo
    const checkAll = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.profile-checkbox');
    const bulkControls = document.getElementById('bulk-controls');
    const bulkCountSpan = document.getElementById('bulk-selected-count');

    if (checkAll) {
        checkAll.addEventListener('change', () => {
            const isChecked = checkAll.checked;
            checkboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row.style.display !== 'none') {
                    cb.checked = isChecked;
                }
            });
            updateBulkUI();
        });
    }

    function updateBulkUI() {
        const checkedBoxes = document.querySelectorAll('.profile-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkControls.classList.remove('hidden');
            bulkControls.classList.add('flex');
            bulkCountSpan.textContent = `${count} sel.`;
        } else {
            bulkControls.classList.add('hidden');
            bulkControls.classList.remove('flex');
            if (checkAll) checkAll.checked = false;
        }
    }

    function submitBulkVisibility(action) {
        const checkedBoxes = document.querySelectorAll('.profile-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        const container = document.getElementById('bulk-inputs-container');
        container.innerHTML = '';

        checkedBoxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('bulk-action-input').value = action;
        document.getElementById('bulk-form').submit();
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

<?php
include __DIR__ . '/../layout/administracion_cabecera.php'; 

$total_estudiantes = count($estudiantes);
$total_activos = count(array_filter($estudiantes, fn($e) => ($e['activo'] ?? 0) == 1));
$total_inactivos = $total_estudiantes - $total_activos;
?>

<main class="flex-grow w-full px-4 py-6 sm:px-6 relative transition-colors duration-300">
    <div class="space-y-6">
        
        <!-- Encabezado y Botones de Acción -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Gestión de Estudiantes
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Administra los alumnos inscritos, grados, grupos y estados académicos.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Barra de Acciones Masivas -->
                <div id="bulk-action-bar" class="hidden items-center gap-2 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/40 rounded-xl px-3 py-1.5 transition-all">
                    <span id="bulk-count" class="text-xs font-bold text-red-700 dark:text-red-400">0 sel.</span>
                    <button type="button" onclick="confirmBulkDelete()" class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase px-2.5 py-1 rounded-lg transition-colors focus:outline-none cursor-pointer">
                        <span class="material-icons-outlined text-sm">delete_sweep</span>
                        <span>Eliminar</span>
                    </button>
                    <button type="button" onclick="clearSelection()" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 px-1 focus:outline-none cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Exportar a Excel -->
                <button type="button" onclick="openExportModal('excel')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">table_view</span>
                    <span class="hidden sm:inline">Excel</span>
                </button>

                <!-- Exportar a PDF -->
                <button type="button" onclick="openExportModal('pdf')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all flex items-center gap-1.5 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">picture_as_pdf</span>
                    <span class="hidden sm:inline">PDF</span>
                </button>

                <!-- Botón Nuevo Estudiante -->
                <button type="button" onclick="openModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">person_add</span>
                    <span>Nuevo Estudiante</span>
                </button>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas Rápidas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Alumnos</p>
                    <p class="text-2xl font-display font-bold text-slate-900 dark:text-white mt-1"><?= $total_estudiantes ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-tkd-blue flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">school</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Activos</p>
                    <p class="text-2xl font-display font-bold text-emerald-600 dark:text-emerald-400 mt-1"><?= $total_activos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">check_circle</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-500">Inactivos</p>
                    <p class="text-2xl font-display font-bold text-rose-500 mt-1"><?= $total_inactivos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-500 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">cancel</span>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="flex flex-wrap items-center gap-3 w-full">
                <!-- Buscador -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="student-search" placeholder="Buscar por nombre o doc..." 
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>

                <!-- Filtro Sede -->
                <select id="filter-sede" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todas las Sedes</option>
                    <?php foreach($sedes_list as $sede): ?>
                        <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro Grado / Cinturón -->
                <select id="filter-nivel" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Grados</option>
                    <?php foreach($niveles_list as $nivel): ?>
                        <option value="<?= htmlspecialchars($nivel['nombre']) ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro Grupo -->
                <select id="filter-grupo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Grupos</option>
                    <?php foreach($grupos_list as $grp): ?>
                        <option value="<?= htmlspecialchars($grp['nombre']) ?>"><?= htmlspecialchars($grp['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro Estado -->
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

        <!-- Gráficos de Distribución -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm flex flex-col transition-colors">
                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Distribución de Cinturones</h3>
                <div class="flex-grow relative w-full flex items-center justify-center min-h-[190px]">
                    <canvas id="chart-cinturones"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm flex flex-col transition-colors">
                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Alumnos por Sede</h3>
                <div class="flex-grow relative w-full flex items-center justify-center min-h-[190px]">
                    <canvas id="chart-sedes"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de Estudiantes -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full">
                <table class="w-full text-sm text-left border-collapse table-fixed" id="students-table">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="w-8 px-1 py-3 text-center">
                                <input type="checkbox" id="select-all-checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                            </th>
                            <th scope="col" class="w-[28%] px-2 py-3">Estudiante</th>
                            <th scope="col" class="w-[12%] min-w-[70px] px-1 py-3 text-center">Grado</th>
                            <th scope="col" class="w-[23%] px-2 py-3">Grupo & Sede</th>
                            <th scope="col" class="w-[15%] px-2 py-3">Contacto</th>
                            <th scope="col" class="w-[10%] min-w-[65px] px-1 py-3 text-center">Estado</th>
                            <th scope="col" class="w-[12%] min-w-[82px] px-2 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="students-tbody">
                        <?php if (empty($estudiantes)): ?>
                            <tr id="empty-row">
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($estudiantes as $est): ?>
                                <?php 
                                    $grado = strtolower($est['grado_nombre'] ?? '');
                                    $beltClass = 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-800 dark:text-slate-300';
                                    if (str_contains($grado, 'blanco')) {
                                        $beltClass = 'bg-slate-50 text-slate-800 border border-slate-300 dark:border-slate-600';
                                    } elseif (str_contains($grado, 'amarillo')) {
                                        $beltClass = 'bg-yellow-50 text-yellow-800 border border-yellow-300 dark:bg-yellow-950/50 dark:text-yellow-400';
                                    } elseif (str_contains($grado, 'verde')) {
                                        $beltClass = 'bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-400';
                                    } elseif (str_contains($grado, 'azul')) {
                                        $beltClass = 'bg-blue-50 text-blue-800 border border-blue-300 dark:bg-blue-950/50 dark:text-blue-400';
                                    } elseif (str_contains($grado, 'rojo')) {
                                        $beltClass = 'bg-red-50 text-red-800 border border-red-300 dark:bg-red-950/50 dark:text-red-400';
                                    } elseif (str_contains($grado, 'negro') || str_contains($grado, 'dan')) {
                                        $beltClass = 'bg-slate-900 text-amber-400 border border-amber-500/40 dark:bg-slate-950 dark:border-amber-500/50';
                                    }
                                    $sedeCorta = str_replace(['Sede Principal ', 'Sede '], '', $est['sede_nombre'] ?? 'Sin Asignar');
                                ?>
                                <tr class="student-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all" 
                                    data-id="<?= $est['id'] ?>"
                                    data-nombre="<?= htmlspecialchars(strtolower($est['nombre'] . ' ' . $est['apellido'])) ?>"
                                    data-doc="<?= htmlspecialchars($est['numero_documento'] ?? '') ?>"
                                    data-sede="<?= htmlspecialchars($est['sede_nombre'] ?? 'Sin Asignar') ?>"
                                    data-nivel="<?= htmlspecialchars($est['grado_nombre'] ?? 'Sin Asignar') ?>"
                                    data-grupo="<?= htmlspecialchars($est['grupo_nombre'] ?? 'Sin Asignar') ?>"
                                    data-estado="<?= ($est['activo'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>">
                                    
                                    <td class="px-1 py-2 text-center">
                                        <input type="checkbox" name="student-checkbox" value="<?= $est['id'] ?>"
                                               class="student-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                                    </td>

                                    <!-- Estudiante (Avatar + Nombre + Doc) -->
                                    <td class="px-2 py-2 font-semibold">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <?php if (!empty($est['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $est['foto_perfil']) ?>" class="w-7 h-7 rounded-full object-cover shadow-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    <?= strtoupper(substr($est['nombre'] ?? 'E', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-slate-900 dark:text-white font-bold text-xs truncate leading-tight" title="<?= htmlspecialchars($est['nombre'] . ' ' . $est['apellido']) ?>">
                                                    <?= htmlspecialchars($est['nombre'] ?? '') . ' ' . htmlspecialchars($est['apellido'] ?? '') ?>
                                                </div>
                                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1 mt-0.5 truncate">
                                                    <span class="font-mono bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-1 rounded text-[9px] shrink-0"><?= htmlspecialchars($est['tipo_documento'] ?? 'TI') ?></span>
                                                    <span class="truncate"><?= htmlspecialchars($est['numero_documento'] ?? 'S/D') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Grado -->
                                    <td class="px-1 py-2 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                            <?= htmlspecialchars($est['grado_nombre'] ?? 'Sin Asignar') ?>
                                        </span>
                                    </td>

                                    <!-- Grupo & Sede -->
                                    <td class="px-2 py-2 text-xs min-w-0">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 truncate leading-tight" title="<?= htmlspecialchars($est['grupo_nombre'] ?? 'Sin Grupo') ?>">
                                            <?= htmlspecialchars($est['grupo_nombre'] ?? 'Sin Grupo') ?>
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5 truncate" title="<?= htmlspecialchars($est['sede_nombre'] ?? '') ?>">
                                            <span class="material-icons-outlined text-xs text-tkd-blue shrink-0">place</span>
                                            <span class="truncate"><?= htmlspecialchars($sedeCorta) ?></span>
                                        </div>
                                    </td>

                                    <!-- Contacto -->
                                    <td class="px-2 py-2 text-xs min-w-0">
                                        <?php if (!empty($est['telefono'])): ?>
                                            <div class="text-slate-800 dark:text-slate-300 font-medium truncate text-[11px]"><?= htmlspecialchars($est['telefono']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($est['correo'])): ?>
                                            <div class="text-[10px] text-slate-400 truncate" title="<?= htmlspecialchars($est['correo']) ?>"><?= htmlspecialchars($est['correo']) ?></div>
                                        <?php endif; ?>
                                        <?php if (empty($est['telefono']) && empty($est['correo'])): ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-1 py-2 text-center whitespace-nowrap">
                                        <?php if (($est['activo'] ?? 0) == 1): ?>
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
                                            <!-- Ver Detalle -->
                                            <button type="button" onclick='openDetailModal(<?= json_encode($est) ?>)' class="text-slate-500 hover:text-tkd-blue dark:text-slate-400 dark:hover:text-blue-400 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Ver Detalle">
                                                <span class="material-icons-outlined text-base">visibility</span>
                                            </button>
                                            
                                            <!-- Editar -->
                                            <button type="button" onclick='openModal("edit", <?= json_encode($est) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Editar">
                                                <span class="material-icons-outlined text-base">edit</span>
                                            </button>
                                            
                                            <!-- Eliminar -->
                                            <form action="<?= base_url('/admin/estudiantes/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar a este estudiante?');">
                                                <input type="hidden" name="id" value="<?= $est['id'] ?>">
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

            <!-- Controles de Paginación (10 por página) -->
            <div id="pagination-controls" class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Mostrando <span id="page-start-idx" class="font-bold text-slate-800 dark:text-slate-200">1</span> a <span id="page-end-idx" class="font-bold text-slate-800 dark:text-slate-200">10</span> de <span id="total-matching-records" class="font-bold text-slate-800 dark:text-slate-200"><?= count($estudiantes) ?></span> estudiantes
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

<!-- MODAL CREAR / EDITAR ESTUDIANTE -->
<div id="student-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-950/80 transition-opacity" onclick="closeModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="relative z-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl text-left transition-colors">
            
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-950/50">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-blue text-xl">person</span>
                    <h2 id="modal-title" class="text-lg font-display font-bold text-slate-900 dark:text-white uppercase">Añadir Nuevo Estudiante</h2>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <form id="student-form" action="<?= base_url('/admin/estudiantes/create') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <input type="hidden" name="id" id="modal-id">
                
                <!-- Sección 1: Datos Personales -->
                <div>
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">badge</span>
                        <span>1. Datos Personales</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal-nombre" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Nombre *</label>
                            <input type="text" name="nombre" id="modal-nombre" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="modal-apellido" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Apellido *</label>
                            <input type="text" name="apellido" id="modal-apellido" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="modal-tipo-doc" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Tipo Documento *</label>
                            <select name="tipo_documento" id="modal-tipo-doc" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <option value="TI">Tarjeta de Identidad (T.I)</option>
                                <option value="CC">Cédula de Ciudadanía (C.C)</option>
                                <option value="RC">Registro Civil (R.C)</option>
                                <option value="CE">Cédula de Extranjería (C.E)</option>
                                <option value="PASAPORTE">Pasaporte</option>
                            </select>
                        </div>
                        <div>
                            <label for="modal-num-doc" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Número Documento *</label>
                            <input type="text" name="numero_documento" id="modal-num-doc" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="modal-fnac" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="modal-fnac" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Contacto -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">contact_mail</span>
                        <span>2. Contacto</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal-telefono" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Teléfono Móvil</label>
                            <input type="tel" name="telefono" id="modal-telefono" placeholder="Ej. 3001234567" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="modal-correo" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Correo Electrónico *</label>
                            <input type="email" name="correo" id="modal-correo" required placeholder="alumno@jinhwan.com" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Datos de Academia -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">military_tech</span>
                        <span>3. Academia y Grados</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="modal-id-grupo" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Grupo de Entrenamiento *</label>
                            <select name="id_grupo" id="modal-id-grupo" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($grupos_list as $grp): ?>
                                    <option value="<?= $grp['id_grupo'] ?>"><?= htmlspecialchars($grp['nombre']) ?> (<?= htmlspecialchars($grp['sede_nombre'] ?? '') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="modal-nivel-id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Grado / Cinturón *</label>
                            <select name="nivel_id" id="modal-nivel-id" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($niveles_list as $niv): ?>
                                    <option value="<?= $niv['id'] ?>"><?= htmlspecialchars($niv['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="modal-categoria-id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Categoría</label>
                            <select name="categoria_id" id="modal-categoria-id" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($categorias_list as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="modal-division" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">División de Peso</label>
                            <input type="text" name="division" id="modal-division" placeholder="Ej. Minimosca -54kg" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección 4: Salud -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">health_and_safety</span>
                        <span>4. Información Médica</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="modal-peso" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Peso (kg)</label>
                            <input type="number" step="0.01" name="peso" id="modal-peso" placeholder="Ej. 65.5" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="modal-eps" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">EPS</label>
                            <input type="text" name="eps" id="modal-eps" placeholder="Ej. Sura, Sanitas" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="modal-rh" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Grupo Sanguíneo (RH)</label>
                            <select name="rh" id="modal-rh" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <option value="">Seleccionar RH</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección 5: Foto y Credenciales -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">manage_accounts</span>
                        <span>5. Perfil y Acceso</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Foto de Perfil</label>
                            <div class="flex items-center gap-4">
                                <div id="foto-preview-container" class="h-14 w-14 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-100 dark:bg-slate-950 flex items-center justify-center shrink-0">
                                    <span id="foto-preview-placeholder" class="material-icons-outlined text-slate-400 text-xl font-bold">person</span>
                                    <img id="foto-preview-img" class="h-full w-full object-cover hidden" alt="Foto">
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300">
                                    <div id="delete-foto-container" class="mt-1.5 hidden">
                                        <label class="flex items-center space-x-2 cursor-pointer text-xs text-rose-600">
                                            <input type="checkbox" name="eliminar_foto" id="eliminar_foto" value="1" class="rounded text-rose-600 border-slate-300 focus:ring-rose-500">
                                            <span>Eliminar foto actual</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="modal-clave" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Contraseña</label>
                                <input type="password" name="clave" id="modal-clave" placeholder="Por defecto: jinhwa2024" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <span id="clave-hint" class="hidden text-[10px] text-slate-400 mt-1 block">Dejar en blanco para mantener la contraseña actual.</span>
                            </div>
                            <div class="flex items-center pt-5">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="activo" id="modal-activo" value="1" checked class="h-4 w-4 text-tkd-blue rounded border-slate-300 focus:ring-tkd-blue">
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Activo en la Academia</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones del Formulario -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all focus:outline-none cursor-pointer">
                        Guardar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalle Completo del Estudiante -->
<div id="student-detail-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-950/80 transition-opacity" onclick="closeDetailModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="relative z-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-2xl max-h-[92vh] overflow-y-auto shadow-2xl text-left transition-colors">
            
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-950/50">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl border-2 border-white dark:border-slate-800 shadow-md overflow-hidden bg-slate-100 dark:bg-slate-950 flex items-center justify-center shrink-0">
                        <img id="detail-foto-img" class="h-full w-full object-cover hidden" alt="Foto">
                        <span id="detail-foto-initial" class="text-2xl font-display font-bold text-slate-700 dark:text-slate-300"></span>
                    </div>
                    <div>
                        <h2 id="detail-fullname" class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight"></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="detail-badge-grado" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
                            <span id="detail-badge-estado" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-2xl">close</span>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Grid de Datos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- Datos Personales -->
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 space-y-2.5 text-xs">
                        <p class="font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider text-[11px] mb-2">Datos Personales</p>
                        <div class="flex justify-between"><span class="text-slate-400">Documento:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-documento"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Nacimiento:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-fecha-n"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Edad:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-edad"></span></div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 space-y-2.5 text-xs">
                        <p class="font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider text-[11px] mb-2">Contacto</p>
                        <div class="flex justify-between"><span class="text-slate-400">Teléfono:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-telefono"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Email:</span> <span class="font-bold text-slate-800 dark:text-slate-200 truncate max-w-[150px]" id="detail-correo"></span></div>
                    </div>

                    <!-- Información Deportiva -->
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 space-y-2.5 text-xs">
                        <p class="font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider text-[11px] mb-2">Deporte</p>
                        <div class="flex justify-between"><span class="text-slate-400">Sede:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-sede"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Grado:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-grado"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Grupo:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-grupo"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Categoría:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-categoria"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">División:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-division"></span></div>
                    </div>

                    <!-- Salud -->
                    <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 space-y-2.5 text-xs">
                        <p class="font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider text-[11px] mb-2">Salud</p>
                        <div class="flex justify-between"><span class="text-slate-400">EPS:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-eps"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">RH:</span> <span class="font-bold text-slate-800 dark:text-slate-200 font-mono" id="detail-rh"></span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Peso:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-peso"></span></div>
                    </div>

                </div>
            </div>

            <div class="p-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 bg-slate-50 dark:bg-slate-950/50">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors cursor-pointer">
                    Cerrar
                </button>
                <button type="button" id="detail-btn-edit" class="px-5 py-2 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl shadow transition cursor-pointer flex items-center gap-1">
                    <span class="material-icons-outlined text-sm">edit</span>
                    <span>Editar</span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Exportación -->
<div id="export-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-950/80 transition-opacity" onclick="closeExportModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="relative z-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl text-left transition-colors">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div id="export-modal-icon" class="h-9 w-9 rounded-xl flex items-center justify-center">
                        <span class="material-icons-outlined text-white text-lg"></span>
                    </div>
                    <div>
                        <h2 class="text-sm font-display font-bold text-slate-900 dark:text-white uppercase" id="export-modal-title">Exportar Datos</h2>
                        <p class="text-[11px] text-slate-400">Selecciona las columnas que deseas incluir.</p>
                    </div>
                </div>
                <button type="button" onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-slate-500 dark:text-slate-400">Columnas a exportar</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAllExportCols()" class="text-tkd-blue dark:text-blue-400 font-bold hover:underline">Todas</button>
                        <span class="text-slate-300">|</span>
                        <button type="button" onclick="deselectAllExportCols()" class="text-slate-400 font-bold hover:underline">Ninguna</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs border border-slate-200 dark:border-slate-800 p-3 rounded-xl max-h-56 overflow-y-auto">
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="nombre" checked><span>Nombre</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="documento" checked><span>Documento</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="cinturon" checked><span>Grado</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="sede" checked><span>Sede</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="grupo" checked><span>Grupo</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="telefono" checked><span>Teléfono</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="correo" checked><span>Correo</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="categoria"><span>Categoría</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="division"><span>División</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="eps"><span>EPS</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="rh"><span>RH</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="peso"><span>Peso</span></label>
                </div>
            </div>

            <div class="p-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button type="button" onclick="closeExportModal()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="button" id="export-confirm-btn" onclick="confirmExport()" class="px-5 py-2 font-bold uppercase text-xs text-white rounded-xl shadow transition cursor-pointer flex items-center gap-1.5">
                    <span class="material-icons-outlined text-base" id="export-confirm-icon"></span>
                    <span id="export-confirm-label">Exportar</span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Formulario para eliminación masiva -->
<form id="bulk-delete-form" action="<?= base_url('/admin/estudiantes/delete-bulk') ?>" method="POST" class="hidden">
    <div id="bulk-delete-ids"></div>
</form>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    const ALL_STUDENTS_DATA = <?= json_encode(array_values($estudiantes)) ?>;
    const BELT_ORDER = <?= json_encode(array_column($niveles_list, 'nombre')) ?>;
    let activeStudentData = null;
    let currentExportType = 'excel';

    // Funciones de Modal
    function openModal(action, data = null) {
        const modal = document.getElementById('student-modal');
        const form = document.getElementById('student-form');
        const title = document.getElementById('modal-title');
        const claveHint = document.getElementById('clave-hint');
        
        modal.classList.remove('hidden');
        form.reset();
        
        if (action === 'add') {
            title.textContent = 'Añadir Nuevo Estudiante';
            form.action = '<?= base_url('/admin/estudiantes/create') ?>';
            document.getElementById('modal-id').value = '';
            document.getElementById('modal-tipo-doc').value = 'TI';
            document.getElementById('modal-activo').checked = true;
            claveHint.classList.add('hidden');
            document.getElementById('foto-preview-img').classList.add('hidden');
            document.getElementById('foto-preview-placeholder').classList.remove('hidden');
            document.getElementById('delete-foto-container').classList.add('hidden');
        } else if (action === 'edit' && data) {
            title.textContent = 'Editar Estudiante: ' + (data.nombre || '') + ' ' + (data.apellido || '');
            form.action = '<?= base_url('/admin/estudiantes/update') ?>';
            
            document.getElementById('modal-id').value = data.id;
            document.getElementById('modal-nombre').value = data.nombre || '';
            document.getElementById('modal-apellido').value = data.apellido || '';
            document.getElementById('modal-tipo-doc').value = data.tipo_documento || 'TI';
            document.getElementById('modal-num-doc').value = data.numero_documento || '';
            document.getElementById('modal-fnac').value = data.fecha_nacimiento || '';
            document.getElementById('modal-telefono').value = data.telefono || '';
            document.getElementById('modal-correo').value = data.correo || '';
            if (data.id_grupo) document.getElementById('modal-id-grupo').value = data.id_grupo;
            if (data.id_grado) document.getElementById('modal-nivel-id').value = data.id_grado;
            if (data.id_categoria) document.getElementById('modal-categoria-id').value = data.id_categoria;
            document.getElementById('modal-division').value = data.division || '';
            document.getElementById('modal-peso').value = data.peso || '';
            document.getElementById('modal-eps').value = data.eps || '';
            document.getElementById('modal-rh').value = data.rh || '';
            document.getElementById('modal-activo').checked = (data.activo == 1);
            claveHint.classList.remove('hidden');

            const previewImg = document.getElementById('foto-preview-img');
            const previewPlaceholder = document.getElementById('foto-preview-placeholder');
            const deleteContainer = document.getElementById('delete-foto-container');
            document.getElementById('eliminar_foto').checked = false;
            
            if (data.foto_perfil) {
                previewImg.src = '<?= base_url("public/uploads/perfiles/") ?>' + data.foto_perfil;
                previewImg.classList.remove('hidden');
                previewPlaceholder.classList.add('hidden');
                deleteContainer.classList.remove('hidden');
            } else {
                previewImg.classList.add('hidden');
                previewPlaceholder.classList.remove('hidden');
                deleteContainer.classList.add('hidden');
            }
        }
    }

    function closeModal() {
        document.getElementById('student-modal').classList.add('hidden');
    }

    // Modal Detalle
    function openDetailModal(data) {
        activeStudentData = data;
        const modal = document.getElementById('student-detail-modal');
        modal.classList.remove('hidden');

        document.getElementById('detail-fullname').textContent = ((data.nombre || '') + ' ' + (data.apellido || '')).trim();
        
        const badgeGrado = document.getElementById('detail-badge-grado');
        badgeGrado.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-tkd-blue border border-blue-200 dark:bg-blue-950/60 dark:text-blue-400";
        badgeGrado.textContent = data.grado_nombre || 'Sin Grado';

        const badgeEstado = document.getElementById('detail-badge-estado');
        badgeEstado.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ";
        if (data.activo == 1) {
            badgeEstado.textContent = "Activo";
            badgeEstado.classList.add("bg-emerald-50", "text-emerald-700", "border", "border-emerald-200", "dark:bg-emerald-950/60", "dark:text-emerald-400");
        } else {
            badgeEstado.textContent = "Inactivo";
            badgeEstado.classList.add("bg-rose-50", "text-rose-700", "border", "border-rose-200", "dark:bg-rose-950/60", "dark:text-rose-400");
        }

        const fotoImg = document.getElementById('detail-foto-img');
        const fotoInitial = document.getElementById('detail-foto-initial');
        if (data.foto_perfil) {
            fotoImg.src = '<?= base_url("public/uploads/perfiles/") ?>' + data.foto_perfil;
            fotoImg.classList.remove('hidden');
            fotoInitial.classList.add('hidden');
        } else {
            fotoImg.classList.add('hidden');
            fotoInitial.textContent = (data.nombre || 'E').substring(0, 1).toUpperCase();
            fotoInitial.classList.remove('hidden');
        }

        document.getElementById('detail-documento').textContent = (data.tipo_documento || 'TI') + ' ' + (data.numero_documento || '');
        document.getElementById('detail-fecha-n').textContent = data.fecha_nacimiento || 'Sin registrar';
        document.getElementById('detail-edad').textContent = calculateAge(data.fecha_nacimiento) + ' años';
        document.getElementById('detail-telefono').textContent = data.telefono || 'Sin registrar';
        document.getElementById('detail-correo').textContent = data.correo || 'Sin registrar';
        document.getElementById('detail-sede').textContent = data.sede_nombre || 'Sin Asignar';
        document.getElementById('detail-grado').textContent = data.grado_nombre || 'Sin Asignar';
        document.getElementById('detail-grupo').textContent = data.grupo_nombre || 'Sin Asignar';
        document.getElementById('detail-categoria').textContent = data.categoria_nombre || 'Sin Asignar';
        document.getElementById('detail-division').textContent = data.division || 'Sin Asignar';
        document.getElementById('detail-eps').textContent = data.eps || 'Sin registrar';
        document.getElementById('detail-rh').textContent = data.rh || 'Sin registrar';
        document.getElementById('detail-peso').textContent = data.peso ? (data.peso + ' kg') : 'Sin registrar';
    }

    function closeDetailModal() {
        document.getElementById('student-detail-modal').classList.add('hidden');
        activeStudentData = null;
    }

    document.getElementById('detail-btn-edit')?.addEventListener('click', () => {
        if (activeStudentData) {
            const d = {...activeStudentData};
            closeDetailModal();
            openModal('edit', d);
        }
    });

    function calculateAge(birthdayStr) {
        if (!birthdayStr) return 0;
        const b = new Date(birthdayStr);
        const t = new Date();
        let age = t.getFullYear() - b.getFullYear();
        const m = t.getMonth() - b.getMonth();
        if (m < 0 || (m === 0 && t.getDate() < b.getDate())) age--;
        return Math.max(0, age);
    }

    // Filtrado en vivo, Paginación y Gráficos
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput  = document.getElementById('student-search');
        const sedeSelect   = document.getElementById('filter-sede');
        const nivelSelect  = document.getElementById('filter-nivel');
        const grupoSelect  = document.getElementById('filter-grupo');
        const estadoSelect = document.getElementById('filter-estado');
        const resetBtn     = document.getElementById('btn-reset-filters');
        const rows         = document.querySelectorAll('.student-row');

        // Configuración de Colores y Tramas de Cinturones de Taekwondo
        const beltColors = {
            'pinta verde':    { bg: '#dcfce7', stripe: '#22c55e', border: '#16a34a', isPattern: true },
            'punta verde':    { bg: '#dcfce7', stripe: '#22c55e', border: '#16a34a', isPattern: true },
            'pinta amarilla': { bg: '#fef9c3', stripe: '#eab308', border: '#ca8a04', isPattern: true },
            'punta amarilla': { bg: '#fef9c3', stripe: '#eab308', border: '#ca8a04', isPattern: true },
            'pinta amarillo': { bg: '#fef9c3', stripe: '#eab308', border: '#ca8a04', isPattern: true },
            'punta amarillo': { bg: '#fef9c3', stripe: '#eab308', border: '#ca8a04', isPattern: true },
            'pinta azul':     { bg: '#dbeafe', stripe: '#3b82f6', border: '#2563eb', isPattern: true },
            'punta azul':     { bg: '#dbeafe', stripe: '#3b82f6', border: '#2563eb', isPattern: true },
            'pinta roja':     { bg: '#fee2e2', stripe: '#ef4444', border: '#dc2626', isPattern: true },
            'punta roja':     { bg: '#fee2e2', stripe: '#ef4444', border: '#dc2626', isPattern: true },
            'pinta rojo':     { bg: '#fee2e2', stripe: '#ef4444', border: '#dc2626', isPattern: true },
            'punta rojo':     { bg: '#fee2e2', stripe: '#ef4444', border: '#dc2626', isPattern: true },
            'pinta negra':    { bg: '#64748b', stripe: '#1e293b', border: '#475569', isPattern: true },
            'punta negra':    { bg: '#64748b', stripe: '#1e293b', border: '#475569', isPattern: true },
            'blanco':         { bg: '#ffffff', border: '#cbd5e1', isPattern: false },
            'amarillo':       { bg: '#eab308', border: '#ca8a04', isPattern: false },
            'verde':          { bg: '#22c55e', border: '#16a34a', isPattern: false },
            'azul':           { bg: '#3b82f6', border: '#2563eb', isPattern: false },
            'rojo':           { bg: '#ef4444', border: '#dc2626', isPattern: false },
            'negro':          { bg: '#1e293b', border: '#475569', isPattern: false },
            'dan':            { bg: '#334155', border: '#1e293b', isPattern: false },
            'default':        { bg: '#ffffff', border: '#cbd5e1', isPattern: false }
        };

        function createStripePattern(baseColor, stripeColor) {
            const canvas = document.createElement('canvas');
            canvas.width = 14;
            canvas.height = 14;
            const ctx = canvas.getContext('2d');

            ctx.fillStyle = baseColor;
            ctx.fillRect(0, 0, 14, 14);

            ctx.strokeStyle = stripeColor;
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.moveTo(-2, 16);
            ctx.lineTo(16, -2);
            ctx.stroke();
            
            const tempCtx = document.createElement('canvas').getContext('2d');
            return tempCtx.createPattern(canvas, 'repeat');
        }

        const isDark = document.documentElement.classList.contains('dark');
        const tickColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';

        const ctxC = document.getElementById('chart-cinturones').getContext('2d');
        const chartCinturones = new Chart(ctxC, {
            type: 'doughnut',
            data: { labels: [], datasets: [{ data: [], backgroundColor: [], borderColor: isDark ? '#0f172a' : '#ffffff', borderWidth: 2, hoverOffset: 6 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: { 
                    legend: { 
                        position: 'right', 
                        labels: { 
                            color: tickColor, 
                            font: { size: 10, weight: '600' },
                            padding: 6,
                            usePointStyle: false
                        } 
                    } 
                }
            }
        });

        const ctxS = document.getElementById('chart-sedes').getContext('2d');
        const chartSedes = new Chart(ctxS, {
            type: 'bar',
            data: { labels: [], datasets: [{ label: 'Estudiantes', data: [], backgroundColor: '#3b82f6', borderRadius: 8 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: { color: tickColor, font: { size: 9 }, maxRotation: 20, minRotation: 15 } 
                    },
                    y: { 
                        grid: { color: gridColor },
                        ticks: { color: tickColor, font: { size: 9 }, stepSize: 1 }, 
                        beginAtZero: true 
                    }
                }
            }
        });

        // Orden jerárquico de cinturones
        const DEFAULT_BELT_ORDER = [
            'Blanco', 'Punta Amarilla', 'Pinta Amarilla', 'Amarillo', 
            'Punta Verde', 'Pinta Verde', 'Verde', 
            'Punta Azul', 'Pinta Azul', 'Azul', 
            'Punta Roja', 'Pinta Roja', 'Rojo', 
            'Punta Negra', 'Pinta Negra', 'Negro 1er Dan', 'Negro 2do Dan', 
            'Negro 3er Dan', 'Negro 4to Dan', 'Negro 5to Dan', 'Negro 6to Dan', 
            'Negro 7mo Dan', 'Negro 8vo Dan', 'Negro 9no Dan', 'Dan', 'Negro'
        ];
        const effectiveBeltOrder = (BELT_ORDER && BELT_ORDER.length > 0) ? BELT_ORDER : DEFAULT_BELT_ORDER;

        // Variables de Paginación (10 por página)
        let currentPage = 1;
        const pageSize = 10;
        let matchingRows = [];

        function renderPagination() {
            const totalMatching = matchingRows.length;
            const totalPages = Math.max(1, Math.ceil(totalMatching / pageSize));

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

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
                    ? 'bg-tkd-blue text-white shadow-md scale-105' 
                    : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
            }`;
            btn.addEventListener('click', () => {
                currentPage = pageNumber;
                renderPagination();
                document.getElementById('students-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
            return btn;
        }

        document.getElementById('btn-prev-page')?.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPagination();
                document.getElementById('students-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });

        document.getElementById('btn-next-page')?.addEventListener('click', () => {
            const totalPages = Math.ceil(matchingRows.length / pageSize) || 1;
            if (currentPage < totalPages) {
                currentPage++;
                renderPagination();
                document.getElementById('students-table')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });

        function filterStudents() {
            const query = searchInput.value.toLowerCase().trim();
            const sede  = sedeSelect.value;
            const nivel = nivelSelect.value;
            const grupo = grupoSelect.value;
            const estado= estadoSelect.value;

            matchingRows = [];

            rows.forEach(r => {
                const rNombre = r.getAttribute('data-nombre') || '';
                const rDoc    = r.getAttribute('data-doc') || '';
                const rSede   = r.getAttribute('data-sede') || '';
                const rNivel  = r.getAttribute('data-nivel') || '';
                const rGrupo  = r.getAttribute('data-grupo') || '';
                const rEstado = r.getAttribute('data-estado') || '';

                const matchesQuery = !query || rNombre.includes(query) || rDoc.includes(query);
                const matchesSede  = sede === 'all' || rSede === sede;
                const matchesNivel = nivel === 'all' || rNivel === nivel;
                const matchesGrupo = grupo === 'all' || rGrupo === grupo;
                const matchesEstado= estado === 'all' || rEstado === estado;

                if (matchesQuery && matchesSede && matchesNivel && matchesGrupo && matchesEstado) {
                    matchingRows.push(r);
                }
            });

            const isFiltered = query || sede !== 'all' || nivel !== 'all' || grupo !== 'all' || estado !== 'all';
            resetBtn.classList.toggle('hidden', !isFiltered);

            renderPagination();
            updateCharts(matchingRows);
        }

        function updateCharts(visibleRows) {
            const cintMap = {};
            const sedeMap = {};

            visibleRows.forEach(r => {
                const c = r.getAttribute('data-nivel') || 'Sin Asignar';
                const s = r.getAttribute('data-sede') || 'Sin Asignar';
                cintMap[c] = (cintMap[c] || 0) + 1;
                sedeMap[s] = (sedeMap[s] || 0) + 1;
            });

            // Ordenar cinturones
            const cLabels = Object.keys(cintMap);
            cLabels.sort((a, b) => {
                let indexA = effectiveBeltOrder.indexOf(a);
                let indexB = effectiveBeltOrder.indexOf(b);
                if (indexA === -1) indexA = 999;
                if (indexB === -1) indexB = 999;
                return indexA - indexB;
            });

            const cData = cLabels.map(l => cintMap[l]);
            const cBg = cLabels.map(l => {
                const key = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
                const item = beltColors[key];
                if (item.isPattern) {
                    return createStripePattern(item.bg, item.stripe);
                }
                return item.bg;
            });

            chartCinturones.data.labels = cLabels;
            chartCinturones.data.datasets[0].data = cData;
            chartCinturones.data.datasets[0].backgroundColor = cBg;
            chartCinturones.data.datasets[0].borderColor = isDark ? '#0f172a' : '#ffffff';
            chartCinturones.update();

            chartSedes.data.labels = Object.keys(sedeMap);
            chartSedes.data.datasets[0].data = Object.values(sedeMap);
            chartSedes.update();
        }

        searchInput.addEventListener('input', () => { currentPage = 1; filterStudents(); });
        sedeSelect.addEventListener('change', () => { currentPage = 1; filterStudents(); });
        nivelSelect.addEventListener('change', () => { currentPage = 1; filterStudents(); });
        grupoSelect.addEventListener('change', () => { currentPage = 1; filterStudents(); });
        estadoSelect.addEventListener('change', () => { currentPage = 1; filterStudents(); });

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            sedeSelect.value = 'all';
            nivelSelect.value = 'all';
            grupoSelect.value = 'all';
            estadoSelect.value = 'all';
            currentPage = 1;
            filterStudents();
        });

        filterStudents();
    });

    // Selección Masiva y Borrado
    const selectAllCb = document.getElementById('select-all-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const bulkCount = document.getElementById('bulk-count');

    function updateBulkUI() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        if (checked.length > 0) {
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
            bulkCount.textContent = `${checked.length} sel.`;
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
            if (selectAllCb) selectAllCb.checked = false;
        }
    }

    selectAllCb?.addEventListener('change', () => {
        const isChecked = selectAllCb.checked;
        document.querySelectorAll('.student-row').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.student-checkbox');
                if (cb) cb.checked = isChecked;
            }
        });
        updateBulkUI();
    });

    document.getElementById('students-tbody')?.addEventListener('change', (e) => {
        if (e.target.classList.contains('student-checkbox')) {
            updateBulkUI();
        }
    });

    function clearSelection() {
        document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
        updateBulkUI();
    }

    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        if (checked.length === 0) return;

        if (!confirm(`¿Estás seguro de eliminar a los ${checked.length} estudiantes seleccionados? Esta acción es irreversible.`)) {
            return;
        }

        const container = document.getElementById('bulk-delete-ids');
        container.innerHTML = '';
        checked.forEach(cb => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'ids[]';
            inp.value = cb.value;
            container.appendChild(inp);
        });

        document.getElementById('bulk-delete-form').submit();
    }

    // Modal de Exportación (Excel / PDF)
    const EXPORT_COLUMNS = {
        nombre:    { label: 'Nombre Completo', get: m => ((m.nombre || '') + ' ' + (m.apellido || '')).trim() },
        documento: { label: 'Documento',       get: m => (m.tipo_documento || '') + ' ' + (m.numero_documento || '') },
        cinturon:  { label: 'Grado / Cinturón', get: m => m.grado_nombre || 'Sin Asignar' },
        sede:      { label: 'Sede',            get: m => m.sede_nombre || 'Sin Asignar' },
        grupo:     { label: 'Grupo',           get: m => m.grupo_nombre || 'Sin Asignar' },
        telefono:  { label: 'Teléfono',        get: m => m.telefono || '' },
        correo:    { label: 'Correo',          get: m => m.correo || '' },
        categoria: { label: 'Categoría',       get: m => m.categoria_nombre || '' },
        division:  { label: 'División',        get: m => m.division || '' },
        eps:       { label: 'EPS',             get: m => m.eps || '' },
        rh:        { label: 'RH',              get: m => m.rh || '' },
        peso:      { label: 'Peso',            get: m => m.peso ? (m.peso + ' kg') : '' }
    };

    function openExportModal(type) {
        currentExportType = type;
        const modal = document.getElementById('export-modal');
        const iconContainer = document.getElementById('export-modal-icon');
        const confirmBtn = document.getElementById('export-confirm-btn');
        const confirmIcon = document.getElementById('export-confirm-icon');
        const confirmLabel = document.getElementById('export-confirm-label');

        if (type === 'excel') {
            iconContainer.className = 'h-9 w-9 rounded-xl flex items-center justify-center bg-emerald-600';
            iconContainer.querySelector('span').textContent = 'table_view';
            confirmBtn.className = 'px-5 py-2 font-bold uppercase text-xs text-white rounded-xl shadow transition cursor-pointer flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700';
            confirmIcon.textContent = 'table_view';
            confirmLabel.textContent = 'Descargar Excel';
        } else {
            iconContainer.className = 'h-9 w-9 rounded-xl flex items-center justify-center bg-rose-600';
            iconContainer.querySelector('span').textContent = 'picture_as_pdf';
            confirmBtn.className = 'px-5 py-2 font-bold uppercase text-xs text-white rounded-xl shadow transition cursor-pointer flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700';
            confirmIcon.textContent = 'picture_as_pdf';
            confirmLabel.textContent = 'Descargar PDF';
        }

        modal.classList.remove('hidden');
    }

    function closeExportModal() {
        document.getElementById('export-modal').classList.add('hidden');
    }

    function selectAllExportCols() {
        document.querySelectorAll('.export-col-check').forEach(cb => cb.checked = true);
    }

    function deselectAllExportCols() {
        document.querySelectorAll('.export-col-check').forEach(cb => cb.checked = false);
    }

    function confirmExport() {
        const checkedCols = Array.from(document.querySelectorAll('.export-col-check:checked')).map(cb => cb.value);
        if (checkedCols.length === 0) {
            alert('Por favor, selecciona al menos una columna para exportar.');
            return;
        }

        // Obtener estudiantes visibles en la búsqueda/filtro
        const visibleIds = new Set();
        document.querySelectorAll('.student-row').forEach(r => {
            if (r.style.display !== 'none') {
                visibleIds.add(String(r.getAttribute('data-id')));
            }
        });

        const exportData = ALL_STUDENTS_DATA.filter(m => visibleIds.has(String(m.id)));
        const headers = checkedCols.map(c => EXPORT_COLUMNS[c].label);
        const rows = exportData.map(m => checkedCols.map(c => EXPORT_COLUMNS[c].get(m)));

        const filename = 'Estudiantes_Jinhwan_' + new Date().toISOString().slice(0, 10);

        if (currentExportType === 'excel') {
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Estudiantes');
            XLSX.writeFile(workbook, filename + '.xlsx');
        } else {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF(checkedCols.length > 5 ? 'landscape' : 'portrait');
            doc.text('Reporte de Estudiantes - Jinhwan Corporation', 14, 15);
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 22,
                theme: 'grid',
                styles: { fontSize: 8 }
            });
            doc.save(filename + '.pdf');
        }

        closeExportModal();
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

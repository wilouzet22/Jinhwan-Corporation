<?php
include __DIR__ . '/../layout/administracion_cabecera.php'; 

$total_estudiantes = count($estudiantes);
$total_activos = count(array_filter($estudiantes, fn($e) => ($e['activo'] ?? 0) == 1));
$total_inactivos = $total_estudiantes - $total_activos;
?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
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

                <!-- Botón Nuevo Estudiante -->
                <button type="button" onclick="openModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">person_add</span>
                    <span>Nuevo Estudiante</span>
                </button>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
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

        <!-- Tabla de Estudiantes -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse" id="students-table">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-3 py-3 w-10 text-center">
                                <input type="checkbox" id="select-all-checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                            </th>
                            <th scope="col" class="px-4 py-3">Estudiante</th>
                            <th scope="col" class="px-3 py-3 text-center">Grado / Cinturón</th>
                            <th scope="col" class="px-3 py-3">Grupo & Sede</th>
                            <th scope="col" class="px-3 py-3">Contacto</th>
                            <th scope="col" class="px-3 py-3 text-center">Estado</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="students-tbody">
                        <?php if (empty($estudiantes)): ?>
                            <tr>
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
                                ?>
                                <tr class="student-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all" 
                                    data-id="<?= $est['id'] ?>"
                                    data-nombre="<?= htmlspecialchars(strtolower($est['nombre'] . ' ' . $est['apellido'])) ?>"
                                    data-doc="<?= htmlspecialchars($est['numero_documento'] ?? '') ?>"
                                    data-sede="<?= htmlspecialchars($est['sede_nombre'] ?? 'Sin Asignar') ?>"
                                    data-nivel="<?= htmlspecialchars($est['grado_nombre'] ?? 'Sin Asignar') ?>"
                                    data-grupo="<?= htmlspecialchars($est['grupo_nombre'] ?? 'Sin Asignar') ?>"
                                    data-estado="<?= ($est['activo'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>">
                                    
                                    <td class="px-3 py-2.5 text-center">
                                        <input type="checkbox" name="student-checkbox" value="<?= $est['id'] ?>"
                                               class="student-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                                    </td>

                                    <!-- Estudiante (Avatar + Nombre + Doc) -->
                                    <td class="px-4 py-2.5 font-semibold">
                                        <div class="flex items-center gap-2.5">
                                            <?php if (!empty($est['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $est['foto_perfil']) ?>" class="w-8 h-8 rounded-full object-cover shadow-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    <?= strtoupper(substr($est['nombre'] ?? 'E', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="min-w-0">
                                                <div class="text-slate-900 dark:text-white font-bold text-sm truncate leading-tight">
                                                    <?= htmlspecialchars($est['nombre'] ?? '') . ' ' . htmlspecialchars($est['apellido'] ?? '') ?>
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1 mt-0.5">
                                                    <span class="font-mono bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-1 py-0.2 rounded text-[10px]"><?= htmlspecialchars($est['tipo_documento'] ?? 'TI') ?></span>
                                                    <span><?= htmlspecialchars($est['numero_documento'] ?? 'S/D') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Grado -->
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                            <?= htmlspecialchars($est['grado_nombre'] ?? 'Sin Asignar') ?>
                                        </span>
                                    </td>

                                    <!-- Grupo & Sede -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">
                                            <?= htmlspecialchars($est['grupo_nombre'] ?? 'Sin Grupo') ?>
                                        </div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                            <span class="material-icons-outlined text-xs text-tkd-blue">place</span>
                                            <span class="truncate"><?= htmlspecialchars($est['sede_nombre'] ?? 'Sin Sede') ?></span>
                                        </div>
                                    </td>

                                    <!-- Contacto -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="text-slate-800 dark:text-slate-300 font-medium"><?= htmlspecialchars($est['telefono'] ?? '-') ?></div>
                                        <div class="text-[11px] text-slate-400 truncate mt-0.5"><?= htmlspecialchars($est['correo'] ?? '-') ?></div>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <?php if (($est['activo'] ?? 0) == 1): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800/30">
                                                Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-800/30">
                                                Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- Ver Detalle -->
                                            <button type="button" onclick='openDetailModal(<?= json_encode($est) ?>)' class="text-slate-500 hover:text-tkd-blue dark:text-slate-400 dark:hover:text-blue-400 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Ver Detalle">
                                                <span class="material-icons-outlined text-base">visibility</span>
                                            </button>
                                            
                                            <!-- Editar -->
                                            <button type="button" onclick='openModal("edit", <?= json_encode($est) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Editar">
                                                <span class="material-icons-outlined text-base">edit</span>
                                            </button>
                                            
                                            <!-- Eliminar -->
                                            <form action="<?= base_url('/admin/estudiantes/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar a este estudiante?');">
                                                <input type="hidden" name="id" value="<?= $est['id'] ?>">
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all cursor-pointer" title="Eliminar">
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

<!-- MODAL CREAR / EDITAR ESTUDIANTE -->
<div id="student-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden transition-all my-8">
        <form id="student-form" method="POST" enctype="multipart/form-data" class="flex flex-col">
            <input type="hidden" name="id" id="modal-id">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-bold text-slate-900 dark:text-white">Nuevo Estudiante</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                
                <!-- Datos Personales Básicos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nombre *</label>
                        <input type="text" name="nombre" id="modal-nombre" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Apellido *</label>
                        <input type="text" name="apellido" id="modal-apellido" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Tipo Doc.</label>
                        <select name="tipo_documento" id="modal-tipo-doc" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                            <option value="TI">TI - Tarjeta de Identidad</option>
                            <option value="CC">CC - Cédula</option>
                            <option value="RC">RC - Registro Civil</option>
                            <option value="CE">CE - Cédula Extranjería</option>
                            <option value="PASAPORTE">Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">N° Documento *</label>
                        <input type="text" name="numero_documento" id="modal-num-doc" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="modal-fnac" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Correo Electrónico *</label>
                        <input type="email" name="correo" id="modal-correo" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="modal-telefono" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                </div>

                <!-- Academia y Grupos -->
                <div class="p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-tkd-blue">Datos de Academia</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Grupo de Entrenamiento</label>
                            <select name="id_grupo" id="modal-id-grupo" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                                <?php foreach($grupos_list as $grp): ?>
                                    <option value="<?= $grp['id_grupo'] ?>"><?= htmlspecialchars($grp['nombre']) ?> (<?= htmlspecialchars($grp['sede_nombre'] ?? '') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Grado / Cinturón</label>
                            <select name="nivel_id" id="modal-nivel-id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                                <?php foreach($niveles_list as $niv): ?>
                                    <option value="<?= $niv['id'] ?>"><?= htmlspecialchars($niv['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Categoría</label>
                            <select name="categoria_id" id="modal-categoria-id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                                <?php foreach($categorias_list as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Datos Físicos y Médicos -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">Peso (kg)</label>
                        <input type="number" step="0.1" name="peso" id="modal-peso" class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">División</label>
                        <input type="text" name="division" id="modal-division" class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">EPS</label>
                        <input type="text" name="eps" id="modal-eps" class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-1">RH</label>
                        <select name="rh" id="modal-rh" class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white">
                            <option value="">Seleccionar</option>
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

                <!-- Foto y Clave -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Foto de Perfil</label>
                        <input type="file" name="foto_perfil" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-tkd-blue hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Contraseña</label>
                        <input type="password" name="clave" id="modal-clave" placeholder="Por defecto: jinhwa2024" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                        <span id="clave-hint" class="hidden text-[10px] text-slate-400">Dejar en blanco para mantener la contraseña actual.</span>
                    </div>
                </div>

                <!-- Opciones / Toggles -->
                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" id="modal-activo" value="1" checked class="w-4 h-4 rounded text-tkd-blue">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Activo en la Academia</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="mostrar_en_web" id="modal-web" value="1" class="w-4 h-4 rounded text-tkd-blue">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Mostrar en Perfil Público Web</span>
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

<!-- MODAL DETALLE DE ESTUDIANTE -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 relative">
        <button type="button" onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">✕</button>
        <div id="detail-content"></div>
    </div>
</div>

<script>
// Filtros y Búsqueda
const searchInput = document.getElementById('student-search');
const filterSede = document.getElementById('filter-sede');
const filterNivel = document.getElementById('filter-nivel');
const filterGrupo = document.getElementById('filter-grupo');
const filterEstado = document.getElementById('filter-estado');
const btnReset = document.getElementById('btn-reset-filters');
const rows = document.querySelectorAll('.student-row');

function filterTable() {
    const text = searchInput.value.toLowerCase().trim();
    const sede = filterSede.value;
    const nivel = filterNivel.value;
    const grupo = filterGrupo.value;
    const estado = filterEstado.value;

    let hasFilter = text || sede !== 'all' || nivel !== 'all' || grupo !== 'all' || estado !== 'all';
    btnReset.classList.toggle('hidden', !hasFilter);

    rows.forEach(r => {
        const rNombre = r.dataset.nombre;
        const rDoc = r.dataset.doc;
        const rSede = r.dataset.sede;
        const rNivel = r.dataset.nivel;
        const rGrupo = r.dataset.grupo;
        const rEstado = r.dataset.estado;

        const matchText = !text || rNombre.includes(text) || rDoc.includes(text);
        const matchSede = sede === 'all' || rSede === sede;
        const matchNivel = nivel === 'all' || rNivel === nivel;
        const matchGrupo = grupo === 'all' || rGrupo === grupo;
        const matchEstado = estado === 'all' || rEstado === estado;

        r.style.display = (matchText && matchSede && matchNivel && matchGrupo && matchEstado) ? '' : 'none';
    });
}

[searchInput, filterSede, filterNivel, filterGrupo, filterEstado].forEach(el => {
    el.addEventListener('input', filterTable);
    el.addEventListener('change', filterTable);
});

btnReset.addEventListener('click', () => {
    searchInput.value = '';
    filterSede.value = 'all';
    filterNivel.value = 'all';
    filterGrupo.value = 'all';
    filterEstado.value = 'all';
    filterTable();
});

// Selección Múltiple y Eliminación Masiva
const selectAllCheckbox = document.getElementById('select-all-checkbox');
const studentCheckboxes = document.querySelectorAll('.student-checkbox');
const bulkBar = document.getElementById('bulk-action-bar');
const bulkCount = document.getElementById('bulk-count');

function updateBulkBar() {
    const selected = Array.from(studentCheckboxes).filter(c => c.checked);
    bulkCount.textContent = `${selected.length} sel.`;
    bulkBar.classList.toggle('hidden', selected.length === 0);
    bulkBar.classList.toggle('flex', selected.length > 0);
}

selectAllCheckbox?.addEventListener('change', (e) => {
    studentCheckboxes.forEach(c => {
        if (c.closest('tr').style.display !== 'none') {
            c.checked = e.target.checked;
        }
    });
    updateBulkBar();
});

studentCheckboxes.forEach(c => c.addEventListener('change', updateBulkBar));

function clearSelection() {
    studentCheckboxes.forEach(c => c.checked = false);
    if (selectAllCheckbox) selectAllCheckbox.checked = false;
    updateBulkBar();
}

function confirmBulkDelete() {
    const selected = Array.from(studentCheckboxes).filter(c => c.checked).map(c => c.value);
    if (selected.length === 0) return;
    if (confirm(`¿Estás seguro de eliminar a los ${selected.length} estudiantes seleccionados?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('/admin/estudiantes/delete-bulk') ?>';
        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        document.body.appendChild(form);
        form.submit();
    }
}

// Modal Agregar / Editar
function openModal(mode, data = null) {
    const modal = document.getElementById('student-modal');
    const form = document.getElementById('student-form');
    const title = document.getElementById('modal-title');
    const claveHint = document.getElementById('clave-hint');

    form.reset();

    if (mode === 'edit' && data) {
        title.textContent = 'Editar Estudiante';
        form.action = '<?= base_url('/admin/estudiantes/update') ?>';
        document.getElementById('modal-id').value = data.id;
        document.getElementById('modal-nombre').value = data.nombre || '';
        document.getElementById('modal-apellido').value = data.apellido || '';
        document.getElementById('modal-tipo-doc').value = data.tipo_documento || 'TI';
        document.getElementById('modal-num-doc').value = data.numero_documento || '';
        document.getElementById('modal-fnac').value = data.fecha_nacimiento || '';
        document.getElementById('modal-correo').value = data.correo || '';
        document.getElementById('modal-telefono').value = data.telefono || '';
        if (data.id_grupo) document.getElementById('modal-id-grupo').value = data.id_grupo;
        if (data.id_grado) document.getElementById('modal-nivel-id').value = data.id_grado;
        if (data.id_categoria) document.getElementById('modal-categoria-id').value = data.id_categoria;
        document.getElementById('modal-peso').value = data.peso || '';
        document.getElementById('modal-division').value = data.division || '';
        document.getElementById('modal-eps').value = data.eps || '';
        document.getElementById('modal-rh').value = data.rh || '';
        document.getElementById('modal-activo').checked = data.activo == 1;
        document.getElementById('modal-web').checked = data.mostrar_en_web == 1;
        claveHint.classList.remove('hidden');
    } else {
        title.textContent = 'Nuevo Estudiante';
        form.action = '<?= base_url('/admin/estudiantes/create') ?>';
        document.getElementById('modal-id').value = '';
        document.getElementById('modal-activo').checked = true;
        document.getElementById('modal-web').checked = false;
        claveHint.classList.add('hidden');
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('student-modal').classList.add('hidden');
}

// Modal Detalle
function openDetailModal(data) {
    const modal = document.getElementById('detail-modal');
    const container = document.getElementById('detail-content');
    
    container.innerHTML = `
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-xl text-slate-600 dark:text-slate-300">
                ${data.foto_perfil ? `<img src="<?= base_url('/public/uploads/perfiles/') ?>${data.foto_perfil}" class="w-full h-full object-cover">` : data.nombre.charAt(0)}
            </div>
            <div>
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">${data.nombre} ${data.apellido}</h4>
                <p class="text-xs text-slate-400">${data.tipo_documento || 'TI'}: ${data.numero_documento || 'S/D'}</p>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ${data.activo == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}">
                    ${data.activo == 1 ? 'Activo' : 'Inactivo'}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-200 dark:border-slate-800 pt-3">
            <div><span class="text-slate-400">Grado:</span> <strong class="text-slate-700 dark:text-slate-200">${data.grado_nombre || 'Sin asignar'}</strong></div>
            <div><span class="text-slate-400">Grupo:</span> <strong class="text-slate-700 dark:text-slate-200">${data.grupo_nombre || 'Sin asignar'}</strong></div>
            <div><span class="text-slate-400">Sede:</span> <strong class="text-slate-700 dark:text-slate-200">${data.sede_nombre || 'Sin asignar'}</strong></div>
            <div><span class="text-slate-400">Categoría:</span> <strong class="text-slate-700 dark:text-slate-200">${data.categoria_nombre || 'Sin asignar'}</strong></div>
            <div><span class="text-slate-400">Correo:</span> <p class="truncate text-slate-700 dark:text-slate-200">${data.correo || '-'}</p></div>
            <div><span class="text-slate-400">Teléfono:</span> <strong class="text-slate-700 dark:text-slate-200">${data.telefono || '-'}</strong></div>
            <div><span class="text-slate-400">EPS:</span> <strong class="text-slate-700 dark:text-slate-200">${data.eps || '-'} (${data.rh || '-'})</strong></div>
            <div><span class="text-slate-400">Nacimiento:</span> <strong class="text-slate-700 dark:text-slate-200">${data.fecha_nacimiento || '-'}</strong></div>
        </div>
    `;

    modal.classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

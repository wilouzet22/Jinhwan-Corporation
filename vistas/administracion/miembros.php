<?php
include __DIR__ . '/../layout/administracion_cabecera.php'; 

// Estadísticas rápidas
$total_miembros = count($miembros);
$total_alumnos = count(array_filter($miembros, fn($m) => $m['rol_id'] === Roles::ESTUDIANTE || $m['rol_id'] === 'Alumno'));
$total_instructores = count(array_filter($miembros, fn($m) => $m['rol_id'] === Roles::MAESTRO || $m['rol_id'] === Roles::PROFESOR || $m['rol_id'] === Roles::MONITOR));
$total_admins = count(array_filter($miembros, fn($m) => $m['rol_id'] === Roles::ADMINISTRADOR));
?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">
        
        <!-- Encabezado y Botones de Acción -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Gestión de Miembros
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Administra alumnos, instructores y directivos de la academia.
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

                <!-- Añadir Nuevo Miembro -->
                <button type="button" onclick="openModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">person_add</span>
                    <span>Nuevo Miembro</span>
                </button>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas Rápidas -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Miembros</p>
                    <p class="text-2xl font-display font-bold text-slate-900 dark:text-white mt-1"><?= $total_miembros ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-tkd-blue flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">groups</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Alumnos</p>
                    <p class="text-2xl font-display font-bold text-emerald-600 dark:text-emerald-400 mt-1"><?= $total_alumnos ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">sports_martial_arts</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Instructores</p>
                    <p class="text-2xl font-display font-bold text-purple-600 dark:text-purple-400 mt-1"><?= $total_instructores ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">military_tech</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Admins</p>
                    <p class="text-2xl font-display font-bold text-rose-600 dark:text-rose-400 mt-1"><?= $total_admins ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">admin_panel_settings</span>
                </div>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            
            <div class="flex flex-wrap items-center gap-3 w-full">
                <!-- Buscador por texto -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="member-search" placeholder="Buscar por nombre o doc..." 
                           class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>

                <!-- Filtro Sede -->
                <select id="filter-sede" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todas las Sedes</option>
                    <?php foreach($sedes_list as $sede): ?>
                        <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro Cinturón -->
                <select id="filter-nivel" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Cinturones</option>
                    <?php foreach($niveles_list as $nivel): ?>
                        <option value="<?= htmlspecialchars($nivel['nombre']) ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtro Rol -->
                <select id="filter-rol" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Roles</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Alumno">Alumno</option>
                    <option value="Invitado">Invitado</option>
                </select>

                <!-- Filtro Estado -->
                <select id="filter-estado" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                    <option value="all">Todos los Estados</option>
                    <option value="Activo">🟢 Activo</option>
                    <option value="Pendiente">🟡 Pendiente</option>
                </select>

                <!-- Botón Reset Filtros -->
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
                    <div id="chart-cinturones-empty" class="absolute inset-0 flex items-center justify-center text-xs text-slate-400 hidden">Sin datos para mostrar</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm flex flex-col transition-colors">
                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Alumnos por Sede</h3>
                <div class="flex-grow relative w-full flex items-center justify-center min-h-[190px]">
                    <canvas id="chart-sedes"></canvas>
                    <div id="chart-sedes-empty" class="absolute inset-0 flex items-center justify-center text-xs text-slate-400 hidden">Sin datos para mostrar</div>
                </div>
            </div>
        </div>

        <!-- Tabla de Miembros -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="overflow-x-auto overflow-y-auto max-h-[580px]">
                <table class="w-full text-sm text-left border-collapse" id="members-table">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 w-10 text-center">
                                <input type="checkbox" id="select-all-checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                            </th>
                            <th scope="col" class="px-6 py-3.5">Nombre</th>
                            <th scope="col" class="px-6 py-3.5">Documento</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Cinturón</th>
                            <th scope="col" class="px-6 py-3.5">Sede</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Rol</th>
                            <th scope="col" class="px-6 py-3.5">Contacto</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="members-tbody">
                        <?php foreach ($miembros as $miembro): ?>
                            <?php 
                                $rolNombre = match($miembro['rol_id']) {
                                    Roles::ADMINISTRADOR => 'Administrador',
                                    Roles::MAESTRO => 'Instructor',
                                    Roles::ESTUDIANTE => 'Alumno',
                                    default => 'Invitado'
                                };

                                $nivel = strtolower($miembro['nombre_nivel'] ?? '');
                                $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400'; 
                                if (str_contains($nivel, 'blanco')) {
                                    $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-600 shadow-sm';
                                } elseif (str_contains($nivel, 'amarillo')) {
                                    $beltClass = 'bg-yellow-50 text-yellow-800 border border-yellow-300 dark:bg-yellow-950/50 dark:text-yellow-400';
                                } elseif (str_contains($nivel, 'verde')) {
                                    $beltClass = 'bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-400';
                                } elseif (str_contains($nivel, 'azul')) {
                                    $beltClass = 'bg-blue-50 text-blue-800 border border-blue-300 dark:bg-blue-950/50 dark:text-blue-400';
                                } elseif (str_contains($nivel, 'rojo')) {
                                    $beltClass = 'bg-red-50 text-red-800 border border-red-300 dark:bg-red-950/50 dark:text-red-400';
                                } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                                    $beltClass = 'bg-slate-900 text-white border border-slate-900 dark:bg-slate-950 dark:border-slate-700 shadow-md';
                                }
                            ?>
                            <tr class="member-row hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all" 
                                data-id="<?= $miembro['id'] ?>"
                                data-nombre="<?= htmlspecialchars(strtolower($miembro['nombre'] . ' ' . $miembro['apellido'])) ?>"
                                data-doc="<?= htmlspecialchars($miembro['numero_documento'] ?? '') ?>"
                                data-sede="<?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>"
                                data-nivel="<?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>"
                                data-rol="<?= $rolNombre ?>"
                                data-estado="<?= $miembro['activo'] == 1 ? 'Activo' : 'Pendiente' ?>">
                                
                                <td class="px-4 py-3.5 text-center">
                                    <input type="checkbox" name="member-checkbox" value="<?= $miembro['id'] ?>"
                                           class="member-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-tkd-blue focus:ring-tkd-blue bg-white dark:bg-slate-800 cursor-pointer">
                                </td>

                                <td class="px-6 py-3.5 font-semibold whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($miembro['foto_perfil'])): ?>
                                            <img src="<?= base_url('/public/uploads/perfiles/' . $miembro['foto_perfil']) ?>" class="w-9 h-9 rounded-full object-cover shadow-sm shrink-0 border border-slate-200 dark:border-slate-700">
                                        <?php else: ?>
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-tkd-blue to-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                                <?= strtoupper(substr($miembro['nombre'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-slate-900 dark:text-white font-bold">
                                                <?= htmlspecialchars($miembro['nombre'] ?? '') . ' ' . htmlspecialchars($miembro['apellido'] ?? '') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-3.5 text-slate-600 dark:text-slate-300 text-xs">
                                    <span class="font-mono bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-1.5 py-0.5 rounded text-slate-500 dark:text-slate-400 mr-1 text-[10px]"><?= htmlspecialchars($miembro['tipo_documento'] ?? 'CC') ?></span>
                                    <?= htmlspecialchars($miembro['numero_documento'] ?? '') ?>
                                </td>

                                <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                        <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                    </span>
                                </td>

                                <td class="px-6 py-3.5 text-slate-600 dark:text-slate-300 text-xs">
                                    <div class="flex items-center gap-1">
                                        <span class="material-icons-outlined text-sm text-tkd-blue">place</span>
                                        <span><?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?></span>
                                    </div>
                                </td>

                                <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                    <?php switch($miembro['rol_id']): 
                                        case Roles::ADMINISTRADOR: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-800/30">Administrador</span>
                                        <?php break; case Roles::MAESTRO: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/60 dark:text-purple-400 dark:border-purple-800/30">Instructor</span>
                                        <?php break; case Roles::ESTUDIANTE: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800/30">Alumno</span>
                                        <?php break; default: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400">Invitado</span>
                                     <?php endswitch; ?>
                                </td>

                                <td class="px-6 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                                    <div class="text-slate-700 dark:text-slate-300 font-medium"><?= htmlspecialchars($miembro['telefono'] ?? '-') ?></div>
                                    <div class="truncate max-w-[140px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                                </td>

                                <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <!-- Ver Detalle -->
                                        <button type="button" onclick='openDetailModal(<?= json_encode($miembro) ?>)' class="text-slate-500 hover:text-tkd-blue dark:text-slate-400 dark:hover:text-blue-400 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all focus:outline-none cursor-pointer" title="Ver Detalle">
                                            <span class="material-icons-outlined text-lg">visibility</span>
                                        </button>
                                        
                                        <!-- Editar -->
                                        <button type="button" onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all focus:outline-none cursor-pointer" title="Editar">
                                            <span class="material-icons-outlined text-lg">edit</span>
                                        </button>
                                        
                                        <!-- Eliminar -->
                                        <form action="<?= base_url('/admin/miembros/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar a este miembro?');">
                                            <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all focus:outline-none cursor-pointer" title="Eliminar">
                                                <span class="material-icons-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Modal de Añadir / Editar Miembro -->
<div id="member-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-950/80 transition-opacity" onclick="closeModal()"></div>
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="relative z-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl text-left transition-colors">
            
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-950/50">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-blue text-xl">person</span>
                    <h2 id="modal-title" class="text-lg font-display font-bold text-slate-900 dark:text-white uppercase">Añadir Nuevo Miembro</h2>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>

            <form id="member-form" action="<?= base_url('/admin/miembros/create') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <input type="hidden" name="id" id="id">
                
                <!-- Sección: Datos Personales -->
                <div>
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">badge</span>
                        <span>1. Datos Personales</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="apellido" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Apellido *</label>
                            <input type="text" name="apellido" id="apellido" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="tipo_documento" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Tipo Documento *</label>
                            <select name="tipo_documento" id="tipo_documento" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <option value="TI">Tarjeta de Identidad (T.I)</option>
                                <option value="CC">Cédula de Ciudadanía (C.C)</option>
                                <option value="CE">Cédula de Extranjería (C.E)</option>
                                <option value="PAS">Pasaporte</option>
                            </select>
                        </div>
                        <div>
                            <label for="numero_documento" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Número Documento *</label>
                            <input type="text" name="numero_documento" id="numero_documento" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección: Contacto -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">contact_phone</span>
                        <span>2. Contacto</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="telefono" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Teléfono / Celular</label>
                            <input type="tel" name="telefono" id="telefono" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="correo" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Correo Electrónico</label>
                            <input type="email" name="correo" id="correo" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección: Información Deportiva -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">sports_martial_arts</span>
                        <span>3. Información Deportiva y Rol</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="sede_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Sede *</label>
                            <select name="sede_id" id="sede_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($sedes_list as $sede): ?>
                                    <option value="<?= $sede['id'] ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="nivel_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Cinturón (Nivel) *</label>
                            <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($niveles_list as $nivel): ?>
                                    <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="rol_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Rol en el Sistema *</label>
                            <select name="rol_id" id="rol_id" onchange="togglePermisos(this.value)" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <option value="<?= Roles::ESTUDIANTE ?>">Alumno (Deportista)</option>
                                <option value="<?= Roles::MAESTRO ?>">Instructor (Maestro)</option>
                                <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                            </select>
                        </div>
                        <div>
                            <label for="categoria_id" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Categoría Deportiva</label>
                            <select name="categoria_id" id="categoria_id" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                                <?php foreach($categorias_list as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="division" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">División de Peso</label>
                            <input type="text" name="division" id="division" placeholder="Ej. Minimosca -54kg" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Sección: Salud -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">health_and_safety</span>
                        <span>4. Información Médica</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="peso" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Peso (kg)</label>
                            <input type="number" step="0.01" name="peso" id="peso" placeholder="Ej. 65.5" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="eps" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">EPS</label>
                            <input type="text" name="eps" id="eps" placeholder="Ej. Sura, Sanitas" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>
                        <div>
                            <label for="rh" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Grupo Sanguíneo (RH)</label>
                            <select name="rh" id="rh" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
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

                <!-- Sección: Perfil Web Público -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                    <h3 class="text-xs font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">public</span>
                        <span>5. Perfil Web y Multimedia</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200 dark:border-slate-800">
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Mostrar en el portal público</span>
                                <p class="text-[11px] text-slate-400">Aparecerá en la sección de miembros del sitio web.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="mostrar_en_web" id="mostrar_en_web" value="1" class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <div>
                            <label for="url_instagram" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Enlace de Video (YouTube / Vimeo) o Instagram</label>
                            <input type="text" name="url_instagram" id="url_instagram" placeholder="https://youtube.com/watch?v=..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none">
                        </div>

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

                        <div>
                            <label for="descripcion_perfil" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Biografía / Reseña</label>
                            <textarea name="descripcion_perfil" id="descripcion_perfil" rows="2" placeholder="Breve biografía..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none resize-none"></textarea>
                        </div>

                        <div>
                            <label for="logros" class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Logros y Reconocimientos</label>
                            <textarea name="logros" id="logros" rows="2" placeholder="Medallas, títulos, participaciones..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white p-2.5 text-sm focus:ring-2 focus:ring-tkd-blue focus:outline-none resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sección: Permisos Extra (Solo Instructores) -->
                <div id="permisos_section" class="border-t border-slate-100 dark:border-slate-800 pt-5 hidden">
                    <h3 class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">lock_person</span>
                        <span>6. Permisos Administrativos de Instructor</span>
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-purple-50 dark:bg-purple-950/20 p-4 rounded-xl border border-purple-100 dark:border-purple-900/30">
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_sedes" id="permiso_sedes" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Gestionar Sedes</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_registros" id="permiso_registros" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Aprobar Registros</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_ascensos" id="permiso_ascensos" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Aprobar Ascensos</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_calendario" id="permiso_calendario" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Gestionar Calendario</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_galeria" id="permiso_galeria" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Gestionar Galería</span>
                        </label>
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="permiso_reportes" id="permiso_reportes" class="h-4 w-4 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Ver Reportes</span>
                        </label>
                    </div>
                </div>

                <!-- Botones del Formulario -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs tracking-wider rounded-xl shadow-md hover:shadow-lg transition-all focus:outline-none cursor-pointer">
                        Guardar Miembro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalle Completo del Miembro -->
<div id="member-detail-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                            <span id="detail-badge-rol" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
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
                        <div class="flex justify-between"><span class="text-slate-400">Cinturón:</span> <span class="font-bold text-slate-800 dark:text-slate-200" id="detail-cinturon"></span></div>
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

                <!-- Perfil Web y Reseña -->
                <div class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-2xl border border-slate-200/60 dark:border-slate-800 space-y-3 text-xs">
                    <p class="font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider text-[11px]">Perfil Web</p>
                    <div class="flex justify-between"><span class="text-slate-400">Visible en Web:</span> <span class="font-bold" id="detail-mostrar-web"></span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Enlace Video:</span> <span class="font-bold" id="detail-instagram"></span></div>
                    <div>
                        <span class="text-slate-400 block mb-1">Biografía:</span>
                        <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 italic border border-slate-200 dark:border-slate-800" id="detail-descripcion"></div>
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
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="cinturon" checked><span>Cinturón</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="sede" checked><span>Sede</span></label>
                    <label class="flex items-center gap-2 cursor-pointer p-1"><input type="checkbox" class="export-col-check text-tkd-blue rounded" value="rol" checked><span>Rol</span></label>
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
<form id="bulk-delete-form" action="<?= base_url('/admin/miembros/delete-bulk') ?>" method="POST" class="hidden">
    <div id="bulk-delete-ids"></div>
</form>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    const ALL_MEMBERS_DATA = <?= json_encode(array_values($miembros)) ?>;
    const BELT_ORDER = <?= json_encode(array_column($niveles_list, 'nombre')) ?>;
    let activeMemberData = null;
    let currentExportType = 'excel';

    // Funciones de Modal
    function openModal(action, data = null) {
        const modal = document.getElementById('member-modal');
        const form = document.getElementById('member-form');
        const title = document.getElementById('modal-title');
        
        modal.classList.remove('hidden');
        form.reset();
        
        if (action === 'add') {
            title.textContent = 'Añadir Nuevo Miembro';
            form.action = '<?= base_url('/admin/miembros/create') ?>';
            document.getElementById('id').value = '';
            document.getElementById('rol_id').value = '<?= Roles::ESTUDIANTE ?>';
            togglePermisos('<?= Roles::ESTUDIANTE ?>');
            document.getElementById('categoria_id').value = '1';
            document.getElementById('mostrar_en_web').checked = false;
            document.getElementById('foto-preview-img').classList.add('hidden');
            document.getElementById('foto-preview-placeholder').classList.remove('hidden');
            document.getElementById('delete-foto-container').classList.add('hidden');
        } else if (action === 'edit') {
            title.textContent = 'Editar Miembro: ' + data.nombre + ' ' + data.apellido;
            form.action = '<?= base_url('/admin/miembros/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('nombre').value = data.nombre || '';
            document.getElementById('apellido').value = data.apellido || '';
            document.getElementById('tipo_documento').value = data.tipo_documento || 'TI';
            document.getElementById('numero_documento').value = data.numero_documento || '';
            document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento || '';
            document.getElementById('telefono').value = data.telefono || '';
            document.getElementById('correo').value = data.correo || '';
            document.getElementById('nivel_id').value = data.nivel_id || '1';
            document.getElementById('sede_id').value = data.sede_id || '';
            document.getElementById('rol_id').value = data.rol_id || '<?= Roles::ESTUDIANTE ?>';
            document.getElementById('categoria_id').value = data.categoria_id || '1';
            document.getElementById('division').value = data.division || '';
            document.getElementById('peso').value = data.peso || '';
            document.getElementById('eps').value = data.eps || '';
            document.getElementById('rh').value = data.rh || '';
            document.getElementById('mostrar_en_web').checked = (data.mostrar_en_web == 1);
            document.getElementById('url_instagram').value = data.instagram_url || '';
            document.getElementById('descripcion_perfil').value = data.descripcion_perfil || '';
            document.getElementById('logros').value = data.logros || '';

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
            
            togglePermisos(data.rol_id);
            if (data.permisos_extra) {
                try {
                    let permisos = JSON.parse(data.permisos_extra);
                    document.getElementById('permiso_sedes').checked = !!permisos.sedes;
                    document.getElementById('permiso_registros').checked = !!permisos.registros;
                    document.getElementById('permiso_ascensos').checked = !!permisos.ascensos;
                    document.getElementById('permiso_calendario').checked = !!permisos.calendario;
                    document.getElementById('permiso_galeria').checked = !!permisos.galeria;
                    document.getElementById('permiso_reportes').checked = !!permisos.reportes;
                } catch(e) {}
            }
        }
    }

    function closeModal() {
        document.getElementById('member-modal').classList.add('hidden');
    }

    function togglePermisos(rol_id) {
        const sec = document.getElementById('permisos_section');
        if (rol_id === '<?= Roles::MAESTRO ?>' || rol_id === '<?= Roles::PROFESOR ?>' || rol_id === '<?= Roles::MONITOR ?>') {
            sec.classList.remove('hidden');
        } else {
            sec.classList.add('hidden');
        }
    }

    // Modal Detalle
    function openDetailModal(data) {
        activeMemberData = data;
        const modal = document.getElementById('member-detail-modal');
        modal.classList.remove('hidden');

        document.getElementById('detail-fullname').textContent = (data.nombre + ' ' + data.apellido).trim();
        
        const badgeRol = document.getElementById('detail-badge-rol');
        badgeRol.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ";
        if (data.rol_id === 'Administracion') {
            badgeRol.textContent = "Administrador";
            badgeRol.classList.add("bg-rose-50", "text-rose-700", "border", "border-rose-200", "dark:bg-rose-950/60", "dark:text-rose-400");
        } else if (data.rol_id === 'Maestros') {
            badgeRol.textContent = "Instructor";
            badgeRol.classList.add("bg-purple-50", "text-purple-700", "border", "border-purple-200", "dark:bg-purple-950/60", "dark:text-purple-400");
        } else {
            badgeRol.textContent = "Alumno";
            badgeRol.classList.add("bg-emerald-50", "text-emerald-700", "border", "border-emerald-200", "dark:bg-emerald-950/60", "dark:text-emerald-400");
        }

        const badgeEstado = document.getElementById('detail-badge-estado');
        badgeEstado.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ";
        if (data.activo == 1) {
            badgeEstado.textContent = "Activo";
            badgeEstado.classList.add("bg-emerald-50", "text-emerald-700", "border", "border-emerald-200", "dark:bg-emerald-950/60", "dark:text-emerald-400");
        } else {
            badgeEstado.textContent = "Pendiente";
            badgeEstado.classList.add("bg-amber-50", "text-amber-700", "border", "border-amber-200", "dark:bg-amber-950/60", "dark:text-amber-400");
        }

        const fotoImg = document.getElementById('detail-foto-img');
        const fotoInitial = document.getElementById('detail-foto-initial');
        if (data.foto_perfil) {
            fotoImg.src = '<?= base_url("public/uploads/perfiles/") ?>' + data.foto_perfil;
            fotoImg.classList.remove('hidden');
            fotoInitial.classList.add('hidden');
        } else {
            fotoImg.classList.add('hidden');
            fotoInitial.textContent = (data.nombre || 'U').substring(0, 1).toUpperCase();
            fotoInitial.classList.remove('hidden');
        }

        document.getElementById('detail-documento').textContent = (data.tipo_documento || 'CC') + ' ' + (data.numero_documento || '');
        document.getElementById('detail-fecha-n').textContent = data.fecha_nacimiento || 'Sin registrar';
        document.getElementById('detail-edad').textContent = calculateAge(data.fecha_nacimiento) + ' años';
        document.getElementById('detail-telefono').textContent = data.telefono || 'Sin registrar';
        document.getElementById('detail-correo').textContent = data.correo || 'Sin registrar';
        document.getElementById('detail-sede').textContent = data.nombre_sede || 'Sin Asignar';
        document.getElementById('detail-cinturon').textContent = data.nombre_nivel || 'Sin Asignar';
        document.getElementById('detail-categoria').textContent = data.nombre_categoria || 'Sin Asignar';
        document.getElementById('detail-division').textContent = data.division || 'Sin Asignar';
        document.getElementById('detail-eps').textContent = data.eps || 'Sin registrar';
        document.getElementById('detail-rh').textContent = data.rh || 'Sin registrar';
        document.getElementById('detail-peso').textContent = data.peso ? (data.peso + ' kg') : 'Sin registrar';
        
        const displayWeb = document.getElementById('detail-mostrar-web');
        displayWeb.textContent = data.mostrar_en_web == 1 ? 'Sí (Visible)' : 'No (Oculto)';
        displayWeb.className = data.mostrar_en_web == 1 ? 'text-emerald-600 font-bold' : 'text-slate-400 font-bold';

        const detailInstagram = document.getElementById('detail-instagram');
        if (data.instagram_url) {
            detailInstagram.innerHTML = `<a href="${data.instagram_url}" target="_blank" class="text-tkd-blue hover:underline">Ver video/enlace ↗</a>`;
        } else {
            detailInstagram.textContent = 'Ninguno';
        }

        document.getElementById('detail-descripcion').textContent = data.descripcion_perfil || 'Sin biografía registrada.';
    }

    function closeDetailModal() {
        document.getElementById('member-detail-modal').classList.add('hidden');
        activeMemberData = null;
    }

    document.getElementById('detail-btn-edit')?.addEventListener('click', () => {
        if (activeMemberData) {
            const d = {...activeMemberData};
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
        return age;
    }

    // Filtrado en vivo y Gráficos
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput  = document.getElementById('member-search');
        const sedeSelect   = document.getElementById('filter-sede');
        const nivelSelect  = document.getElementById('filter-nivel');
        const rolSelect    = document.getElementById('filter-rol');
        const estadoSelect = document.getElementById('filter-estado');
        const resetBtn     = document.getElementById('btn-reset-filters');
        const rows         = document.querySelectorAll('.member-row');

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
            data: { labels: [], datasets: [{ label: 'Miembros', data: [], backgroundColor: '#3b82f6', borderRadius: 8 }] },
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
                        ticks: { color: tickColor, font: { size: 9 }, stepSize: 3 }, 
                        beginAtZero: true 
                    }
                }
            }
        });

        function filterMembers() {
            const query = searchInput.value.toLowerCase().trim();
            const sede  = sedeSelect.value;
            const nivel = nivelSelect.value;
            const rol   = rolSelect.value;
            const estado= estadoSelect.value;

            let visibleRows = [];

            rows.forEach(r => {
                const rNombre = r.getAttribute('data-nombre') || '';
                const rDoc    = r.getAttribute('data-doc') || '';
                const rSede   = r.getAttribute('data-sede') || '';
                const rNivel  = r.getAttribute('data-nivel') || '';
                const rRol    = r.getAttribute('data-rol') || '';
                const rEstado = r.getAttribute('data-estado') || '';

                const matchesQuery = !query || rNombre.includes(query) || rDoc.includes(query);
                const matchesSede  = sede === 'all' || rSede === sede;
                const matchesNivel = nivel === 'all' || rNivel === nivel;
                const matchesRol   = rol === 'all' || rRol === rol;
                const matchesEstado= estado === 'all' || rEstado === estado;

                if (matchesQuery && matchesSede && matchesNivel && matchesRol && matchesEstado) {
                    r.style.display = '';
                    visibleRows.push(r);
                } else {
                    r.style.display = 'none';
                }
            });

            // Toggle reset button
            const isFiltered = query || sede !== 'all' || nivel !== 'all' || rol !== 'all' || estado !== 'all';
            resetBtn.classList.toggle('hidden', !isFiltered);

            // Update charts
            updateCharts(visibleRows);
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

            // Ordenar cinturones respetando la jerarquía de grados
            const cLabels = Object.keys(cintMap);
            cLabels.sort((a, b) => {
                let indexA = BELT_ORDER.indexOf(a);
                let indexB = BELT_ORDER.indexOf(b);
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
            const cBorder = cLabels.map(l => {
                const key = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
                return beltColors[key].border;
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

        searchInput.addEventListener('input', filterMembers);
        sedeSelect.addEventListener('change', filterMembers);
        nivelSelect.addEventListener('change', filterMembers);
        rolSelect.addEventListener('change', filterMembers);
        estadoSelect.addEventListener('change', filterMembers);

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            sedeSelect.value = 'all';
            nivelSelect.value = 'all';
            rolSelect.value = 'all';
            estadoSelect.value = 'all';
            filterMembers();
        });

        filterMembers();
    });

    // Selección Masiva y Borrado
    const selectAllCb = document.getElementById('select-all-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const bulkCount = document.getElementById('bulk-count');

    function updateBulkUI() {
        const checked = document.querySelectorAll('.member-checkbox:checked');
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
        document.querySelectorAll('.member-row').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.member-checkbox');
                if (cb) cb.checked = isChecked;
            }
        });
        updateBulkUI();
    });

    document.getElementById('members-tbody')?.addEventListener('change', (e) => {
        if (e.target.classList.contains('member-checkbox')) {
            updateBulkUI();
        }
    });

    function clearSelection() {
        document.querySelectorAll('.member-checkbox').forEach(cb => cb.checked = false);
        updateBulkUI();
    }

    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.member-checkbox:checked');
        if (checked.length === 0) return;

        if (!confirm(`¿Estás seguro de eliminar a los ${checked.length} miembros seleccionados? Esta acción es irreversible.`)) {
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
        nombre:    { label: 'Nombre Completo', get: m => (m.nombre + ' ' + m.apellido).trim() },
        documento: { label: 'Documento',       get: m => (m.tipo_documento || '') + ' ' + (m.numero_documento || '') },
        cinturon:  { label: 'Cinturón',        get: m => m.nombre_nivel || 'Sin Asignar' },
        sede:      { label: 'Sede',            get: m => m.nombre_sede || 'Sin Asignar' },
        rol:       { label: 'Rol',             get: m => m.rol_id || '' },
        telefono:  { label: 'Teléfono',        get: m => m.telefono || '' },
        correo:    { label: 'Correo',          get: m => m.correo || '' },
        categoria: { label: 'Categoría',       get: m => m.nombre_categoria || '' },
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

        // Obtener miembros visibles
        const visibleIds = new Set();
        document.querySelectorAll('.member-row').forEach(r => {
            if (r.style.display !== 'none') {
                visibleIds.add(r.getAttribute('data-id'));
            }
        });

        const exportData = ALL_MEMBERS_DATA.filter(m => visibleIds.has(String(m.id)));
        const headers = checkedCols.map(c => EXPORT_COLUMNS[c].label);
        const rows = exportData.map(m => checkedCols.map(c => EXPORT_COLUMNS[c].get(m)));

        const filename = 'Miembros_Jinhwan_' + new Date().toISOString().slice(0, 10);

        if (currentExportType === 'excel') {
            const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Miembros');
            XLSX.writeFile(workbook, filename + '.xlsx');
        } else {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF(checkedCols.length > 5 ? 'landscape' : 'portrait');
            doc.text('Reporte de Miembros - Jinhwan Corporation', 14, 15);
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

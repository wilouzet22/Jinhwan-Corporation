<?php
include __DIR__ . '/../layout/administracion_cabecera.php'; 

$total_maestros = count($maestros);
$total_activos = count(array_filter($maestros, fn($m) => ($m['activo'] ?? 0) == 1));
$total_inactivos = $total_maestros - $total_activos;
?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">
        
        <!-- Encabezado y Acciones -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Gestión de Maestros
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Administra instructores, profesores, asignación de sedes y permisos especiales.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" onclick="openModal('add')" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none cursor-pointer">
                    <span class="material-icons-outlined text-base">sports_martial_arts</span>
                    <span>Nuevo Maestro</span>
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-sm flex items-center justify-between transition-colors">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Maestros</p>
                    <p class="text-2xl font-display font-bold text-slate-900 dark:text-white mt-1"><?= $total_maestros ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">sports_martial_arts</span>
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
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="teacher-search" placeholder="Buscar por nombre o doc..." 
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

        <!-- Tabla de Maestros -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse" id="teachers-table">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3">Maestro / Instructor</th>
                            <th scope="col" class="px-3 py-3">Sede Principal</th>
                            <th scope="col" class="px-3 py-3">Contacto</th>
                            <th scope="col" class="px-3 py-3">Permisos Extra</th>
                            <th scope="col" class="px-3 py-3 text-center">Web</th>
                            <th scope="col" class="px-3 py-3 text-center">Estado</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors" id="teachers-tbody">
                        <?php if (empty($maestros)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    No hay maestros registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($maestros as $m): ?>
                                <?php 
                                    $permisos = json_decode($m['permisos_extra'] ?? '{}', true) ?: [];
                                ?>
                                <tr class="teacher-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all" 
                                    data-id="<?= $m['id_maestro'] ?>"
                                    data-nombre="<?= htmlspecialchars(strtolower($m['nombre'] . ' ' . $m['apellido'])) ?>"
                                    data-doc="<?= htmlspecialchars($m['num_doc'] ?? '') ?>"
                                    data-sede="<?= htmlspecialchars($m['sede_nombre'] ?? 'Sin Asignar') ?>"
                                    data-estado="<?= ($m['activo'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>">
                                    
                                    <!-- Maestro (Avatar + Nombre + Doc) -->
                                    <td class="px-4 py-2.5 font-semibold">
                                        <div class="flex items-center gap-2.5">
                                            <?php if (!empty($m['foto_perfil'])): ?>
                                                <img src="<?= base_url('/public/uploads/perfiles/' . $m['foto_perfil']) ?>" class="w-8 h-8 rounded-full object-cover shadow-xs shrink-0 border border-slate-200 dark:border-slate-700">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    <?= strtoupper(substr($m['nombre'] ?? 'M', 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="min-w-0">
                                                <div class="text-slate-900 dark:text-white font-bold text-sm truncate leading-tight">
                                                    <?= htmlspecialchars($m['nombre'] ?? '') . ' ' . htmlspecialchars($m['apellido'] ?? '') ?>
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal flex items-center gap-1 mt-0.5">
                                                    <span class="font-mono bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-1 py-0.2 rounded text-[10px]"><?= htmlspecialchars($m['tipo_documento'] ?? 'CC') ?></span>
                                                    <span><?= htmlspecialchars($m['num_doc'] ?? 'S/D') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Sede -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="flex items-center gap-1 font-bold text-slate-800 dark:text-slate-200">
                                            <span class="material-icons-outlined text-xs text-tkd-blue">place</span>
                                            <span><?= htmlspecialchars($m['sede_nombre'] ?? 'Sin Asignar') ?></span>
                                        </div>
                                    </td>

                                    <!-- Contacto -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="text-slate-800 dark:text-slate-300 font-medium"><?= htmlspecialchars($m['telefono'] ?? '-') ?></div>
                                        <div class="text-[11px] text-slate-400 truncate mt-0.5"><?= htmlspecialchars($m['correo'] ?? '-') ?></div>
                                    </td>

                                    <!-- Permisos Extra -->
                                    <td class="px-3 py-2.5 text-xs">
                                        <div class="flex flex-wrap gap-1">
                                            <?php foreach ($permisos as $permKey => $hasPerm): ?>
                                                <?php if ($hasPerm): ?>
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/40">
                                                        <?= htmlspecialchars($permKey) ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if (!array_filter($permisos)): ?>
                                                <span class="text-slate-400 text-[11px]">Básicos</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Web -->
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <?php if (($m['mostrar_en_web'] ?? 0) == 1): ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-bold">
                                                <span class="material-icons-outlined text-sm">visibility</span> Sí
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                                <span class="material-icons-outlined text-sm">visibility_off</span> No
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <?php if (($m['activo'] ?? 0) == 1): ?>
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
                                            <button type="button" onclick='openDetailModal(<?= json_encode($m) ?>)' class="text-slate-500 hover:text-tkd-blue dark:text-slate-400 dark:hover:text-blue-400 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Ver Detalle">
                                                <span class="material-icons-outlined text-base">visibility</span>
                                            </button>
                                            
                                            <!-- Editar -->
                                            <button type="button" onclick='openModal("edit", <?= json_encode($m) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-all cursor-pointer" title="Editar">
                                                <span class="material-icons-outlined text-base">edit</span>
                                            </button>
                                            
                                            <!-- Eliminar -->
                                            <form action="<?= base_url('/admin/maestros/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar a este maestro?');">
                                                <input type="hidden" name="id" value="<?= $m['id_maestro'] ?>">
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

<!-- MODAL CREAR / EDITAR MAESTRO -->
<div id="teacher-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden transition-all my-8">
        <form id="teacher-form" method="POST" enctype="multipart/form-data" class="flex flex-col">
            <input type="hidden" name="id" id="modal-id">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-bold text-slate-900 dark:text-white">Nuevo Maestro</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">✕</button>
            </div>

            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                
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
                            <option value="CC">CC - Cédula</option>
                            <option value="TI">TI - Tarjeta de Identidad</option>
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

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Correo *</label>
                        <input type="email" name="correo" id="modal-correo" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="modal-telefono" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-tkd-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Sede Principal</label>
                        <select name="id_sede" id="modal-id-sede" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                            <option value="">Sin Asignar</option>
                            <?php foreach($sedes_list as $s): ?>
                                <option value="<?= $s['id_sede'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Foto de Perfil</label>
                        <input type="file" name="foto_perfil" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Contraseña</label>
                        <input type="password" name="clave" id="modal-clave" placeholder="Por defecto: jinhwa2024" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none">
                        <span id="clave-hint" class="hidden text-[10px] text-slate-400">Dejar en blanco para no modificar.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Descripción / Biografía</label>
                    <textarea name="descripcion_perfil" id="modal-desc" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Logros y Trayectoria</label>
                    <textarea name="logros" id="modal-logros" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:outline-none"></textarea>
                </div>

                <!-- Permisos Extra -->
                <div class="p-3 bg-purple-50/50 dark:bg-purple-950/20 rounded-xl border border-purple-200 dark:border-purple-800/40 space-y-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400">Permisos Extra de Administración</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Otorga acceso a módulos específicos del panel de administración:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="permiso_sedes" id="perm-sedes" class="rounded text-purple-600"> Sedes
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="permiso_registros" id="perm-registros" class="rounded text-purple-600"> Solicitudes
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="permiso_ascensos" id="perm-ascensos" class="rounded text-purple-600"> Temarios / Ascensos
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="permiso_calendario" id="perm-calendario" class="rounded text-purple-600"> Calendario
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="permiso_galeria" id="perm-galeria" class="rounded text-purple-600"> Galería
                        </label>
                    </div>
                </div>

                <!-- Opciones / Toggles -->
                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activo" id="modal-activo" value="1" checked class="w-4 h-4 rounded text-purple-600">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Maestro Activo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="mostrar_en_web" id="modal-web" value="1" class="w-4 h-4 rounded text-purple-600">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Mostrar en Perfil Público Web</span>
                    </label>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3 bg-slate-50/50 dark:bg-slate-950/50">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-bold uppercase text-slate-500 hover:text-slate-700 dark:hover:text-white">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold uppercase bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-md">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETALLE DE MAESTRO -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 relative">
        <button type="button" onclick="closeDetailModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">✕</button>
        <div id="detail-content"></div>
    </div>
</div>

<script>
// Filtros
const searchInput = document.getElementById('teacher-search');
const filterSede = document.getElementById('filter-sede');
const filterEstado = document.getElementById('filter-estado');
const btnReset = document.getElementById('btn-reset-filters');
const rows = document.querySelectorAll('.teacher-row');

function filterTable() {
    const text = searchInput.value.toLowerCase().trim();
    const sede = filterSede.value;
    const estado = filterEstado.value;

    let hasFilter = text || sede !== 'all' || estado !== 'all';
    btnReset.classList.toggle('hidden', !hasFilter);

    rows.forEach(r => {
        const rNombre = r.dataset.nombre;
        const rDoc = r.dataset.doc;
        const rSede = r.dataset.sede;
        const rEstado = r.dataset.estado;

        const matchText = !text || rNombre.includes(text) || rDoc.includes(text);
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

// Modal Agregar / Editar
function openModal(mode, data = null) {
    const modal = document.getElementById('teacher-modal');
    const form = document.getElementById('teacher-form');
    const title = document.getElementById('modal-title');
    const claveHint = document.getElementById('clave-hint');

    form.reset();

    if (mode === 'edit' && data) {
        title.textContent = 'Editar Maestro';
        form.action = '<?= base_url('/admin/maestros/update') ?>';
        document.getElementById('modal-id').value = data.id_maestro;
        document.getElementById('modal-nombre').value = data.nombre || '';
        document.getElementById('modal-apellido').value = data.apellido || '';
        document.getElementById('modal-tipo-doc').value = data.tipo_documento || 'CC';
        document.getElementById('modal-num-doc').value = data.num_doc || '';
        document.getElementById('modal-fnac').value = data.fecha_nacimiento || '';
        document.getElementById('modal-correo').value = data.correo || '';
        document.getElementById('modal-telefono').value = data.telefono || '';
        if (data.id_sede) document.getElementById('modal-id-sede').value = data.id_sede;
        document.getElementById('modal-desc').value = data.descripcion_perfil || '';
        document.getElementById('modal-logros').value = data.logros || '';
        document.getElementById('modal-activo').checked = data.activo == 1;
        document.getElementById('modal-web').checked = data.mostrar_en_web == 1;

        // Parse permisos
        let permisos = {};
        try {
            permisos = typeof data.permisos_extra === 'string' ? JSON.parse(data.permisos_extra || '{}') : (data.permisos_extra || {});
        } catch(e) {}
        document.getElementById('perm-sedes').checked = !!permisos.sedes;
        document.getElementById('perm-registros').checked = !!permisos.registros;
        document.getElementById('perm-ascensos').checked = !!permisos.ascensos;
        document.getElementById('perm-calendario').checked = !!permisos.calendario;
        document.getElementById('perm-galeria').checked = !!permisos.galeria;

        claveHint.classList.remove('hidden');
    } else {
        title.textContent = 'Nuevo Maestro';
        form.action = '<?= base_url('/admin/maestros/create') ?>';
        document.getElementById('modal-id').value = '';
        document.getElementById('modal-activo').checked = true;
        document.getElementById('modal-web').checked = false;
        claveHint.classList.add('hidden');
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('teacher-modal').classList.add('hidden');
}

// Modal Detalle
function openDetailModal(data) {
    const modal = document.getElementById('detail-modal');
    const container = document.getElementById('detail-content');
    
    container.innerHTML = `
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-purple-100 dark:bg-purple-950/60 flex items-center justify-center font-bold text-xl text-purple-700 dark:text-purple-300">
                ${data.foto_perfil ? `<img src="<?= base_url('/public/uploads/perfiles/') ?>${data.foto_perfil}" class="w-full h-full object-cover">` : data.nombre.charAt(0)}
            </div>
            <div>
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">${data.nombre} ${data.apellido}</h4>
                <p class="text-xs text-slate-400">${data.tipo_documento || 'CC'}: ${data.num_doc || 'S/D'}</p>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ${data.activo == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}">
                    ${data.activo == 1 ? 'Activo' : 'Inactivo'}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-200 dark:border-slate-800 pt-3">
            <div><span class="text-slate-400">Sede Principal:</span> <strong class="text-slate-700 dark:text-slate-200">${data.sede_nombre || 'Sin asignar'}</strong></div>
            <div><span class="text-slate-400">Teléfono:</span> <strong class="text-slate-700 dark:text-slate-200">${data.telefono || '-'}</strong></div>
            <div class="col-span-2"><span class="text-slate-400">Correo:</span> <strong class="text-slate-700 dark:text-slate-200">${data.correo || '-'}</strong></div>
            ${data.descripcion_perfil ? `<div class="col-span-2"><span class="text-slate-400">Perfil:</span> <p class="text-slate-600 dark:text-slate-300 mt-1">${data.descripcion_perfil}</p></div>` : ''}
            ${data.logros ? `<div class="col-span-2"><span class="text-slate-400">Logros:</span> <p class="text-slate-600 dark:text-slate-300 mt-1">${data.logros}</p></div>` : ''}
        </div>
    `;

    modal.classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

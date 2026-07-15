<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10 gap-4">
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Gestión de Miembros</h1>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <!-- Bulk Actions Bar (aparece cuando hay selección) -->
                <div id="bulk-action-bar" class="hidden w-full sm:w-auto items-center gap-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800/40 rounded-xl px-4 py-2.5 transition-all">
                    <span id="bulk-count" class="text-sm font-bold text-red-700 dark:text-red-400">0 seleccionados</span>
                    <button onclick="confirmBulkDelete()" class="flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider px-3 py-2 rounded-lg transition-colors focus:outline-none">
                        <span class="material-icons-outlined text-sm">delete_sweep</span>
                        Borrar seleccionados
                    </button>
                    <button onclick="clearSelection()" class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors focus:outline-none">
                        Cancelar
                    </button>
                </div>
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-slate-400 text-sm">search</span>
                    </div>
                    <input type="text" id="member-search" placeholder="Buscar miembro..." 
                           class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>
                
                <!-- Export Buttons -->
                <button onclick="openExportModal('excel')" class="w-full sm:w-auto bg-green-600 text-white font-display font-bold uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all flex items-center justify-center gap-2 focus:outline-none shrink-0" title="Exportar a Excel">
                    <span class="material-icons-outlined">table_view</span>
                    <span class="hidden sm:inline">Excel</span>
                </button>
                <button onclick="openExportModal('pdf')" class="w-full sm:w-auto bg-red-600 text-white font-display font-bold uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md hover:bg-red-700 hover:shadow-lg transition-all flex items-center justify-center gap-2 focus:outline-none shrink-0" title="Exportar a PDF">
                    <span class="material-icons-outlined">picture_as_pdf</span>
                    <span class="hidden sm:inline">PDF</span>
                </button>

                <button onclick="openModal('add')" class="w-full sm:w-auto bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all flex items-center justify-center gap-2 focus:outline-none shrink-0">
                    <span class="material-icons-outlined">person_add</span>
                    <span class="hidden lg:inline">Añadir Nuevo Miembro</span>
                    <span class="lg:hidden">Añadir</span>
                </button>
            </div>
        </div>
        <!-- Filtros y Estadísticas -->
        <div class="mb-8 grid grid-cols-1 lg:grid-cols-4 gap-6 relative z-10">
            <!-- Filtros (Izquierda) -->
            <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons-outlined text-tkd-blue">filter_alt</span>
                        Filtros
                    </h2>
                    <button id="btn-reset-filters" class="text-xs text-slate-400 hover:text-tkd-blue transition-colors hidden focus:outline-none">Limpiar</button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Sede</label>
                        <select id="filter-sede" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                            <option value="all">Todas las Sedes</option>
                            <?php foreach($sedes_list as $sede): ?>
                                <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Cinturón</label>
                        <select id="filter-nivel" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                            <option value="all">Todos los Cinturones</option>
                            <?php foreach($niveles_list as $nivel): ?>
                                <option value="<?= htmlspecialchars($nivel['nombre']) ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Rol</label>
                        <select id="filter-rol" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                            <option value="all">Todos los Roles</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Instructor">Instructor</option>
                            <option value="Alumno">Alumno</option>
                            <option value="Invitado">Invitado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Estado</label>
                        <select id="filter-estado" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                            <option value="all">Todos</option>
                            <option value="Activo">Activo</option>
                            <option value="Pendiente">Pendiente</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Gráficas (Derecha) -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cinturones -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col transition-colors duration-300">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Distribución de Cinturones</h3>
                    <div class="flex-grow relative w-full flex items-center justify-center min-h-[200px]">
                        <canvas id="chart-cinturones"></canvas>
                        <div id="chart-cinturones-empty" class="absolute inset-0 flex items-center justify-center text-sm text-slate-400 hidden">Sin datos para mostrar</div>
                    </div>
                </div>
                
                <!-- Sedes -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col transition-colors duration-300">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Alumnos por Sede</h3>
                    <div class="flex-grow relative w-full flex items-center justify-center min-h-[200px]">
                        <canvas id="chart-sedes"></canvas>
                        <div id="chart-sedes-empty" class="absolute inset-0 flex items-center justify-center text-sm text-slate-400 hidden">Sin datos para mostrar</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                    <tr>
                        <th scope="col" class="px-4 py-4 w-10">
                            <input type="checkbox" id="select-all-checkbox"
                                   class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-800 cursor-pointer"
                                   title="Seleccionar todos">
                        </th>
                        <th scope="col" class="px-6 py-4">Nombre</th>
                        <th scope="col" class="px-6 py-4">Documento</th>
                        <th scope="col" class="px-6 py-4">Cinturón</th>
                        <th scope="col" class="px-6 py-4">Sede</th>
                        <th scope="col" class="px-6 py-4">Rol</th>
                        <th scope="col" class="px-6 py-4">Contacto</th>
                        <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    <?php foreach ($miembros as $miembro): ?>
                        <tr class="member-row hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all" 
                            data-id="<?= $miembro['id'] ?>"
                            data-nombre="<?= htmlspecialchars(strtolower($miembro['nombre'] . ' ' . $miembro['apellido'])) ?>"
                            data-sede="<?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>"
                            data-nivel="<?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>"
                            data-rol="<?php 
                                switch($miembro['rol_id']) {
                                    case Roles::ADMINISTRADOR: echo 'Administrador'; break;
                                    case Roles::MAESTRO: echo 'Instructor'; break;
                                    case Roles::ESTUDIANTE: echo 'Alumno'; break;
                                    default: echo 'Invitado'; break;
                                }
                            ?>"
                            data-estado="<?= $miembro['activo'] == 1 ? 'Activo' : 'Pendiente' ?>">
                            <td class="px-4 py-4">
                                <input type="checkbox" name="member-checkbox" value="<?= $miembro['id'] ?>"
                                       class="member-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-800 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800/80 text-slate-600 dark:text-slate-300 flex items-center justify-center mr-3 font-bold transition-colors shrink-0">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-800 dark:text-white transition-colors"><?= htmlspecialchars($miembro['nombre'] ?? '') . ' ' . htmlspecialchars($miembro['apellido'] ?? '') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-2 py-1 rounded text-slate-500 dark:text-slate-400 mr-1 transition-colors"><?= htmlspecialchars($miembro['tipo_documento'] ?? '') ?></span>
                                <?= htmlspecialchars($miembro['numero_documento'] ?? '') ?>
                            </td>
                            <?php
                                $nivel = strtolower($miembro['nombre_nivel'] ?? '');
                                $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-900/60 dark:text-slate-400 dark:border-slate-800/80'; // Default
                                
                                if (str_contains($nivel, 'blanco')) {
                                    $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-200 shadow-sm';
                                } elseif (str_contains($nivel, 'amarillo')) {
                                    $beltClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-950/60 dark:text-yellow-400 dark:border-yellow-800/30';
                                } elseif (str_contains($nivel, 'verde')) {
                                    $beltClass = 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/60 dark:text-green-400 dark:border-green-800/30';
                                } elseif (str_contains($nivel, 'azul')) {
                                    $beltClass = 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-800/30';
                                } elseif (str_contains($nivel, 'rojo')) {
                                    $beltClass = 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/60 dark:text-red-400 dark:border-red-800/30';
                                } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                                    $beltClass = 'bg-slate-900 text-white border border-slate-900 dark:bg-slate-950 dark:border-slate-800 shadow-md';
                                }
                            ?>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors <?= $beltClass ?>">
                                    <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <div class="flex items-center gap-1 text-sm font-medium">
                                    <span class="material-icons-outlined text-sm text-tkd-blue">place</span>
                                    <?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php switch($miembro['rol_id']): 
                                    case Roles::ADMINISTRADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/60 dark:text-red-400 dark:border-red-800/30 transition-colors">Administrador</span>
                                    <?php break; case Roles::MAESTRO: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/60 dark:text-purple-400 dark:border-purple-800/30 transition-colors">Instructor</span>
                                    <?php break; case Roles::ESTUDIANTE: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800/30 transition-colors">Alumno</span>
                                    <?php break; default: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-900/60 dark:text-slate-400 dark:border-slate-800/80 transition-colors">Invitado</span>
                                 <?php endswitch; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">
                                <div class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($miembro['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button onclick='openDetailModal(<?= json_encode($miembro) ?>)' class="text-slate-500 hover:text-tkd-blue dark:text-slate-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/20 transition-all focus:outline-none" title="Ver Detalle">
                                        <span class="material-icons-outlined text-[18px]">visibility</span>
                                    </button>
                                    <button onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-950/20 transition-all focus:outline-none" title="Editar">
                                        <span class="material-icons-outlined text-[18px]">edit</span>
                                    </button>
                                    <form action="<?= base_url('/admin/miembros/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                                        <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/20 transition-all focus:outline-none" title="Borrar">
                                            <span class="material-icons-outlined text-[18px]">delete</span>
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
   <!-- Modal -->
<div id="member-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between transition-colors">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white transition-colors"></h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form id="member-form" action="<?= base_url('/admin/miembros/create') ?>" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="id" id="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-[10px] font-bold text-tkd-blue uppercase tracking-widest mb-1">Información Personal</h3>
                </div>
                
                <div>
                    <label for="nombre" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="apellido" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Apellido</label>
                    <input type="text" name="apellido" id="apellido" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Tipo Documento</label>
                    <select name="tipo_documento" id="tipo_documento" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <option value="TI">T.I</option>
                        <option value="CC">C.C</option>
                        <option value="CE">C.E</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div>
                    <label for="numero_documento" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Numero Documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                
                <div>
                    <label for="fecha_nacimiento" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div></div>
 
                <!-- Contact Info -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h3 class="text-[10px] font-bold text-tkd-blue uppercase tracking-widest mb-1">Contacto</h3>
                </div>
 
                <div>
                    <label for="telefono" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="correo" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
 
                <!-- Academic Info -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h3 class="text-[10px] font-bold text-tkd-blue uppercase tracking-widest mb-1">Información Académica</h3>
                </div>
 
                <div>
                    <label for="nivel_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Cinturón (Nivel)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <?php foreach($niveles_list as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="sede_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Sede</label>
                    <select name="sede_id" id="sede_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <?php foreach($sedes_list as $sede): ?>
                            <option value="<?= $sede['id'] ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="categoria_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Categoría Deportiva</label>
                    <select name="categoria_id" id="categoria_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <?php foreach($categorias_list as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="division" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">División de Peso</label>
                    <input type="text" name="division" id="division" placeholder="Ej. Minimosca -54kg" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="ctgc" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Código CTGC</label>
                    <input type="text" name="ctgc" id="ctgc" placeholder="Ej. CTGC-12345" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="rol_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Rol</label>
                    <select name="rol_id" id="rol_id" onchange="togglePermisos(this.value)" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <option value="<?= Roles::ESTUDIANTE ?>">Alumno (Estudiante)</option>
                        <option value="<?= Roles::MAESTRO ?>">Instructor (Maestro)</option>
                        <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                    </select>
                </div>
 
                <!-- Info de Salud -->
                <div class="col-span-1 md:col-span-2 mt-4 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <h3 class="text-[10px] font-bold text-tkd-blue uppercase tracking-widest mb-1">Información de Salud</h3>
                </div>
                
                <div>
                    <label for="peso" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Peso (kg)</label>
                    <input type="number" step="0.01" name="peso" id="peso" placeholder="Ej. 65.50" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="eps" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">EPS</label>
                    <input type="text" name="eps" id="eps" placeholder="Ej. Sura, Sanitas" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                </div>
                <div>
                    <label for="rh" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Grupo Sanguíneo (RH)</label>
                    <select name="rh" id="rh" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
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
                <div></div>

                <!-- Perfil Web Público -->
                <div class="col-span-1 md:col-span-2 mt-4 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <h3 class="text-[10px] font-bold text-tkd-blue uppercase tracking-widest mb-1">Perfil Web Público</h3>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="mostrar_en_web" id="mostrar_en_web" value="1" class="form-checkbox h-5 w-5 text-tkd-blue rounded border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950/60 focus:ring-tkd-blue">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Mostrar este perfil en la web pública del club</span>
                    </label>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="foto_perfil" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Foto de Perfil</label>
                    <div class="flex items-center gap-4">
                        <div id="foto-preview-container" class="h-16 w-16 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-100 dark:bg-slate-950 flex items-center justify-center shrink-0">
                            <span id="foto-preview-placeholder" class="material-icons-outlined text-slate-400 text-2xl font-bold">person</span>
                            <img id="foto-preview-img" class="h-full w-full object-cover hidden" alt="Foto">
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300">
                            <div id="delete-foto-container" class="mt-2 hidden">
                                <label class="flex items-center space-x-2 cursor-pointer text-xs text-red-600">
                                    <input type="checkbox" name="eliminar_foto" id="eliminar_foto" value="1" class="rounded text-red-600 border-slate-300 focus:ring-red-500">
                                    <span>Eliminar foto actual</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="descripcion_perfil" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Descripción del Perfil</label>
                    <textarea name="descripcion_perfil" id="descripcion_perfil" rows="3" placeholder="Breve biografía del miembro..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none"></textarea>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="logros" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Logros y Reconocimientos</label>
                    <textarea name="logros" id="logros" rows="3" placeholder="Medallas, títulos, participaciones destacadas..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none"></textarea>
                </div>

                <!-- Permisos Dinámicos (Solo para Maestros) -->
                <div id="permisos_section" class="col-span-1 md:col-span-2 mt-4 hidden">
                    <h3 class="text-[10px] font-bold text-purple-600 uppercase tracking-widest mb-3">Permisos Extra (Sub-administrador)</h3>
                    <div class="grid grid-cols-2 gap-4 bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl border border-purple-100 dark:border-purple-800/30">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_sedes" id="permiso_sedes" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gestionar Sedes</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_registros" id="permiso_registros" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Aprobar Registros</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_ascensos" id="permiso_ascensos" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Aprobar Ascensos</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_calendario" id="permiso_calendario" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gestionar Calendario</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_galeria" id="permiso_galeria" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Gestionar Galería</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="permiso_reportes" id="permiso_reportes" class="form-checkbox h-5 w-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Ver Reportes</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-slate-200 dark:border-slate-800 transition-colors">
                <button type="button" onclick="closeModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Detalle Completo de Miembro -->
<div id="member-detail-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-3xl max-h-[92vh] overflow-y-auto shadow-2xl transition-colors duration-300 relative overflow-hidden">
        
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>
        
        <!-- Header Panel with profile photo and name -->
        <div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-950/20 border-b border-slate-100 dark:border-slate-850 flex flex-col sm:flex-row items-center gap-6 relative transition-colors">
            
            <button onclick="closeDetailModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined text-2xl">close</span>
            </button>
            
            <!-- Large Profile Pic/Initial -->
            <div class="h-24 w-24 rounded-2xl border-2 border-white dark:border-slate-800 shadow-lg overflow-hidden bg-white dark:bg-slate-950 flex items-center justify-center shrink-0 transition-transform hover:scale-105">
                <img id="detail-foto-img" class="h-full w-full object-cover hidden" alt="Foto">
                <span id="detail-foto-initial" class="text-3xl font-display font-bold text-slate-700 dark:text-slate-300"></span>
            </div>
            
            <!-- Main Title & Tags -->
            <div class="text-center sm:text-left">
                <h2 id="detail-fullname" class="text-2xl md:text-3xl font-display font-bold text-slate-900 dark:text-white tracking-tight uppercase"></h2>
                
                <div class="flex flex-wrap gap-2 justify-center sm:justify-start mt-2">
                    <span id="detail-badge-rol" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
                    <span id="detail-badge-estado" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
                </div>
            </div>
        </div>
        
        <!-- Detailed Grid Data -->
        <div class="p-6 md:p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Sección 1: Información Personal -->
                <div class="bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-850/60">
                    <h3 class="text-xs font-bold text-tkd-blue uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">badge</span>
                        Datos Personales
                    </h3>
                    <div class="space-y-3.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Documento:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-documento"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Fecha Nacimiento:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-fecha-n"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Edad:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-edad"></span>
                        </div>
                    </div>
                </div>
 
                <!-- Sección 2: Contacto -->
                <div class="bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-850/60">
                    <h3 class="text-xs font-bold text-tkd-blue uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">contacts</span>
                        Contacto
                    </h3>
                    <div class="space-y-3.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Teléfono:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-telefono"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Email:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-[180px]" id="detail-correo" title=""></span>
                        </div>
                    </div>
                </div>
 
                <!-- Sección 3: Datos de Taekwondo -->
                <div class="bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-850/60">
                    <h3 class="text-xs font-bold text-tkd-blue uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">sports_kabaddi</span>
                        Información Deportiva
                    </h3>
                    <div class="space-y-3.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Sede:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-sede"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Cinturón (Grado):</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-cinturon"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Categoría:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-categoria"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">División:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-division"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Código CTGC:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold font-mono" id="detail-ctgc"></span>
                        </div>
                    </div>
                </div>
 
                <!-- Sección 4: Información de Salud -->
                <div class="bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-850/60">
                    <h3 class="text-xs font-bold text-tkd-blue uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">health_and_safety</span>
                        Salud y Física
                    </h3>
                    <div class="space-y-3.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">EPS:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-eps"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Grupo Sanguíneo:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold font-mono" id="detail-rh"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Peso:</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold" id="detail-peso"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Sección 5: Perfil Público Web -->
                <div class="col-span-1 md:col-span-2 bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-850/60">
                    <h3 class="text-xs font-bold text-tkd-blue uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">public</span>
                        Perfil Web del Club
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm pb-2 border-b border-slate-200/40 dark:border-slate-800/40">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Mostrar en la Web:</span>
                            <span class="font-semibold" id="detail-mostrar-web"></span>
                        </div>
                        <div class="text-sm">
                            <span class="block text-slate-400 dark:text-slate-500 font-medium mb-1">Descripción:</span>
                            <div class="text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-3 text-xs leading-relaxed italic animate-none" id="detail-descripcion"></div>
                        </div>
                        <div class="text-sm">
                            <span class="block text-slate-400 dark:text-slate-500 font-medium mb-1">Logros & Reconocimientos:</span>
                            <div class="text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-3 text-xs leading-relaxed" id="detail-logros"></div>
                        </div>
                    </div>
                </div>
 
                <!-- Sección 6: Permisos Extra (Solo si es Instructor) -->
                <div id="detail-section-permisos" class="col-span-1 md:col-span-2 bg-purple-50/50 dark:bg-purple-950/10 p-5 rounded-2xl border border-purple-100/50 dark:border-purple-900/20 hidden">
                    <h3 class="text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="material-icons-outlined text-base">lock_person</span>
                        Permisos del Sistema (Instructor)
                    </h3>
                    <div class="grid grid-cols-2 gap-3 text-xs text-slate-700 dark:text-slate-300 font-medium" id="detail-permisos-list">
                        <!-- Permisos dinámicos se listan aquí -->
                    </div>
                </div>
 
            </div>
        </div>
        
        <!-- Footer actions -->
        <div class="p-6 md:p-8 border-t border-slate-100 dark:border-slate-850 flex flex-wrap justify-between items-center bg-slate-50/30 dark:bg-slate-950/10 gap-4 transition-colors">
            
            <!-- Delete action (left-aligned) -->
            <form id="detail-delete-form" action="<?= base_url('/admin/miembros/delete') ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                <input type="hidden" name="id" id="detail-delete-id">
                <button type="submit" class="flex items-center gap-1.5 px-5 py-3 rounded-xl border border-red-200 dark:border-red-800/40 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 font-bold uppercase text-xs transition-colors focus:outline-none">
                    <span class="material-icons-outlined text-sm">delete</span>
                    Eliminar
                </button>
            </form>
            
            <!-- Cancel / Edit actions (right-aligned) -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeDetailModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">
                    Cerrar
                </button>
                <button type="button" id="detail-btn-edit" class="flex items-center gap-1.5 px-6 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">
                    <span class="material-icons-outlined text-sm">edit</span>
                    Editar
                </button>
            </div>
            
        </div>
    </div>
</div>>
    </div>
</div>

<script>
    let activeMemberData = null;

    function openDetailModal(data) {
        activeMemberData = data;
        
        const modal = document.getElementById('member-detail-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Header
        document.getElementById('detail-fullname').textContent = data.nombre + ' ' + data.apellido;
        
        const badgeRol = document.getElementById('detail-badge-rol');
        badgeRol.className = "inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ";
        if (data.rol_id === 'Administracion') {
            badgeRol.textContent = "Administrador";
            badgeRol.classList.add("bg-red-50", "text-red-700", "border", "border-red-200", "dark:bg-red-950/60", "dark:text-red-400", "dark:border-red-800/30");
        } else if (data.rol_id === 'Maestros') {
            badgeRol.textContent = "Instructor";
            badgeRol.classList.add("bg-purple-50", "text-purple-700", "border", "border-purple-200", "dark:bg-purple-950/60", "dark:text-purple-400", "dark:border-purple-800/30");
        } else if (data.rol_id === 'Deportistas') {
            badgeRol.textContent = "Alumno";
            badgeRol.classList.add("bg-emerald-50", "text-emerald-700", "border", "border-emerald-200", "dark:bg-emerald-950/60", "dark:text-emerald-400", "dark:border-emerald-800/30");
        } else {
            badgeRol.textContent = data.rol_id || "Invitado";
            badgeRol.classList.add("bg-slate-100", "text-slate-600", "border", "border-slate-200", "dark:bg-slate-900/60", "dark:text-slate-400", "dark:border-slate-800/80");
        }
        
        const badgeEstado = document.getElementById('detail-badge-estado');
        badgeEstado.className = "inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ";
        if (data.activo == 1) {
            badgeEstado.textContent = "Activo";
            badgeEstado.classList.add("bg-green-50", "text-green-700", "border", "border-green-200", "dark:bg-green-950/60", "dark:text-green-400", "dark:border-green-800/30");
        } else {
            badgeEstado.textContent = "Pendiente";
            badgeEstado.classList.add("bg-amber-50", "text-amber-700", "border", "border-amber-200", "dark:bg-amber-950/60", "dark:text-amber-400", "dark:border-amber-800/30");
        }
        
        // Foto
        const fotoImg = document.getElementById('detail-foto-img');
        const fotoInitial = document.getElementById('detail-foto-initial');
        if (data.foto_perfil) {
            fotoImg.src = '<?= base_url("public/uploads/perfiles/") ?>' + data.foto_perfil;
            fotoImg.classList.remove('hidden');
            fotoInitial.classList.add('hidden');
        } else {
            fotoImg.classList.add('hidden');
            fotoInitial.textContent = data.nombre.substring(0, 1).toUpperCase();
            fotoInitial.classList.remove('hidden');
        }
        
        // Personal Info
        document.getElementById('detail-documento').textContent = (data.tipo_documento || 'TI') + ' ' + data.numero_documento;
        document.getElementById('detail-fecha-n').textContent = formatLocalDate(data.fecha_nacimiento);
        document.getElementById('detail-edad').textContent = calculateAge(data.fecha_nacimiento) + ' años';
        
        // Contacto
        document.getElementById('detail-telefono').textContent = data.telefono || 'Sin asignar';
        const detailCorreo = document.getElementById('detail-correo');
        detailCorreo.textContent = data.correo || 'Sin registrar';
        detailCorreo.title = data.correo || '';
        
        // Deportiva
        document.getElementById('detail-sede').textContent = data.nombre_sede || 'Sin asignar';
        document.getElementById('detail-cinturon').textContent = data.nombre_nivel || 'Ninguno';
        document.getElementById('detail-categoria').textContent = data.nombre_categoria || 'Sin asignar';
        document.getElementById('detail-division').textContent = data.division || 'Sin asignar';
        document.getElementById('detail-ctgc').textContent = data.ctgc || 'Sin asignar';
        
        // Salud
        document.getElementById('detail-eps').textContent = data.eps || 'Sin registrar';
        document.getElementById('detail-rh').textContent = data.rh || 'Sin registrar';
        document.getElementById('detail-peso').textContent = data.peso ? (data.peso + ' kg') : 'Sin registrar';
        
        // Web
        const displayWeb = document.getElementById('detail-mostrar-web');
        if (data.mostrar_en_web == 1) {
            displayWeb.textContent = 'Sí (Visible en web pública)';
            displayWeb.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-0.5 rounded-lg border border-emerald-100 dark:border-emerald-900/30';
        } else {
            displayWeb.textContent = 'No';
            displayWeb.className = 'text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-lg border border-slate-200/50 dark:border-slate-700/50';
        }
        
        document.getElementById('detail-descripcion').textContent = data.descripcion_perfil || 'Sin descripción del perfil.';
        document.getElementById('detail-logros').textContent = data.logros || 'Sin logros registrados.';
        
        // Permisos
        const permisosSection = document.getElementById('detail-section-permisos');
        const permisosList = document.getElementById('detail-permisos-list');
        permisosList.innerHTML = '';
        if ((data.rol_id === 'Maestros' || data.rol_id === 'Profesores' || data.rol_id === 'Monitores') && data.permisos_extra) {
            permisosSection.classList.remove('hidden');
            try {
                const permisos = JSON.parse(data.permisos_extra);
                const list = {
                    'sedes': 'Gestionar Sedes',
                    'registros': 'Aprobar Registros',
                    'ascensos': 'Aprobar Ascensos',
                    'calendario': 'Gestionar Calendario',
                    'galeria': 'Gestionar Galería',
                    'reportes': 'Ver Reportes'
                };
                
                for (const [key, label] of Object.entries(list)) {
                    const status = permisos[key] === true;
                    const item = document.createElement('div');
                    item.className = `flex items-center gap-1.5 px-3 py-1.5 rounded-xl border ${status ? 'bg-purple-50 text-purple-700 border-purple-200/50 dark:bg-purple-950/30 dark:text-purple-400 dark:border-purple-900/30' : 'bg-slate-50 text-slate-400 border-slate-200/50 dark:bg-slate-900/40 dark:text-slate-600 dark:border-slate-850/50'}`;
                    item.innerHTML = `
                        <span class="material-icons-outlined text-sm">${status ? 'check_circle' : 'cancel'}</span>
                        <span>${label}</span>
                    `;
                    permisosList.appendChild(item);
                }
            } catch(e) {
                permisosSection.classList.add('hidden');
            }
        } else {
            permisosSection.classList.add('hidden');
        }
        
        // Deletion form
        document.getElementById('detail-delete-id').value = data.id;
    }
    
    function closeDetailModal() {
        const modal = document.getElementById('member-detail-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        activeMemberData = null;
    }

    function openModal(action, data = null) {
        const modal = document.getElementById('member-modal');
        const form = document.getElementById('member-form');
        const title = document.getElementById('modal-title');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        form.reset();
        
        if (action === 'add') {
            title.textContent = 'Añadir Nuevo Miembro';
            form.action = '<?= base_url('/admin/miembros/create') ?>';
            document.getElementById('id').value = '';
            document.getElementById('rol_id').value = '<?= Roles::ESTUDIANTE ?>';
            togglePermisos('<?= Roles::ESTUDIANTE ?>');
            
            // Reset nuevos campos
            document.getElementById('categoria_id').value = '1';
            document.getElementById('division').value = '';
            document.getElementById('ctgc').value = '';
            document.getElementById('peso').value = '';
            document.getElementById('eps').value = '';
            document.getElementById('rh').value = '';
            document.getElementById('mostrar_en_web').checked = false;
            document.getElementById('descripcion_perfil').value = '';
            document.getElementById('logros').value = '';
            document.getElementById('foto-preview-img').classList.add('hidden');
            document.getElementById('foto-preview-placeholder').classList.remove('hidden');
            document.getElementById('delete-foto-container').classList.add('hidden');
            document.getElementById('eliminar_foto').checked = false;
            
        } else if (action === 'edit') {
            title.textContent = 'Editar Miembro';
            form.action = '<?= base_url('/admin/miembros/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellido').value = data.apellido;
            document.getElementById('tipo_documento').value = data.tipo_documento || 'TI';
            document.getElementById('numero_documento').value = data.numero_documento;
            document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento;
            document.getElementById('telefono').value = data.telefono || '';
            document.getElementById('correo').value = data.correo || '';
            document.getElementById('nivel_id').value = data.nivel_id;
            document.getElementById('sede_id').value = data.sede_id || '';
            document.getElementById('rol_id').value = data.rol_id;
            
            // Cargar nuevos campos
            document.getElementById('categoria_id').value = data.categoria_id || '1';
            document.getElementById('division').value = data.division || '';
            document.getElementById('ctgc').value = data.ctgc || '';
            document.getElementById('peso').value = data.peso || '';
            document.getElementById('eps').value = data.eps || '';
            document.getElementById('rh').value = data.rh || '';
            document.getElementById('mostrar_en_web').checked = data.mostrar_en_web == 1;
            document.getElementById('descripcion_perfil').value = data.descripcion_perfil || '';
            document.getElementById('logros').value = data.logros || '';
            
            // Foto de perfil preview
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
                    document.getElementById('permiso_sedes').checked = permisos.sedes || false;
                    document.getElementById('permiso_registros').checked = permisos.registros || false;
                    document.getElementById('permiso_ascensos').checked = permisos.ascensos || false;
                    document.getElementById('permiso_calendario').checked = permisos.calendario || false;
                    document.getElementById('permiso_galeria').checked = permisos.galeria || false;
                    document.getElementById('permiso_reportes').checked = permisos.reportes || false;
                } catch(e) { }
            }
        }
    }
    
    function togglePermisos(rol_id) {
        const sec = document.getElementById('permisos_section');
        if (rol_id == 'Maestros' || rol_id == 'Profesores' || rol_id == 'Monitores') {
            sec.classList.remove('hidden');
        } else {
            sec.classList.add('hidden');
            // reset checkboxes
            document.querySelectorAll('#permisos_section input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    }
    
    function closeModal() {
        const modal = document.getElementById('member-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Bind edit button in details modal
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('detail-btn-edit').addEventListener('click', function() {
            if (activeMemberData) {
                const dataToEdit = {...activeMemberData};
                closeDetailModal();
                openModal('edit', dataToEdit);
            }
        });
    });

    // Helper functions
    function calculateAge(birthdayStr) {
        if (!birthdayStr) return 0;
        const birthday = new Date(birthdayStr);
        const today = new Date();
        let age = today.getFullYear() - birthday.getFullYear();
        const m = today.getMonth() - birthday.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }
        return age;
    }
    
    function formatLocalDate(dateStr) {
        if (!dateStr) return 'Sin fecha';
        const options = { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'UTC' };
        return new Date(dateStr).toLocaleDateString('es-ES', options);
    }

    // Funcionalidad de Búsqueda, Filtros y Gráficas
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput  = document.getElementById('member-search');
        const sedeSelect   = document.getElementById('filter-sede');
        const nivelSelect  = document.getElementById('filter-nivel');
        const rolSelect    = document.getElementById('filter-rol');
        const estadoSelect = document.getElementById('filter-estado');
        const resetBtn     = document.getElementById('btn-reset-filters');
        
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.querySelectorAll('.member-row');

        const filters = {
            search: '',
            sede: 'all',
            nivel: 'all',
            rol: 'all',
            estado: 'all'
        };

        const beltOrder = <?= json_encode(array_column($niveles_list, 'nombre')) ?>;

        // Paletas de Colores para Gráficas (Colores sólidos y visibles)
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
            'blanco':         { bg: '#f1f5f9', border: '#94a3b8', isPattern: false },
            'amarillo':       { bg: '#eab308', border: '#ca8a04', isPattern: false },
            'verde':          { bg: '#22c55e', border: '#16a34a', isPattern: false },
            'azul':           { bg: '#3b82f6', border: '#2563eb', isPattern: false },
            'rojo':           { bg: '#ef4444', border: '#dc2626', isPattern: false },
            'negro':          { bg: '#1e293b', border: '#475569', isPattern: false },
            'default':        { bg: '#64748b', border: '#475569', isPattern: false },
        };

        // Función para generar patrones de rayas diagonales para los cinturones con pinta
        function createStripePattern(baseColor, stripeColor) {
            const canvas = document.createElement('canvas');
            canvas.width = 12;
            canvas.height = 12;
            const ctx = canvas.getContext('2d');
            
            // Fondo
            ctx.fillStyle = baseColor;
            ctx.fillRect(0, 0, 12, 12);
            
            // Raya diagonal
            ctx.strokeStyle = stripeColor;
            ctx.lineWidth = 3.5;
            ctx.beginPath();
            ctx.moveTo(-2, 14);
            ctx.lineTo(14, -2);
            ctx.stroke();
            
            const tempCtx = document.createElement('canvas').getContext('2d');
            return tempCtx.createPattern(canvas, 'repeat');
        }

        const sedeColorPalette = [
            'rgba(37, 99, 235, 0.8)',
            'rgba(220, 38, 38, 0.8)',
            'rgba(250, 204, 21, 0.85)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(14, 165, 233, 0.8)',
        ];

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
        const tickColor = isDark ? '#94a3b8' : '#64748b';

        // Inicializar Gráficas
        const ctxCinturones = document.getElementById('chart-cinturones').getContext('2d');
        const ctxSedes      = document.getElementById('chart-sedes').getContext('2d');

        const cinturonChart = new Chart(ctxCinturones, {
            type: 'doughnut',
            data: { labels: [], datasets: [{ data: [], backgroundColor: [], borderColor: [], borderWidth: 2, hoverOffset: 8 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: tickColor, font: { size: 10, weight: '600' }, padding: 8, usePointStyle: true }
                    },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
                },
                cutout: '60%',
                animation: { animateScale: true, duration: 500 }
            }
        });

        const sedesChart = new Chart(ctxSedes, {
            type: 'bar',
            data: { labels: [], datasets: [{ label: 'Miembros', data: [], backgroundColor: sedeColorPalette, borderRadius: 8 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.raw} miembros` } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 10 } } },
                    y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10 }, stepSize: 1 }, beginAtZero: true }
                },
                animation: { duration: 500 }
            }
        });

        function applyFilters() {
            let visibleCount = 0;
            const visibleRows = [];

            rows.forEach(row => {
                if (row.id === 'no-results-row') return;

                const nombre = row.getAttribute('data-nombre') || '';
                const sede   = row.getAttribute('data-sede') || '';
                const nivel  = row.getAttribute('data-nivel') || '';
                const rol    = row.getAttribute('data-rol') || '';
                const estado = row.getAttribute('data-estado') || '';

                const matchSearch = !filters.search || nombre.includes(filters.search.toLowerCase());
                const matchSede   = filters.sede === 'all' || sede === filters.sede;
                const matchNivel  = filters.nivel === 'all' || nivel === filters.nivel;
                const matchRol    = filters.rol === 'all' || rol === filters.rol;
                const matchEstado = filters.estado === 'all' || estado === filters.estado;

                const visible = matchSearch && matchSede && matchNivel && matchRol && matchEstado;
                row.style.display = visible ? '' : 'none';

                if (visible) {
                    visibleCount++;
                    visibleRows.push(row);
                }
            });

            // Control de mensaje sin resultados
            let noResultsRow = document.getElementById('no-results-row');
            if (visibleCount === 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'no-results-row';
                    noResultsRow.innerHTML = `<td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No se encontraron miembros que coincidan con los filtros.</td>`;
                    tableBody.appendChild(noResultsRow);
                } else {
                    noResultsRow.style.display = '';
                }
            } else {
                if (noResultsRow) noResultsRow.style.display = 'none';
            }

            // Mostrar/Ocultar botón de Limpiar Filtros
            const isAnyFilterActive = filters.search || filters.sede !== 'all' || filters.nivel !== 'all' || filters.rol !== 'all' || filters.estado !== 'all';
            if (resetBtn) {
                if (isAnyFilterActive) {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }

            // Actualizar Gráficas
            updateCharts(visibleRows);
        }

        function updateCharts(visibleRows) {
            // — Cinturones —
            const cinturonMap = {};
            visibleRows.forEach(row => {
                const n = row.getAttribute('data-nivel') || 'Sin Asignar';
                cinturonMap[n] = (cinturonMap[n] || 0) + 1;
            });

            const cLabels = Object.keys(cinturonMap);
            cLabels.sort((a, b) => {
                let indexA = beltOrder.indexOf(a);
                let indexB = beltOrder.indexOf(b);
                if (indexA === -1) indexA = 999;
                if (indexB === -1) indexB = 999;
                return indexA - indexB;
            });

            const cData   = cLabels.map(l => cinturonMap[l]);
            const cBg     = cLabels.map(l => {
                const k = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
                const item = beltColors[k];
                if (item.isPattern) {
                    return createStripePattern(item.bg, item.stripe);
                }
                return item.bg;
            });
            const cBorder = cLabels.map(l => {
                const k = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
                return beltColors[k].border;
            });

            cinturonChart.data.labels            = cLabels;
            cinturonChart.data.datasets[0].data  = cData;
            cinturonChart.data.datasets[0].backgroundColor = cBg;
            cinturonChart.data.datasets[0].borderColor     = cBorder;
            cinturonChart.update();

            const chartCEmpty = document.getElementById('chart-cinturones-empty');
            if (chartCEmpty) {
                chartCEmpty.classList.toggle('hidden', cData.length > 0);
            }

            // — Sedes —
            const sedeMap = {};
            visibleRows.forEach(row => {
                const s = row.getAttribute('data-sede') || 'Sin Asignar';
                sedeMap[s] = (sedeMap[s] || 0) + 1;
            });

            const sLabels = Object.keys(sedeMap);
            const sData   = Object.values(sedeMap);

            sedesChart.data.labels           = sLabels;
            sedesChart.data.datasets[0].data = sData;
            sedesChart.data.datasets[0].backgroundColor = sedeColorPalette.slice(0, sLabels.length);
            sedesChart.update();

            const chartSEmpty = document.getElementById('chart-sedes-empty');
            if (chartSEmpty) {
                chartSEmpty.classList.toggle('hidden', sData.length > 0);
            }
        }

        // Listeners
        searchInput.addEventListener('input', (e) => {
            filters.search = e.target.value;
            applyFilters();
        });

        sedeSelect.addEventListener('change', (e) => {
            filters.sede = e.target.value;
            applyFilters();
        });

        nivelSelect.addEventListener('change', (e) => {
            filters.nivel = e.target.value;
            applyFilters();
        });

        rolSelect.addEventListener('change', (e) => {
            filters.rol = e.target.value;
            applyFilters();
        });

        estadoSelect.addEventListener('change', (e) => {
            filters.estado = e.target.value;
            applyFilters();
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                sedeSelect.value = 'all';
                nivelSelect.value = 'all';
                rolSelect.value = 'all';
                estadoSelect.value = 'all';

                filters.search = '';
                filters.sede = 'all';
                filters.nivel = 'all';
                filters.rol = 'all';
                filters.estado = 'all';

                applyFilters();
            });
        }

        // Cargar query inicial de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const urlSearch = urlParams.get('search');
        if (urlSearch) {
            searchInput.value = urlSearch;
            filters.search = urlSearch;
        }

        // Primera carga
        applyFilters();
    });
</script>

<!-- Modal de Configuración de Exportación -->
<div id="export-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl transition-colors duration-300">
        
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div id="export-modal-icon" class="h-10 w-10 rounded-xl flex items-center justify-center">
                    <span class="material-icons-outlined text-white text-xl"></span>
                </div>
                <div>
                    <h2 class="text-base font-display font-bold text-slate-900 dark:text-white">Configurar Exportación</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Selecciona las columnas que deseas incluir</p>
                </div>
            </div>
            <button onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <!-- Column Picker -->
        <div class="p-6 space-y-3">
            
            <!-- Quick selects -->
            <div class="flex gap-2 mb-4">
                <button type="button" onclick="selectAllExportCols()" class="text-xs font-bold text-tkd-blue hover:underline focus:outline-none">Seleccionar todo</button>
                <span class="text-slate-300 dark:text-slate-700">|</span>
                <button type="button" onclick="deselectAllExportCols()" class="text-xs font-bold text-slate-500 hover:underline focus:outline-none">Ninguno</button>
            </div>

            <!-- Grupo: Datos Personales -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-950/50 px-4 py-2 text-[10px] font-bold text-tkd-blue uppercase tracking-widest">
                    Datos Personales
                </div>
                <div class="grid grid-cols-2 gap-0 divide-y divide-slate-100 dark:divide-slate-800">
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer col-span-2">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="nombre" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Nombre completo</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="documento" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Documento</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-l border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="fecha_nacimiento">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Fecha nacimiento</span>
                    </label>
                </div>
            </div>

            <!-- Grupo: Contacto -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-950/50 px-4 py-2 text-[10px] font-bold text-tkd-blue uppercase tracking-widest">
                    Contacto
                </div>
                <div class="grid grid-cols-2 gap-0">
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="telefono" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Teléfono</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-l border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="correo" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Correo</span>
                    </label>
                </div>
            </div>

            <!-- Grupo: Taekwondo -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-950/50 px-4 py-2 text-[10px] font-bold text-tkd-blue uppercase tracking-widest">
                    Información Deportiva
                </div>
                <div class="grid grid-cols-2 gap-0 divide-x divide-slate-100 dark:divide-slate-800">
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="cinturon" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Cinturón</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="sede" checked>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Sede</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="rol">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Rol</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="categoria">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Categoría</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="division">
                        <span class="text-sm text-slate-700 dark:text-slate-300">División</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer border-t border-slate-100 dark:border-slate-800">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="ctgc">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Código CTGC</span>
                    </label>
                </div>
            </div>

            <!-- Grupo: Salud -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-950/50 px-4 py-2 text-[10px] font-bold text-tkd-blue uppercase tracking-widest">
                    Salud
                </div>
                <div class="grid grid-cols-3 gap-0 divide-x divide-slate-100 dark:divide-slate-800">
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="eps">
                        <span class="text-sm text-slate-700 dark:text-slate-300">EPS</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="rh">
                        <span class="text-sm text-slate-700 dark:text-slate-300">RH</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-950/30 cursor-pointer">
                        <input type="checkbox" class="export-col-check h-4 w-4 rounded text-tkd-blue border-slate-300 focus:ring-tkd-blue" value="peso">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Peso (kg)</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
            <p class="text-xs text-slate-400 dark:text-slate-500">Solo los miembros visibles (según filtros activos) serán exportados.</p>
            <button id="export-confirm-btn" onclick="confirmExport()" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold uppercase text-xs text-white transition hover:shadow-lg focus:outline-none shrink-0">
                <span class="material-icons-outlined text-sm" id="export-confirm-icon"></span>
                <span id="export-confirm-label">Exportar</span>
            </button>
        </div>
    </div>
</div>

<!-- Scripts para exportar a PDF y Excel y Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    let currentExportType = 'excel';

    // Definición de todas las columnas disponibles con su extractor
    const EXPORT_COLUMNS = {
        nombre:           { label: 'Nombre Completo',   get: (m) => (m.nombre + ' ' + m.apellido).trim() },
        documento:        { label: 'Documento',          get: (m) => (m.tipo_documento || '') + ' ' + (m.numero_documento || '') },
        fecha_nacimiento: { label: 'Fecha Nacimiento',  get: (m) => m.fecha_nacimiento || '' },
        telefono:         { label: 'Teléfono',           get: (m) => m.telefono || '' },
        correo:           { label: 'Correo',             get: (m) => m.correo || '' },
        cinturon:         { label: 'Cinturón',           get: (m) => m.nombre_nivel || '' },
        sede:             { label: 'Sede',               get: (m) => m.nombre_sede || '' },
        rol:              { label: 'Rol',                get: (m) => m.rol_id || '' },
        categoria:        { label: 'Categoría',          get: (m) => m.nombre_categoria || '' },
        division:         { label: 'División',           get: (m) => m.division || '' },
        ctgc:             { label: 'Código CTGC',        get: (m) => m.ctgc || '' },
        eps:              { label: 'EPS',                get: (m) => m.eps || '' },
        rh:               { label: 'Grupo Sanguíneo',   get: (m) => m.rh || '' },
        peso:             { label: 'Peso (kg)',          get: (m) => m.peso || '' },
    };

    // Datos completos de miembros desde PHP
    const ALL_MEMBERS_DATA = <?= json_encode(array_values($miembros)) ?>;

    function openExportModal(type) {
        currentExportType = type;
        const modal = document.getElementById('export-modal');
        const icon  = document.getElementById('export-modal-icon');
        const confirmBtn = document.getElementById('export-confirm-btn');
        const confirmIcon = document.getElementById('export-confirm-icon');
        const confirmLabel = document.getElementById('export-confirm-label');

        if (type === 'excel') {
            icon.className = 'h-10 w-10 rounded-xl flex items-center justify-center bg-green-600';
            icon.querySelector('span').textContent = 'table_view';
            confirmBtn.className = confirmBtn.className.replace(/bg-\S+/, 'bg-green-600') + ' bg-green-600 hover:bg-green-700';
            confirmBtn.style.background = '#16a34a';
            confirmIcon.textContent = 'table_view';
            confirmLabel.textContent = 'Exportar Excel';
        } else {
            icon.className = 'h-10 w-10 rounded-xl flex items-center justify-center bg-red-600';
            icon.querySelector('span').textContent = 'picture_as_pdf';
            confirmBtn.style.background = '#dc2626';
            confirmIcon.textContent = 'picture_as_pdf';
            confirmLabel.textContent = 'Exportar PDF';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeExportModal() {
        const modal = document.getElementById('export-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function selectAllExportCols() {
        document.querySelectorAll('.export-col-check').forEach(cb => cb.checked = true);
    }

    function deselectAllExportCols() {
        document.querySelectorAll('.export-col-check').forEach(cb => cb.checked = false);
    }

    function getSelectedColumns() {
        const selected = [];
        document.querySelectorAll('.export-col-check:checked').forEach(cb => selected.push(cb.value));
        return selected;
    }

    function getVisibleMemberIds() {
        // Get IDs of members currently visible in the table (respects active filters)
        const ids = new Set();
        document.querySelectorAll('tbody tr:not(#no-results-row)').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.member-checkbox');
                if (cb) ids.add(cb.value);
            }
        });
        return ids;
    }

    function buildExportData() {
        const cols = getSelectedColumns();
        if (cols.length === 0) {
            alert('Selecciona al menos una columna para exportar.');
            return null;
        }

        const visibleIds = getVisibleMemberIds();
        // Filter members to only visible ones
        const visibleMembers = ALL_MEMBERS_DATA.filter(m => visibleIds.has(String(m.id)));

        // Build header row
        const headers = cols.map(c => EXPORT_COLUMNS[c]?.label || c);

        // Build data rows
        const rows = visibleMembers.map(m => cols.map(c => {
            const val = EXPORT_COLUMNS[c]?.get(m);
            return val !== undefined && val !== null ? String(val) : '';
        }));

        return [headers, ...rows];
    }

    function confirmExport() {
        const data = buildExportData();
        if (!data) return;

        closeExportModal();

        if (currentExportType === 'excel') {
            doExportExcel(data);
        } else {
            doExportPDF(data);
        }
    }

    function doExportExcel(data) {
        const worksheet = XLSX.utils.aoa_to_sheet(data);

        // Auto column widths
        const colWidths = data[0].map((_, i) => ({
            wch: Math.max(...data.map(row => (row[i] || '').length), data[0][i].length) + 4
        }));
        worksheet['!cols'] = colWidths;

        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Miembros');
        XLSX.writeFile(workbook, 'Miembros_Jinhwan_' + new Date().toLocaleDateString('es-CO').replace(/\//g, '-') + '.xlsx');
    }

    function doExportPDF(data) {
        const { jsPDF } = window.jspdf;
        const orientation = data[0].length > 6 ? 'landscape' : 'portrait';
        const doc = new jsPDF(orientation);

        const today = new Date().toLocaleDateString('es-CO', { year: 'numeric', month: 'long', day: 'numeric' });
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(13);
        doc.text('Reporte de Miembros - Jinhwan Corporation', 14, 15);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(120);
        doc.text('Generado el ' + today, 14, 21);
        doc.setTextColor(0);

        doc.autoTable({
            head: [data[0]],
            body: data.slice(1),
            startY: 26,
            theme: 'grid',
            styles: { fontSize: 7.5, cellPadding: 3 },
            headStyles: { fillColor: [37, 99, 235], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 247, 250] },
            margin: { left: 14, right: 14 },
        });

        doc.save('Miembros_Jinhwan_' + new Date().toLocaleDateString('es-CO').replace(/\//g, '-') + '.pdf');
    }
</script>

<!-- Formulario oculto para borrado masivo -->
<form id="bulk-delete-form" action="<?= base_url('/admin/miembros/delete-bulk') ?>" method="POST" class="hidden">
    <div id="bulk-delete-ids"></div>
</form>

<script>
(function() {
    const selectAll   = document.getElementById('select-all-checkbox');
    const bulkBar     = document.getElementById('bulk-action-bar');
    const bulkCount   = document.getElementById('bulk-count');

    function getCheckboxes() {
        return [...document.querySelectorAll('.member-checkbox')];
    }

    function getChecked() {
        return getCheckboxes().filter(cb => cb.checked && cb.closest('tr').style.display !== 'none');
    }

    function updateBulkBar() {
        const checked = getChecked();
        if (checked.length > 0) {
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
            bulkCount.textContent = checked.length + (checked.length === 1 ? ' seleccionado' : ' seleccionados');
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
        }
        // Actualizar estado del checkbox de cabecera
        const visible = getCheckboxes().filter(cb => cb.closest('tr').style.display !== 'none');
        selectAll.indeterminate = checked.length > 0 && checked.length < visible.length;
        selectAll.checked       = visible.length > 0 && checked.length === visible.length;
    }

    // Seleccionar / Deseleccionar todos (solo filas visibles)
    selectAll.addEventListener('change', function() {
        const visible = getCheckboxes().filter(cb => cb.closest('tr').style.display !== 'none');
        visible.forEach(cb => {
            cb.checked = selectAll.checked;
            cb.closest('tr').classList.toggle('bg-red-50/30', selectAll.checked);
            cb.closest('tr').classList.toggle('dark:bg-red-950/10', selectAll.checked);
        });
        updateBulkBar();
    });

    // Delegación de eventos en el tbody para los checkboxes individuales
    document.querySelector('tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('member-checkbox')) {
            e.target.closest('tr').classList.toggle('bg-red-50/30', e.target.checked);
            e.target.closest('tr').classList.toggle('dark:bg-red-950/10', e.target.checked);
            updateBulkBar();
        }
    });

    // Re-evaluar selección cuando cambien los filtros/búsqueda
    const observer = new MutationObserver(updateBulkBar);
    document.querySelectorAll('tbody tr').forEach(tr => {
        observer.observe(tr, { attributes: true, attributeFilter: ['style'] });
    });

    window.clearSelection = function() {
        getCheckboxes().forEach(cb => {
            cb.checked = false;
            cb.closest('tr').classList.remove('bg-red-50/30', 'dark:bg-red-950/10');
        });
        selectAll.checked = false;
        selectAll.indeterminate = false;
        updateBulkBar();
    };

    window.confirmBulkDelete = function() {
        const checked = getChecked();
        if (checked.length === 0) return;

        const n = checked.length;
        const msg = `¿Estás seguro de que deseas eliminar ${n} miembro${n > 1 ? 's' : ''}?\n\nEsta acción no se puede deshacer.`;

        if (!confirm(msg)) return;

        // Rellenar el formulario oculto con los IDs
        const container = document.getElementById('bulk-delete-ids');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('bulk-delete-form').submit();
    };
})();
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

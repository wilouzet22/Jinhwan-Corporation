<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10 gap-4">
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Gestión de Miembros</h1>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-slate-400 text-sm">search</span>
                    </div>
                    <input type="text" id="member-search" placeholder="Buscar miembro..." 
                           class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-colors">
                </div>
                
                <!-- Export Buttons -->
                <button onclick="exportToExcel()" class="w-full sm:w-auto bg-green-600 text-white font-display font-bold uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all flex items-center justify-center gap-2 focus:outline-none shrink-0" title="Exportar a Excel">
                    <span class="material-icons-outlined">table_view</span>
                    <span class="hidden sm:inline">Excel</span>
                </button>
                <button onclick="exportToPDF()" class="w-full sm:w-auto bg-red-600 text-white font-display font-bold uppercase tracking-wider py-2.5 px-4 rounded-xl shadow-md hover:bg-red-700 hover:shadow-lg transition-all flex items-center justify-center gap-2 focus:outline-none shrink-0" title="Exportar a PDF">
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
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all">
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800/80 text-slate-600 dark:text-slate-300 flex items-center justify-center mr-3 font-bold transition-colors shrink-0">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-800 dark:text-white transition-colors"><?= htmlspecialchars($miembro['nombre']) . ' ' . htmlspecialchars($miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-2 py-1 rounded text-slate-500 dark:text-slate-400 mr-1 transition-colors"><?= htmlspecialchars($miembro['tipo_documento']) ?></span>
                                <?= htmlspecialchars($miembro['numero_documento']) ?>
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
                                <button onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3 transition-colors focus:outline-none" title="Editar">
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <form action="<?= base_url('/admin/miembros/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                                    <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors focus:outline-none" title="Borrar">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<!-- Modal -->
<div id="member-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between transition-colors">
            <h2 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white transition-colors"></h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form id="member-form" action="<?= base_url('/admin/miembros/create') ?>" method="POST" class="p-6">
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
                <div class="col-span-1 md:col-span-2">
                    <label for="rol_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Rol</label>
                    <select name="rol_id" id="rol_id" onchange="togglePermisos(this.value)" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <option value="<?= Roles::ESTUDIANTE ?>">Alumno (Estudiante)</option>
                        <option value="<?= Roles::MAESTRO ?>">Instructor (Maestro)</option>
                        <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                    </select>
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

<script>
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
        } else if (action === 'edit') {
            title.textContent = 'Editar Miembro';
            form.action = '<?= base_url('/admin/miembros/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellido').value = data.apellido;
            document.getElementById('tipo_documento').value = data.tipo_documento;
            document.getElementById('numero_documento').value = data.numero_documento;
            document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento;
            document.getElementById('telefono').value = data.telefono;
            document.getElementById('correo').value = data.correo;
            document.getElementById('nivel_id').value = data.nivel_id;
            document.getElementById('sede_id').value = data.sede_id; // Note: sede_id comes from join in controller/model
            document.getElementById('rol_id').value = data.rol_id;
            
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

    // Funcionalidad de Búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('member-search');
        const tableBody = document.querySelector('tbody');
        const rows = tableBody.querySelectorAll('tr');
        
        function filterTable(query) {
            const lowerQuery = query.toLowerCase();
            let visibleCount = 0;
            
            rows.forEach(row => {
                // If it's the "No results" row, skip it for the search logic
                if (row.id === 'no-results-row') return;

                const text = row.textContent.toLowerCase();
                if (text.includes(lowerQuery)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Handle "No results found" message
            let noResultsRow = document.getElementById('no-results-row');
            if (visibleCount === 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'no-results-row';
                    noResultsRow.innerHTML = `<td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No se encontraron miembros que coincidan con la búsqueda.</td>`;
                    tableBody.appendChild(noResultsRow);
                } else {
                    noResultsRow.style.display = '';
                }
            } else {
                if (noResultsRow) noResultsRow.style.display = 'none';
            }
        }
        
        searchInput.addEventListener('input', (e) => filterTable(e.target.value));
        
        // Comprobar si hay un parámetro 'search' en la URL (proveniente del dashboard)
        const urlParams = new URLSearchParams(window.location.search);
        const urlSearch = urlParams.get('search');
        if (urlSearch) {
            searchInput.value = urlSearch;
            filterTable(urlSearch);
        }
    });
</script>

<!-- Scripts para exportar a PDF y Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    function getTableDataForExport() {
        const rows = document.querySelectorAll('tbody tr:not(#no-results-row)');
        let data = [];
        // Header
        data.push(['Miembro', 'Documento', 'Cinturón', 'Sede', 'Rol', 'Contacto']);
        
        rows.forEach(row => {
            if(row.style.display !== 'none') {
                const cols = row.querySelectorAll('td');
                if(cols.length >= 6) {
                    // Extract text content carefully
                    let miembroText = cols[0].innerText.replace(/\n/g, ' ').trim();
                    let docText = cols[1].innerText.replace(/\n/g, ' ').trim();
                    let cinturonText = cols[2].innerText.replace(/\n/g, ' ').trim();
                    let sedeText = cols[3].innerText.replace(/\n/g, ' ').trim();
                    let rolText = cols[4].innerText.replace(/\n/g, ' ').trim();
                    let contactoText = cols[5].innerText.replace(/\n/g, ' - ').trim();
                    
                    data.push([miembroText, docText, cinturonText, sedeText, rolText, contactoText]);
                }
            }
        });
        return data;
    }

    function exportToExcel() {
        let data = getTableDataForExport();
        let worksheet = XLSX.utils.aoa_to_sheet(data);
        let workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Miembros");
        XLSX.writeFile(workbook, "Miembros_Jinhwan.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');
        
        let data = getTableDataForExport();
        let headers = [data[0]];
        let body = data.slice(1);
        
        doc.text("Reporte de Miembros - Jinhwan Corporation", 14, 15);
        
        doc.autoTable({
            head: headers,
            body: body,
            startY: 20,
            theme: 'grid',
            styles: { fontSize: 8 },
            headStyles: { fillColor: [37, 99, 235] } // tkd-blue
        });
        
        doc.save("Miembros_Jinhwan.pdf");
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

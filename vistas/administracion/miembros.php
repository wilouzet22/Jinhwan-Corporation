<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10">
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Gestión de Miembros</h1>
            <button onclick="openModal('add')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none">
                <span class="material-icons-outlined">person_add</span>
                Añadir Nuevo Miembro
            </button>
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
                    <select name="rol_id" id="rol_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <option value="<?= Roles::ESTUDIANTE ?>">Alumno (Estudiante)</option>
                        <option value="<?= Roles::MAESTRO ?>">Instructor (Maestro)</option>
                        <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                    </select>
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
        }
    }
    
    function closeModal() {
        const modal = document.getElementById('member-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h1 class="text-2xl font-bold mb-4 md:mb-0">Gestión de Miembros</h1>
            <button onclick="openModal('add')" class="bg-primary text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-primary/90 flex items-center gap-2">
                <span class="material-icons-outlined">person_add</span>
                Añadir Nuevo Miembro
            </button>
        </div>
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl border overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-700/50 text-text-light-secondary dark:text-dark-secondary">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Documento</th>
                        <th scope="col" class="px-6 py-3">Cinturón</th>
                        <th scope="col" class="px-6 py-3">Sede</th>
                        <th scope="col" class="px-6 py-3">Rol</th>
                        <th scope="col" class="px-6 py-3">Contacto</th>
                        <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-border-dark">
                    <?php foreach ($miembros as $miembro): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3 font-bold">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <?= htmlspecialchars($miembro['nombre']) . ' ' . htmlspecialchars($miembro['apellido']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-text-light-secondary dark:text-dark-secondary">
                                <span class="font-mono text-xs bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded"><?= htmlspecialchars($miembro['tipo_documento']) ?></span>
                                <?= htmlspecialchars($miembro['numero_documento']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-light-secondary dark:text-dark-secondary">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">place</span>
                                    <?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php switch($miembro['rol_id']): 
                                    case Roles::SUPERUSUARIO: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Superusuario</span>
                                    <?php break; case Roles::ADMINISTRADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">Admin</span>
                                    <?php break; case Roles::MAESTRO: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Maestro</span>
                                    <?php break; case Roles::CONTADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">Contador</span>
                                    <?php break; default: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Estudiante</span>
                                <?php endswitch; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-text-light-secondary dark:text-dark-secondary">
                                <div><?= htmlspecialchars($miembro['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3" title="Editar">
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <form action="/jinwha/admin/miembros/delete" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                                    <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Borrar">
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
<div id="member-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-border-light dark:border-border-dark">
            <h2 id="modal-title" class="text-xl font-bold"></h2>
        </div>
        <form id="member-form" action="/jinwha/admin/miembros/create" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Información Personal</h3>
                </div>
                
                <div>
                    <label for="nombre" class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                <div>
                    <label for="apellido" class="block text-sm font-medium mb-1">Apellido</label>
                    <input type="text" name="apellido" id="apellido" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium mb-1">Tipo Documento</label>
                    <select name="tipo_documento" id="tipo_documento" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <option value="TI">T.I</option>
                        <option value="CC">C.C</option>
                        <option value="CE">C.E</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div>
                    <label for="numero_documento" class="block text-sm font-medium mb-1">Numero Documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                
                <div>
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium mb-1">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>

                <!-- Contact Info -->
                <div class="col-span-1 md:col-span-2 mt-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Contacto</h3>
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium mb-1">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                <div>
                    <label for="correo" class="block text-sm font-medium mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>

                <!-- Academic Info -->
                <div class="col-span-1 md:col-span-2 mt-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Información Académica</h3>
                </div>

                <div>
                    <label for="nivel_id" class="block text-sm font-medium mb-1">Cinturón (Nivel)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <?php foreach($niveles_list as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="sede_id" class="block text-sm font-medium mb-1">Sede</label>
                    <select name="sede_id" id="sede_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <?php foreach($sedes_list as $sede): ?>
                            <option value="<?= $sede['id'] ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="rol_id" class="block text-sm font-medium mb-1">Rol</label>
                    <select name="rol_id" id="rol_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <option value="<?= Roles::ESTUDIANTE ?>">Estudiante</option>
                        <option value="<?= Roles::MAESTRO ?>">Maestro</option>
                        <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                        <option value="<?= Roles::CONTADOR ?>">Contador</option>
                        <option value="<?= Roles::SUPERUSUARIO ?>">Superusuario</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-border-light dark:border-border-dark">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 transition">Guardar</button>
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
            form.action = '/jinwha/admin/miembros/create';
            document.getElementById('id').value = '';
        } else if (action === 'edit') {
            title.textContent = 'Editar Miembro';
            form.action = '/jinwha/admin/miembros/update';
            
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

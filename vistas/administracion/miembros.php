<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden">
        <!-- Abstract Ambient Glows -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10">
            <h1 class="text-3xl font-display font-bold text-white uppercase tracking-tight">Gestión de Miembros</h1>
            <button onclick="openModal('add')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all flex items-center gap-2">
                <span class="material-icons-outlined">person_add</span>
                Añadir Nuevo Miembro
            </button>
        </div>

        <div class="glass-card rounded-2xl border border-slate-800/80 overflow-hidden shadow-2xl relative z-10">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs uppercase bg-slate-950/40 border-b border-slate-800 text-slate-400">
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
                    <tbody class="divide-y divide-slate-800/50">
                    <?php foreach ($miembros as $miembro): ?>
                        <tr class="hover:bg-slate-900/20 transition-all">
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-950/60 border border-slate-800/80 text-slate-300 flex items-center justify-center mr-3 font-bold">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-white"><?= htmlspecialchars($miembro['nombre']) . ' ' . htmlspecialchars($miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                <span class="font-mono text-[10px] bg-slate-900/60 border border-slate-800 px-2 py-1 rounded text-slate-400 mr-1"><?= htmlspecialchars($miembro['tipo_documento']) ?></span>
                                <?= htmlspecialchars($miembro['numero_documento']) ?>
                            </td>
                            <?php
                                $nivel = strtolower($miembro['nombre_nivel'] ?? '');
                                $beltClass = 'bg-slate-900/60 text-slate-400 border border-slate-800/80'; // Default
                                
                                if (str_contains($nivel, 'blanco')) {
                                    $beltClass = 'bg-white text-slate-900 border border-slate-200 shadow-sm';
                                } elseif (str_contains($nivel, 'amarillo')) {
                                    $beltClass = 'bg-yellow-950/60 text-yellow-400 border border-yellow-800/30';
                                } elseif (str_contains($nivel, 'verde')) {
                                    $beltClass = 'bg-green-950/60 text-green-400 border border-green-800/30';
                                } elseif (str_contains($nivel, 'azul')) {
                                    $beltClass = 'bg-blue-950/60 text-blue-400 border border-blue-800/30';
                                } elseif (str_contains($nivel, 'rojo')) {
                                    $beltClass = 'bg-red-950/60 text-red-400 border border-red-800/30';
                                } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                                    $beltClass = 'bg-slate-950 text-white border border-slate-800 shadow-md';
                                }
                            ?>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $beltClass ?>">
                                    <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                <div class="flex items-center gap-1 text-sm font-light">
                                    <span class="material-icons-outlined text-sm text-tkd-blue">place</span>
                                    <?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php switch($miembro['rol_id']): 
                                    case Roles::ADMINISTRADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-950/60 text-red-400 border border-red-800/30 shadow-sm">Administrador</span>
                                    <?php break; case Roles::MAESTRO: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-950/60 text-purple-400 border border-purple-800/30 shadow-sm">Instructor</span>
                                    <?php break; case Roles::ESTUDIANTE: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-950/60 text-emerald-400 border border-emerald-800/30 shadow-sm">Alumno</span>
                                    <?php break; default: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-900/60 text-slate-400 border border-slate-800/80">Invitado</span>
                                 <?php endswitch; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-light">
                                <div class="font-normal text-slate-300"><?= htmlspecialchars($miembro['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-400 hover:text-blue-300 mr-3 transition-colors" title="Editar">
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <form action="<?= base_url('/admin/miembros/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                                    <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Borrar">
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
<div id="member-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="glass-card border border-slate-800 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-white"></h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form id="member-form" action="<?= base_url('/admin/miembros/create') ?>" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-xs font-semibold text-tkd-blue uppercase tracking-wider mb-1">Información Personal</h3>
                </div>
                
                <div>
                    <label for="nombre" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                <div>
                    <label for="apellido" class="block text-xs font-bold text-slate-400 uppercase mb-2">Apellido</label>
                    <input type="text" name="apellido" id="apellido" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-xs font-bold text-slate-400 uppercase mb-2">Tipo Documento</label>
                    <select name="tipo_documento" id="tipo_documento" class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                        <option value="TI">T.I</option>
                        <option value="CC">C.C</option>
                        <option value="CE">C.E</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div>
                    <label for="numero_documento" class="block text-xs font-bold text-slate-400 uppercase mb-2">Numero Documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                
                <div>
                    <label for="fecha_nacimiento" class="block text-xs font-bold text-slate-400 uppercase mb-2">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                <div></div>

                <!-- Contact Info -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h3 class="text-xs font-semibold text-tkd-blue uppercase tracking-wider mb-1">Contacto</h3>
                </div>

                <div>
                    <label for="telefono" class="block text-xs font-bold text-slate-400 uppercase mb-2">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                <div>
                    <label for="correo" class="block text-xs font-bold text-slate-400 uppercase mb-2">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>

                <!-- Academic Info -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h3 class="text-xs font-semibold text-tkd-blue uppercase tracking-wider mb-1">Información Académica</h3>
                </div>

                <div>
                    <label for="nivel_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Cinturón (Nivel)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                        <?php foreach($niveles_list as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="sede_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Sede</label>
                    <select name="sede_id" id="sede_id" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                        <?php foreach($sedes_list as $sede): ?>
                            <option value="<?= $sede['id'] ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="rol_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Rol</label>
                    <select name="rol_id" id="rol_id" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                        <option value="<?= Roles::ESTUDIANTE ?>">Alumno (Estudiante)</option>
                        <option value="<?= Roles::MAESTRO ?>">Instructor (Maestro)</option>
                        <option value="<?= Roles::ADMINISTRADOR ?>">Administrador</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-slate-800">
                <button type="button" onclick="closeModal()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold uppercase text-xs rounded-xl transition">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-[0_0_15px_rgba(37,99,235,0.4)]">Guardar</button>
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

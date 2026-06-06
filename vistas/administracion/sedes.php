<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden">
        <!-- Abstract Ambient Glows -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

        <h1 class="text-3xl font-display font-bold text-white uppercase tracking-tight mb-8 relative z-10">Administración de Sedes</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 relative z-10">

            <?php foreach ($sedes as $sede): ?>
                <div class="glass-card rounded-2xl border border-slate-800/80 overflow-hidden shadow-xl group">
                    <div class="aspect-video relative overflow-hidden border-b border-slate-800/80">
                        <div class="absolute inset-0 bg-[#0b0f19]/35 group-hover:bg-transparent transition-colors z-10 pointer-events-none"></div>
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?= urlencode($sede['direccion']) ?>&output=embed" class="grayscale invert opacity-80 group-hover:grayscale-0 group-hover:invert-0 group-hover:opacity-100 transition-all duration-500"></iframe>
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-display font-bold text-white mb-3 group-hover:text-tkd-blue transition-colors"><?= htmlspecialchars($sede['nombre']) ?></h2>
                        <div class="flex items-center space-x-2 mt-2 text-slate-300">
                            <span class="material-icons-outlined text-base text-tkd-blue">location_on</span>
                            <p class="text-sm font-light"><?= htmlspecialchars($sede['direccion']) ?></p>
                        </div>
                        <div class="flex items-center space-x-2 mt-2 text-slate-300">
                            <span class="material-icons-outlined text-base text-tkd-blue">phone</span>
                            <p class="text-sm font-light"><?= htmlspecialchars($sede['telefono'] ?? 'N/A') ?></p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-800/50 flex justify-end space-x-3">
                            <button onclick='openModal("edit", <?= json_encode($sede) ?>)' class="font-bold text-blue-400 hover:text-blue-300 text-xs uppercase transition-colors">Editar</button>
                            <form action="<?= base_url('/admin/sedes/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Borrar esta sede?');">
                                <input type="hidden" name="id" value="<?= $sede['id'] ?>">
                                <button type="submit" class="font-bold text-red-500 hover:text-red-400 text-xs uppercase transition-colors">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div onclick="openModal('add')" class="bg-slate-950/20 border-2 border-dashed border-slate-800 rounded-2xl flex items-center justify-center min-h-[280px] hover:border-tkd-blue/50 hover:text-tkd-blue transition-colors cursor-pointer group">
                <div class="text-center text-slate-400 group-hover:text-tkd-blue transition-colors">
                    <div class="w-16 h-16 bg-slate-900/60 border border-slate-800/80 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:border-tkd-blue/30 group-hover:shadow-[0_0_15px_rgba(37,99,235,0.15)] transition-all">
                        <span class="material-icons-outlined text-3xl">add</span>
                    </div>
                    <p class="font-display font-semibold tracking-wide uppercase text-xs">Nueva Sede</p>
                </div>
            </div>
        </div>
    </main>

<!-- Modal -->
<div id="sede-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="glass-card border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <h2 id="modal-title" class="text-xl font-display font-bold text-white"></h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form id="sede-form" action="<?= base_url('/admin/sedes/create') ?>" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            <div class="space-y-6">
                <div>
                    <label for="nombre" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nombre de la Sede</label>
                    <input type="text" name="nombre" id="nombre" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                <div>
                    <label for="direccion" class="block text-xs font-bold text-slate-400 uppercase mb-2">Dirección</label>
                    <input type="text" name="direccion" id="direccion" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                </div>
                <div>
                    <label for="telefono" class="block text-xs font-bold text-slate-400 uppercase mb-2">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
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
        const modal = document.getElementById('sede-modal');
        const form = document.getElementById('sede-form');
        const title = document.getElementById('modal-title');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        form.reset();
        
        if (action === 'add') {
            title.textContent = 'Nueva Sede';
            form.action = '<?= base_url('/admin/sedes/create') ?>';
            document.getElementById('id').value = '';
        } else if (action === 'edit') {
            title.textContent = 'Editar Sede';
            form.action = '<?= base_url('/admin/sedes/update') ?>';
            
            document.getElementById('id').value = data.id;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('direccion').value = data.direccion;
            document.getElementById('telefono').value = data.telefono;
        }
    }
    
    function closeModal() {
        const modal = document.getElementById('sede-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

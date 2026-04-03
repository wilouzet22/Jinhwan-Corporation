<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

    <main class="flex-grow container mx-auto p-6 lg:p-8">
        <h1 class="text-2xl font-bold mb-6">Administración de Sedes</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <?php foreach ($sedes as $sede): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark overflow-hidden shadow-sm">
                    <div class="aspect-video">
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?= urlencode($sede['direccion']) ?>&output=embed"></iframe>
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-semibold"><?= htmlspecialchars($sede['nombre']) ?></h2>
                        <div class="flex items-center space-x-2 mt-2 text-text-light-secondary dark:text-dark-secondary">
                            <span class="material-icons-outlined text-base">location_on</span>
                            <p class="text-sm"><?= htmlspecialchars($sede['direccion']) ?></p>
                        </div>
                        <div class="flex items-center space-x-2 mt-2 text-text-light-secondary dark:text-dark-secondary">
                            <span class="material-icons-outlined text-base">phone</span>
                            <p class="text-sm"><?= htmlspecialchars($sede['telefono'] ?? 'N/A') ?></p>
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button onclick='openModal("edit", <?= json_encode($sede) ?>)' class="font-medium text-primary hover:underline">Editar</button>
                            <form action="/jinwha/admin/sedes/delete" method="POST" class="inline-block" onsubmit="return confirm('¿Borrar esta sede?');">
                                <input type="hidden" name="id" value="<?= $sede['id'] ?>">
                                <button type="submit" class="font-medium text-red-500 hover:underline">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div onclick="openModal('add')" class="bg-surface-light dark:bg-surface-dark rounded-xl border-2 border-dashed border-border-light dark:border-border-dark flex items-center justify-center min-h-[280px] hover:border-primary hover:text-primary dark:hover:border-primary transition-colors cursor-pointer">
                <div class="text-center text-text-light-secondary dark:text-dark-secondary">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons-outlined text-3xl">add</span>
                    </div>
                    <p class="font-semibold">Nueva Sede</p>
                </div>
            </div>
        </div>
    </main>

<!-- Modal -->
<div id="sede-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 id="modal-title" class="text-2xl font-bold mb-6"></h2>
        <form id="sede-form" action="/jinwha/admin/sedes/create" method="POST">
            <input type="hidden" name="id" id="id">
            <div class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre de la Sede</label>
                    <input type="text" name="nombre" id="nombre" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input type="text" name="direccion" id="direccion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-lg">Cancelar</button>
                <button type="submit" class="bg-primary text-white font-semibold py-2 px-4 rounded-lg">Guardar</button>
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
            form.action = '/jinwha/admin/sedes/create';
            document.getElementById('id').value = '';
        } else if (action === 'edit') {
            title.textContent = 'Editar Sede';
            form.action = '/jinwha/admin/sedes/update';
            
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

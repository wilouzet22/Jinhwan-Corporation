<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

    <main class="flex-grow container mx-auto p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Administración de Material de Estudio</h1>
            <button onclick="openModal('create_teoria')" class="bg-primary text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 hover:bg-blue-600 transition">
                <span class="material-icons-outlined">add</span>
                <span>Nueva Teoría</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($teorias as $teoria): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark overflow-hidden shadow-sm flex flex-col group">
                    <!-- Video Preview -->
                    <div class="aspect-video bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                        <?php if (!empty($teoria['url_video'])): ?>
                            <?php 
                                // Basic YouTube Embed conversion
                                $video_url = $teoria['url_video'];
                                if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
                                    $video_url = str_replace('watch?v=', 'embed/', $video_url);
                                } elseif (strpos($video_url, 'youtu.be/') !== false) {
                                    $video_url = str_replace('youtu.be/', 'youtube.com/embed/', $video_url);
                                }
                            ?>
                            <iframe class="w-full h-full" src="<?= htmlspecialchars($video_url) ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-text-light-secondary dark:text-dark-secondary">
                                <span class="material-icons-outlined text-4xl">movie</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                        </div>

                        <h3 class="text-lg font-bold mb-2 line-clamp-2"><?= htmlspecialchars($teoria['titulo']) ?></h3>
                        <p class="text-sm text-text-light-secondary dark:text-dark-secondary mb-4 line-clamp-3"><?= htmlspecialchars($teoria['descripcion']) ?></p>

                        <div class="mt-auto border-t border-border-light dark:border-border-dark pt-4 flex justify-end space-x-3">
                            <button onclick='openModal("update_teoria", <?= json_encode($teoria) ?>)' class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:text-primary flex items-center">
                                <span class="material-icons-outlined text-base mr-1">edit</span> Editar
                            </button>
                            <form method="POST" action="<?= base_url('/admin/ascensos/delete') ?>" onsubmit="return confirm('¿Eliminar este tema?');">
                                <input type="hidden" name="id" value="<?= $teoria['id'] ?>">
                                <button type="submit" class="text-sm font-medium text-text-light-secondary dark:text-dark-secondary hover:text-red-500 flex items-center">
                                    <span class="material-icons-outlined text-base mr-1">delete</span> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

<!-- Modal for Create/Edit Theory -->
<div id="theory-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-xl w-full max-w-lg transform transition-all">
        <div class="p-6 border-b border-border-light dark:border-border-dark flex justify-between items-center">
            <h3 id="modal-title" class="text-xl font-bold">Nuevo Tema</h3>
            <button onclick="closeModal()" class="text-text-light-secondary dark:text-dark-secondary hover:text-text-light-primary dark:hover:text-dark-primary">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <form id="theory-form" action="<?= base_url('/admin/ascensos/create') ?>" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="id">
            
            <div>
                <label class="block text-sm font-medium mb-1">Título</label>
                <input type="text" name="titulo" id="titulo" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 focus:ring-primary focus:border-primary">
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nivel (Cinturón)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 focus:ring-primary focus:border-primary">
                        <?php foreach($niveles as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">URL Video (YouTube)</label>
                <input type="url" name="url" id="url" placeholder="https://youtube.com/..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 focus:ring-primary focus:border-primary"></textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Inline script as quick fix for modal JS which likely depends on 'action' parameter or specific IDs
    // The original JS might need adjustments, but let's try to adapt the form submission dynamically
    function openModal(action, data = null) {
        const modal = document.getElementById('theory-modal');
        const form = document.getElementById('theory-form');
        const title = document.getElementById('modal-title');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Reset form
        form.reset();
        
        if (action === 'create_teoria') {
            title.textContent = 'Nuevo Tema';
            form.action = '<?= base_url('/admin/ascensos/create') ?>';
            document.getElementById('id').value = '';
        } else if (action === 'update_teoria') {
            title.textContent = 'Editar Tema';
            form.action = '<?= base_url('/admin/ascensos/update') ?>';
            
            // Fill data
            document.getElementById('id').value = data.id;
            document.getElementById('titulo').value = data.titulo;
            document.getElementById('descripcion').value = data.descripcion;
            document.getElementById('url').value = data.url_video;
            document.getElementById('nivel_id').value = data.nivel_id;
        }
    }
    
    function closeModal() {
        const modal = document.getElementById('theory-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

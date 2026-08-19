<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
        
        <div class="flex justify-between items-center mb-8 relative z-10">
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Administración de Material</h1>
            <button onclick="openModal('create_teoria')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md flex items-center space-x-2 hover:bg-blue-700 hover:shadow-lg transition-all focus:outline-none">
                <span class="material-icons-outlined text-sm">add</span>
                <span>Nueva Teoría</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 relative z-10">
            <?php foreach ($teorias as $teoria): ?>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md flex flex-col group hover:border-tkd-blue/40 dark:hover:border-tkd-blue/40 transition-all duration-300">
                    
                    <div class="aspect-video bg-slate-100 dark:bg-slate-950/60 relative overflow-hidden border-b border-slate-200 dark:border-slate-800 transition-colors">
                        <?php if (!empty($teoria['url_video'])): ?>
                            <?php 
                                
                                $video_url = $teoria['url_video'];
                                if (strpos($video_url, 'youtube.com/watch?v=') !== false) {
                                    $video_url = str_replace('watch?v=', 'embed/', $video_url);
                                } elseif (strpos($video_url, 'youtu.be/') !== false) {
                                    $video_url = str_replace('youtu.be/', 'youtube.com/embed/', $video_url);
                                }
                            ?>
                            <iframe class="w-full h-full" src="<?= htmlspecialchars($video_url) ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-slate-400 dark:text-slate-500">
                                <span class="material-icons-outlined text-4xl">movie</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-tkd-blue dark:bg-tkd-blue/10 border border-blue-200 dark:border-tkd-blue/30 transition-colors">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                        </div>

                        <h3 class="text-lg font-display font-bold text-slate-900 dark:text-white mb-2 line-clamp-2 group-hover:text-tkd-blue dark:group-hover:text-tkd-blue transition-colors"><?= htmlspecialchars($teoria['titulo']) ?></h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3 font-light transition-colors"><?= htmlspecialchars($teoria['descripcion']) ?></p>

                        <div class="mt-auto border-t border-slate-100 dark:border-slate-800/50 pt-4 flex justify-end space-x-3 transition-colors">
                            <button onclick='openModal("update_teoria", <?= json_encode($teoria) ?>)' class="text-xs font-bold uppercase text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 flex items-center transition-colors focus:outline-none">
                                <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                            </button>
                            <form method="POST" action="<?= base_url('/admin/ascensos/delete') ?>" onsubmit="return confirm('¿Eliminar este tema?');">
                                <input type="hidden" name="id" value="<?= $teoria['id'] ?>">
                                <button type="submit" class="text-xs font-bold uppercase text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 flex items-center transition-colors focus:outline-none">
                                    <span class="material-icons-outlined text-sm mr-1">delete</span> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

<div id="theory-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center transition-colors">
            <h3 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white transition-colors">Nuevo Tema</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <form id="theory-form" action="<?= base_url('/admin/ascensos/create') ?>" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="id">
            
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Título</label>
                <input type="text" name="titulo" id="titulo" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Nivel (Cinturón)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
                        <?php foreach($niveles as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">URL Video (YouTube)</label>
                <input type="url" name="url" id="url" placeholder="https://youtube.com/..." required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-tkd-blue focus:ring-1 focus:ring-tkd-blue transition-colors focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-200 dark:border-slate-800/80 transition-colors">
                <button type="button" onclick="closeModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(action, data = null) {
        const modal = document.getElementById('theory-modal');
        const form = document.getElementById('theory-form');
        const title = document.getElementById('modal-title');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        form.reset();
        
        if (action === 'create_teoria') {
            title.textContent = 'Nuevo Tema';
            form.action = '<?= base_url('/admin/ascensos/create') ?>';
            document.getElementById('id').value = '';
        } else if (action === 'update_teoria') {
            title.textContent = 'Editar Tema';
            form.action = '<?= base_url('/admin/ascensos/update') ?>';

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

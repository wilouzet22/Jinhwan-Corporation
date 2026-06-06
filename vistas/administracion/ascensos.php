<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden">
        <!-- Abstract Ambient Glows -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex justify-between items-center mb-8 relative z-10">
            <h1 class="text-3xl font-display font-bold text-white uppercase tracking-tight">Administración de Material</h1>
            <button onclick="openModal('create_teoria')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md flex items-center space-x-2 hover:bg-blue-700 hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all">
                <span class="material-icons-outlined text-sm">add</span>
                <span>Nueva Teoría</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 relative z-10">
            <?php foreach ($teorias as $teoria): ?>
                <div class="glass-card rounded-2xl border border-slate-800/80 overflow-hidden shadow-xl flex flex-col group hover:border-tkd-blue/40">
                    <!-- Video Preview -->
                    <div class="aspect-video bg-slate-950/60 relative overflow-hidden">
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
                            <div class="flex items-center justify-center h-full text-slate-500">
                                <span class="material-icons-outlined text-4xl">movie</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-tkd-blue/10 border border-tkd-blue/30 text-tkd-blue">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                        </div>

                        <h3 class="text-lg font-display font-bold text-white mb-2 line-clamp-2 group-hover:text-tkd-blue transition-colors"><?= htmlspecialchars($teoria['titulo']) ?></h3>
                        <p class="text-sm text-slate-400 mb-4 line-clamp-3 font-light"><?= htmlspecialchars($teoria['descripcion']) ?></p>

                        <div class="mt-auto border-t border-slate-800/50 pt-4 flex justify-end space-x-3">
                            <button onclick='openModal("update_teoria", <?= json_encode($teoria) ?>)' class="text-xs font-bold uppercase text-slate-400 hover:text-blue-400 flex items-center transition-colors">
                                <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                            </button>
                            <form method="POST" action="<?= base_url('/admin/ascensos/delete') ?>" onsubmit="return confirm('¿Eliminar este tema?');">
                                <input type="hidden" name="id" value="<?= $teoria['id'] ?>">
                                <button type="submit" class="text-xs font-bold uppercase text-slate-400 hover:text-red-400 flex items-center transition-colors">
                                    <span class="material-icons-outlined text-sm mr-1">delete</span> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>

<!-- Modal for Create/Edit Theory -->
<div id="theory-modal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="glass-card border border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <h3 id="modal-title" class="text-xl font-display font-bold text-white">Nuevo Tema</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <form id="theory-form" action="<?= base_url('/admin/ascensos/create') ?>" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="id">
            
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Título</label>
                <input type="text" name="titulo" id="titulo" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Nivel (Cinturón)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
                        <?php foreach($niveles as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">URL Video (YouTube)</label>
                <input type="url" name="url" id="url" placeholder="https://youtube.com/..." class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="w-full rounded-xl bg-slate-950/60 border border-slate-800 text-white p-3 focus:border-tkd-blue/60 focus:ring-1 focus:ring-tkd-blue/60 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-800/80">
                <button type="button" onclick="closeModal()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold uppercase text-xs rounded-xl transition">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-tkd-blue hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-[0_0_15px_rgba(37,99,235,0.4)]">Guardar</button>
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

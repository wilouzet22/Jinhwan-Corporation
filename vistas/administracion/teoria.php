<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Administración de Material</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Gestiona el material teórico visible para los estudiantes</p>
        </div>
        <button onclick="openModal('create_teoria')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md flex items-center gap-2 hover:bg-blue-700 hover:shadow-lg transition-all focus:outline-none shrink-0">
            <span class="material-icons-outlined text-sm">add</span>
            <span>Nueva Teoría</span>
        </button>
    </div>

    <!-- Tabs por tipo (igual que el panel del estudiante) -->
    <?php
        $tabs = [];
        foreach ($tipos as $tipo) {
            $tabs[$tipo['id']] = ['nombre' => $tipo['nombre'], 'items' => []];
        }
        foreach ($teorias as $t) {
            $tid = $t['tipo_id'] ?? 1;
            if (isset($tabs[$tid])) $tabs[$tid]['items'][] = $t;
        }

        // Iconos por tipo
        $iconos = [
            'Poomsae'             => 'self_improvement',
            'Técnicas'            => 'sports_martial_arts',
            'Vocabulario Coreano' => 'translate',
            'Código de Honor'     => 'shield',
        ];
    ?>

    <div class="relative z-10">
        <!-- Barra de tabs -->
        <div class="flex gap-1 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-1.5 mb-6 overflow-x-auto custom-scrollbar">
            <?php foreach ($tabs as $tid => $tab): ?>
                <?php $icon = $iconos[$tab['nombre']] ?? 'menu_book'; ?>
                <button
                    id="tab-btn-<?= $tid ?>"
                    onclick="switchTab(<?= $tid ?>)"
                    class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm whitespace-nowrap transition-all duration-200 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-white dark:hover:bg-slate-800"
                >
                    <span class="material-icons-outlined text-base"><?= $icon ?></span>
                    <?= htmlspecialchars($tab['nombre']) ?>
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                        <?= count($tab['items']) ?>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Paneles por tipo -->
        <?php foreach ($tabs as $tid => $tab): ?>
        <div id="panel-<?= $tid ?>" class="tab-panel hidden">

            <?php if (!empty($tab['items'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($tab['items'] as $teoria): ?>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md flex flex-col group hover:border-blue-400/40 dark:hover:border-blue-400/40 transition-all duration-300">

                    <!-- Miniatura video -->
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
                            <div class="flex items-center justify-center h-full text-slate-400 dark:text-slate-600">
                                <span class="material-icons-outlined text-5xl">movie</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 transition-colors">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"><?= htmlspecialchars($tab['nombre']) ?></span>
                        </div>

                        <h3 class="text-base font-display font-bold text-slate-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            <?= htmlspecialchars($teoria['titulo']) ?>
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3 font-light transition-colors flex-grow">
                            <?= htmlspecialchars($teoria['descripcion'] ?? '') ?>
                        </p>

                        <div class="mt-auto border-t border-slate-100 dark:border-slate-800/50 pt-4 flex justify-end gap-3 transition-colors">
                            <button onclick='openModal("update_teoria", <?= json_encode($teoria) ?>)' class="text-xs font-bold uppercase text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 flex items-center transition-colors focus:outline-none">
                                <span class="material-icons-outlined text-sm mr-1">edit</span> Editar
                            </button>
                            <form method="POST" action="<?= base_url('/admin/teoria/delete') ?>" onsubmit="return confirm('¿Eliminar este tema?');">
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

            <?php else: ?>
            <!-- Estado vacío por tipo -->
            <div class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-slate-900/40 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 transition-colors">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4 border border-slate-200 dark:border-slate-700">
                    <span class="material-icons-outlined text-3xl text-slate-400"><?= $iconos[$tab['nombre']] ?? 'menu_book' ?></span>
                </div>
                <h3 class="text-base font-bold text-slate-700 dark:text-slate-300 mb-1">Sin material en «<?= htmlspecialchars($tab['nombre']) ?>»</h3>
                <p class="text-sm text-slate-400 max-w-xs">Usa el botón <strong>«Nueva Teoría»</strong> y selecciona este tipo para agregar contenido.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Modal crear / editar -->
<div id="theory-modal" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center transition-colors">
            <h3 id="modal-title" class="text-xl font-display font-bold text-slate-900 dark:text-white transition-colors">Nuevo Tema</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>

        <form id="theory-form" action="<?= base_url('/admin/teoria/create') ?>" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="id">

            <!-- Tipo de Teoría -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Tipo de Contenido</label>
                <select name="tipo_id" id="tipo_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                    <?php foreach($tipos as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Título -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Título</label>
                <input type="text" name="titulo" id="titulo" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
            </div>

            <!-- Nivel (Cinturón) -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Nivel (Cinturón)</label>
                <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                    <?php foreach($niveles as $nivel): ?>
                        <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- URL Video -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">URL Video (YouTube) <span class="normal-case text-slate-400 font-normal">(opcional)</span></label>
                <input type="url" name="url" id="url" placeholder="https://youtube.com/..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
            </div>

            <!-- Descripción / Contenido -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 transition-colors">Descripción / Contenido</label>
                <textarea name="descripcion" id="descripcion" rows="4" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800/80 transition-colors">
                <button type="button" onclick="closeModal()" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Tabs ────────────────────────────────────────────────────────────
    const TABS = <?= json_encode(array_keys($tabs)) ?>;

    function switchTab(tid) {
        TABS.forEach(id => {
            const btn   = document.getElementById('tab-btn-' + id);
            const panel = document.getElementById('panel-' + id);
            const active = (id == tid);

            panel.classList.toggle('hidden', !active);
            btn.classList.toggle('bg-white',          active);
            btn.classList.toggle('dark:bg-slate-800', active);
            btn.classList.toggle('text-slate-900',    active);
            btn.classList.toggle('dark:text-white',   active);
            btn.classList.toggle('shadow-sm',         active);
            btn.classList.toggle('text-slate-500',    !active);
            btn.classList.toggle('dark:text-slate-400',!active);
        });
    }

    // Activar primera tab al cargar
    switchTab(TABS[0]);

    // ── Modal ────────────────────────────────────────────────────────────
    function openModal(action, data = null) {
        const modal = document.getElementById('theory-modal');
        const form  = document.getElementById('theory-form');
        const title = document.getElementById('modal-title');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();

        if (action === 'create_teoria') {
            title.textContent = 'Nuevo Tema';
            form.action = '<?= base_url('/admin/teoria/create') ?>';
            document.getElementById('id').value = '';
        } else if (action === 'update_teoria' && data) {
            title.textContent = 'Editar Tema';
            form.action = '<?= base_url('/admin/teoria/update') ?>';

            document.getElementById('id').value       = data.id;
            document.getElementById('titulo').value   = data.titulo;
            document.getElementById('descripcion').value = data.descripcion ?? '';
            document.getElementById('url').value      = data.url_video ?? '';
            document.getElementById('nivel_id').value = data.nivel_id;
            document.getElementById('tipo_id').value  = data.tipo_id;
        }
    }

    function closeModal() {
        const modal = document.getElementById('theory-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

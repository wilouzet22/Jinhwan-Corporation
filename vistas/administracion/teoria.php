<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Administración de Material</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Gestiona el material teórico y las categorías oficiales del club</p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <button onclick="openTipoModal()" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 font-display font-bold uppercase tracking-wider py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-2 text-xs transition-all focus:outline-none">
                <span class="material-icons-outlined text-base text-blue-500">category</span>
                <span>+ Nuevo Tipo</span>
            </button>
            <button onclick="openModal('create_teoria')" class="bg-tkd-blue text-white font-display font-bold uppercase tracking-wider py-2.5 px-5 rounded-xl shadow-md flex items-center gap-2 hover:bg-blue-700 hover:shadow-lg transition-all focus:outline-none text-xs">
                <span class="material-icons-outlined text-base">add</span>
                <span>Nueva Teoría</span>
            </button>
        </div>
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
                    class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm whitespace-nowrap shrink-0 transition-all duration-200 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-white dark:hover:bg-slate-800"
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

<!-- Modal crear / editar Teoría (Diseño compacto y adaptable) -->
<div id="theory-modal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] transition-all duration-300 animate-in fade-in zoom-in-95">
        
        <!-- Cabecera modal -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center transition-colors">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <span class="material-icons-outlined text-lg">auto_stories</span>
                </div>
                <h3 id="modal-title" class="text-lg font-display font-bold text-slate-900 dark:text-white transition-colors">Nuevo Tema</h3>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors focus:outline-none">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Formulario compacto -->
        <form id="theory-form" action="<?= base_url('/admin/teoria/create') ?>" method="POST" class="p-6 overflow-y-auto custom-scrollbar space-y-4">
            <input type="hidden" name="id" id="id">

            <!-- Fila 1: Tipo de Contenido + Nivel Cinturón -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Tipo de Teoría</label>
                        <button type="button" onclick="showNuevoTipoInput()" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-0.5">
                            <span class="material-icons-outlined text-xs">add</span> Nuevo Tipo
                        </button>
                    </div>
                    <select name="tipo_id" id="tipo_id" onchange="checkTipoSelect(this.value)" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <?php foreach($tipos as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                        <?php endforeach; ?>
                        <option value="new" class="font-bold text-blue-600">+ Ingresar otro tipo...</option>
                    </select>

                    <!-- Input para ingresar nuevo tipo -->
                    <div id="nuevo-tipo-container" class="hidden mt-2 p-2.5 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900/50">
                        <label class="block text-[10px] font-bold uppercase text-blue-700 dark:text-blue-300 mb-1">Nombre del nuevo tipo:</label>
                        <div class="flex gap-1.5">
                            <input type="text" name="nuevo_tipo_nombre" id="nuevo_tipo_nombre" placeholder="Ej. Defensa Personal..." class="flex-1 rounded-lg bg-white dark:bg-slate-900 border border-blue-300 dark:border-blue-800 text-slate-900 dark:text-white py-1.5 px-2.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                            <button type="button" onclick="hideNuevoTipoInput()" class="px-2 py-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs" title="Cancelar">✕</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Nivel (Cinturón)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <?php foreach($niveles as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Fila 2: Título + URL Video -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Título del Tema</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Ej. Taegeuk Il Jang (1)" required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Video (YouTube) <span class="normal-case text-slate-400 font-normal">(opcional)</span></label>
                    <input type="url" name="url" id="url" placeholder="https://youtube.com/watch?v=..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                </div>
            </div>

            <!-- Fila 3: Descripción / Contenido -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Descripción / Contenido Marcial</label>
                <textarea name="descripcion" id="descripcion" rows="3" placeholder="Ingresa la explicación teórica, secuencia técnica, órdenes o principios..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none"></textarea>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 flex justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800 transition-colors">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors focus:outline-none">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition hover:shadow-lg focus:outline-none">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal dedicado para Nuevo Tipo de Categoría -->
<div id="tipo-modal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transition-all duration-300 animate-in fade-in zoom-in-95">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <span class="material-icons-outlined text-lg">category</span>
                </div>
                <h3 class="text-lg font-display font-bold text-slate-900 dark:text-white">Nuevo Tipo de Teoría</h3>
            </div>
            <button onclick="closeTipoModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>

        <form action="<?= base_url('/admin/teoria/create-tipo') ?>" method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Nombre de la Categoría</label>
                <input type="text" name="nombre" placeholder="Ej. Defensa Personal, Kyorugi..." required class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Descripción (opcional)</label>
                <textarea name="descripcion" rows="2" placeholder="Breve descripción del propósito de esta categoría..." class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none"></textarea>
            </div>
            <div class="pt-3 flex justify-end gap-2.5 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeTipoModal()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl hover:bg-slate-200">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl">Crear Tipo</button>
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

            if (panel) panel.classList.toggle('hidden', !active);
            if (btn) {
                btn.classList.toggle('bg-white',          active);
                btn.classList.toggle('dark:bg-slate-800', active);
                btn.classList.toggle('text-slate-900',    active);
                btn.classList.toggle('dark:text-white',   active);
                btn.classList.toggle('shadow-sm',         active);
                btn.classList.toggle('text-slate-500',    !active);
                btn.classList.toggle('dark:text-slate-400',!active);
            }
        });
    }

    if (TABS.length > 0) {
        switchTab(TABS[0]);
    }

    // ── Modal de Teoría ──────────────────────────────────────────────────
    function openModal(action, data = null) {
        const modal = document.getElementById('theory-modal');
        const form  = document.getElementById('theory-form');
        const title = document.getElementById('modal-title');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();
        hideNuevoTipoInput();

        if (action === 'create_teoria') {
            title.textContent = 'Nuevo Tema';
            form.action = '<?= base_url('/admin/teoria/create') ?>';
            document.getElementById('id').value = '';
        } else if (action === 'update_teoria' && data) {
            title.textContent = 'Editar Tema';
            form.action = '<?= base_url('/admin/teoria/update') ?>';

            document.getElementById('id').value          = data.id;
            document.getElementById('titulo').value      = data.titulo;
            document.getElementById('descripcion').value = data.descripcion ?? '';
            document.getElementById('url').value         = data.url_video ?? '';
            document.getElementById('nivel_id').value    = data.nivel_id;
            document.getElementById('tipo_id').value     = data.tipo_id;
        }
    }

    function closeModal() {
        const modal = document.getElementById('theory-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function checkTipoSelect(val) {
        if (val === 'new') {
            showNuevoTipoInput();
        } else {
            hideNuevoTipoInput();
        }
    }

    function showNuevoTipoInput() {
        const container = document.getElementById('nuevo-tipo-container');
        const input = document.getElementById('nuevo_tipo_nombre');
        const select = document.getElementById('tipo_id');
        container.classList.remove('hidden');
        select.value = 'new';
        input.focus();
    }

    function hideNuevoTipoInput() {
        const container = document.getElementById('nuevo-tipo-container');
        const input = document.getElementById('nuevo_tipo_nombre');
        const select = document.getElementById('tipo_id');
        container.classList.add('hidden');
        input.value = '';
        if (select.value === 'new') {
            select.selectedIndex = 0;
        }
    }

    // ── Modal de Nuevo Tipo ──────────────────────────────────────────────
    function openTipoModal() {
        const modal = document.getElementById('tipo-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTipoModal() {
        const modal = document.getElementById('tipo-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Cerrar modales con tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
            closeTipoModal();
        }
    });
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

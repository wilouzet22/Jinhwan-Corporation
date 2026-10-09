<?php 

function getBeltColor($levelName) {
    $levelName = is_array($levelName) ? ($levelName['nombre'] ?? '') : $levelName;
    $levelName = strtolower($levelName);
    
    if (strpos($levelName, 'blanco') !== false) return 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-100';
    if (strpos($levelName, 'amarillo') !== false) return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-white';
    if (strpos($levelName, 'verde') !== false) return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-600 dark:text-white';
    if (strpos($levelName, 'azul') !== false) return 'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white';
    if (strpos($levelName, 'rojo') !== false) return 'bg-red-100 text-red-800 dark:bg-red-600 dark:text-white';
    if (strpos($levelName, 'negro') !== false) return 'bg-slate-900 text-white dark:bg-slate-950 dark:text-white border border-slate-700';
    return 'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white'; 
}

$current_page = 'estudio';
include __DIR__ . '/../layout/estudiante_cabecera.php'; 

// Iconos por categoría
$iconos = [
    'Poomsae'             => 'self_improvement',
    'Técnicas'            => 'sports_martial_arts',
    'Vocabulario Coreano' => 'translate',
    'Código de Honor'     => 'shield',
];
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-tkd-blue/10 dark:bg-tkd-blue/20 flex items-center justify-center text-tkd-blue">
                    <span class="material-icons-outlined text-2xl">menu_book</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Centro de Estudio Técnico y Marcial</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-0.5 text-sm transition-colors">Material oficial de preparación técnica y examen de grado Jinhwan</p>
                </div>
            </div>
        </div>

        <!-- Selector: Todo vs Mi Teoría (Favoritos) -->
        <div class="flex items-center gap-2">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 transition-colors">
                <button onclick="setFavoriteFilter('all')" id="btn-filter-all" class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all bg-tkd-blue text-white shadow-sm">
                    Todo el Material
                </button>
                <button onclick="setFavoriteFilter('favorites')" id="btn-filter-fav" class="px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center gap-1.5">
                    <span class="material-icons-outlined text-sm text-amber-500">star</span>
                    Mi Teoría
                </button>
            </div>
        </div>
    </div>

    <!-- Pestañas de Categorías Oficiales (Poomsae, Técnicas, Vocabulario, Código de Honor) -->
    <div class="flex gap-2 p-1.5 bg-slate-100 dark:bg-slate-900/80 rounded-2xl border border-slate-200 dark:border-slate-800/80 overflow-x-auto custom-scrollbar">
        <button
            onclick="setCategoryFilter('all')"
            id="cat-tab-all"
            class="cat-tab flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider whitespace-nowrap shrink-0 transition-all bg-white dark:bg-slate-800 text-tkd-blue dark:text-blue-400 shadow-sm border border-slate-200/60 dark:border-slate-700/60"
        >
            <span class="material-icons-outlined text-base">apps</span>
            Todas las Categorías
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                <?= count($teorias) ?>
            </span>
        </button>

        <?php foreach ($tipos as $tipo): 
            $catIcon = $iconos[$tipo['nombre']] ?? 'menu_book';
            $countCat = count(array_filter($teorias, fn($t) => ($t['tipo_id'] ?? 1) == $tipo['id']));
        ?>
        <button
            onclick="setCategoryFilter(<?= $tipo['id'] ?>)"
            id="cat-tab-<?= $tipo['id'] ?>"
            class="cat-tab flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider whitespace-nowrap shrink-0 transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-800/60"
        >
            <span class="material-icons-outlined text-base"><?= $catIcon ?></span>
            <?= htmlspecialchars($tipo['nombre']) ?>
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                <?= $countCat ?>
            </span>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- Contenedor de Tarjetas de Material Real -->
    <div id="teoria-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($teorias as $index => $teoria): 
            $beltClass = getBeltColor($teoria['nivel_nombre'] ?? '');
            $is_personal = in_array($teoria['id'], $mi_teoria_ids);
            $tipo_id = $teoria['tipo_id'] ?? 1;
            $catIcon = $iconos[$teoria['tipo_nombre'] ?? ''] ?? 'menu_book';
        ?>
            <div data-teoria-id="<?= $teoria['id'] ?>" data-tipo-id="<?= $tipo_id ?>" data-is-fav="<?= $is_personal ? '1' : '0' ?>" class="teoria-card bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-blue-400/50 dark:hover:border-blue-500/40 hover:shadow-lg transition-all duration-300 flex flex-col cursor-pointer group">
                
                <div class="p-6 flex-grow flex flex-col relative">
                    
                    <!-- Botón Favorito Estrella -->
                    <button onclick="event.stopPropagation(); toggleMiTeoria(<?= $teoria['id'] ?>)" class="absolute top-5 right-5 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-amber-500 transition-colors z-10 mi-teoria-toggle <?= $is_personal ? 'text-amber-500' : '' ?>" title="Guardar en Mi Teoría">
                        <span class="material-icons-outlined text-xl"><?= $is_personal ? 'star' : 'star_border' ?></span>
                    </button>

                    <div onclick='openAscensoModal(<?= json_encode($teoria) ?>)' class="flex-grow flex flex-col">
                        <!-- Etiquetas de Grado y Categoría -->
                        <div class="flex flex-wrap items-center gap-2 mb-3.5 pr-8">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors <?= $beltClass ?>">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700/80">
                                <span class="material-icons-outlined text-xs"><?= $catIcon ?></span>
                                <?= htmlspecialchars($teoria['tipo_nombre'] ?? 'Teoría') ?>
                            </span>
                        </div>
                        
                        <!-- Título -->
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2.5 group-hover:text-tkd-blue dark:group-hover:text-blue-400 transition-colors pr-6 leading-snug">
                            <?= htmlspecialchars($teoria['titulo']) ?>
                        </h3>
                        
                        <!-- Descripción -->
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-5 line-clamp-3 flex-grow leading-relaxed transition-colors">
                            <?= nl2br(htmlspecialchars($teoria['descripcion'] ?? '')) ?>
                        </div>

                        <!-- Pie de tarjeta -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-slate-500 mt-auto transition-colors">
                            <span class="flex items-center gap-1.5">
                                <span class="material-icons-outlined text-base text-slate-400">
                                    <?= !empty($teoria['url_video']) ? 'smart_display' : 'description' ?>
                                </span>
                                <?= !empty($teoria['url_video']) ? 'Con Video' : 'Lectura' ?>
                            </span>
                            <span class="group-hover:text-tkd-blue dark:group-hover:text-blue-400 transition-colors flex items-center gap-1">
                                Ver material <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Estado vacío: Sin contenido en categoría / búsqueda -->
    <div id="teoria-empty-state" class="<?= empty($teorias) ? '' : 'hidden' ?> text-center py-20 bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800/80 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500 transition-colors border border-slate-200 dark:border-slate-700">
            <span class="material-icons-outlined text-4xl">menu_book</span>
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2 transition-colors">Aún no hay material disponible</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto transition-colors">El administrador de la academia todavía no ha publicado material en esta sección. Cuando se publique contenido nuevo, aparecerá aquí automáticamente.</p>
    </div>

    <!-- Estado vacío específico: Mi Teoría sin favoritos -->
    <div id="mi-teoria-empty" class="hidden text-center py-20 bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="w-20 h-20 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-500 dark:text-amber-400 transition-colors border border-amber-200/50 dark:border-amber-900/40">
            <span class="material-icons-outlined text-4xl">star_outline</span>
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2 transition-colors">Tu lista de estudio está vacía</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto transition-colors">Guarda el material que quieras repasar con prioridad haciendo clic en la estrella de cada tarjeta.</p>
    </div>

</main>

<!-- Modal de detalle de teoría y video -->
<div id="ascenso-modal" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 z-50 hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-200 opacity-0">
    <div class="bg-white dark:bg-slate-900 w-full max-w-3xl h-fit max-h-[85vh] flex flex-col transform scale-95 transition-transform duration-200 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl overflow-hidden" id="modal-content">
        
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900 transition-colors">
            <div>
                <span id="modal-category" class="text-xs font-bold uppercase tracking-wider text-tkd-blue mb-1 block">Teoría</span>
                <h2 id="modal-title" class="text-xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Título de la Teoría</h2>
            </div>
            <button onclick="closeAscensoModal()" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none">
                <span class="material-icons-outlined text-2xl">close</span>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-grow custom-scrollbar space-y-6">
            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-800/50 transition-colors">
                <p id="modal-description" class="whitespace-pre-line"></p>
            </div>

            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2 transition-colors">
                    <span class="material-icons-outlined text-tkd-blue">play_circle</span>
                    Material Multimedia
                </h3>
                <div id="modal-resources" class="grid grid-cols-1 gap-4"></div>
            </div>
        </div>
    </div>
</div>

<script>
    let miTeoriaIds = <?= json_encode($mi_teoria_ids ?? []) ?>;
    let selectedCategory = 'all';
    let selectedFilter = 'all'; // 'all' o 'favorites'

    function filterCards() {
        const cards = document.querySelectorAll('.teoria-card');
        const emptyState = document.getElementById('teoria-empty-state');
        const emptyFavState = document.getElementById('mi-teoria-empty');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardTipo = card.dataset.tipoId;
            const cardId = parseInt(card.dataset.teoriaId);
            const isFav = miTeoriaIds.includes(cardId);

            const matchCat = (selectedCategory === 'all' || cardTipo === String(selectedCategory));
            const matchFilter = (selectedFilter === 'all' || isFav);

            if (matchCat && matchFilter) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            if (selectedFilter === 'favorites') {
                emptyFavState.classList.remove('hidden');
                emptyState.classList.add('hidden');
            } else {
                emptyState.classList.remove('hidden');
                emptyFavState.classList.add('hidden');
            }
        } else {
            emptyState.classList.add('hidden');
            emptyFavState.classList.add('hidden');
        }
    }

    function setCategoryFilter(catId) {
        selectedCategory = catId;
        document.querySelectorAll('.cat-tab').forEach(tab => {
            tab.className = 'cat-tab flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider whitespace-nowrap shrink-0 transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-white/60 dark:hover:bg-slate-800/60';
        });

        const activeTab = document.getElementById('cat-tab-' + catId);
        if (activeTab) {
            activeTab.className = 'cat-tab flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider whitespace-nowrap shrink-0 transition-all bg-white dark:bg-slate-800 text-tkd-blue dark:text-blue-400 shadow-sm border border-slate-200/60 dark:border-slate-700/60';
        }
        filterCards();
    }

    function setFavoriteFilter(filter) {
        selectedFilter = filter;
        const btnAll = document.getElementById('btn-filter-all');
        const btnFav = document.getElementById('btn-filter-fav');

        if (filter === 'all') {
            btnAll.className = 'px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all bg-tkd-blue text-white shadow-sm';
            btnFav.className = 'px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center gap-1.5';
        } else {
            btnFav.className = 'px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all bg-amber-500 text-white shadow-sm flex items-center gap-1.5';
            btnAll.className = 'px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
        }
        filterCards();
    }

    function toggleMiTeoria(teoriaId) {
        const btn = document.querySelector(`.teoria-card[data-teoria-id="${teoriaId}"] .mi-teoria-toggle`);
        const icon = btn ? btn.querySelector(".material-icons-outlined") : null;

        fetch("/jinwha/ascensos/toggle", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ teoriaId: teoriaId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                if (data.action === "added") {
                    if (!miTeoriaIds.includes(teoriaId)) miTeoriaIds.push(teoriaId);
                    if (btn) {
                        btn.classList.add("text-amber-500");
                        btn.classList.remove("text-slate-400");
                    }
                    if (icon) icon.textContent = "star";
                } else {
                    const index = miTeoriaIds.indexOf(teoriaId);
                    if (index > -1) miTeoriaIds.splice(index, 1);
                    if (btn) {
                        btn.classList.remove("text-amber-500");
                        btn.classList.add("text-slate-400");
                    }
                    if (icon) icon.textContent = "star_border";
                }
                filterCards();
            }
        });
    }

    // Modal Helpers
    function openAscensoModal(data) {
        const modal = document.getElementById("ascenso-modal");
        const modalContent = document.getElementById("modal-content");

        document.getElementById("modal-title").textContent = data.titulo;
        document.getElementById("modal-category").textContent = (data.tipo_nombre || 'Teoría') + ' • ' + (data.nivel_nombre || 'General');
        document.getElementById("modal-description").textContent = data.descripcion || '';

        const resourcesContainer = document.getElementById("modal-resources");
        resourcesContainer.innerHTML = "";

        if (data.url_video) {
            let videoUrl = data.url_video;
            let embedUrl = '';
            if (videoUrl.includes('youtube.com/watch?v=')) {
                embedUrl = videoUrl.replace('watch?v=', 'embed/').split('&')[0];
            } else if (videoUrl.includes('youtu.be/')) {
                embedUrl = videoUrl.replace('youtu.be/', 'youtube.com/embed/');
            }

            const videoCard = document.createElement("div");
            videoCard.className = "rounded-2xl p-4 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60";

            if (embedUrl) {
                videoCard.innerHTML = `
                    <div class="aspect-video rounded-xl overflow-hidden bg-black mb-3 shadow-md">
                        <iframe src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                    <a href="${videoUrl}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-tkd-blue dark:text-blue-400 hover:underline">
                        <span class="material-icons-outlined text-sm">open_in_new</span> Ver en YouTube
                    </a>
                `;
            } else {
                videoCard.innerHTML = `
                    <a href="${videoUrl}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-tkd-blue dark:text-blue-400 hover:underline">
                        <span class="material-icons-outlined">open_in_new</span> Abrir enlace de video
                    </a>
                `;
            }
            resourcesContainer.appendChild(videoCard);
        } else {
            resourcesContainer.innerHTML = '<p class="text-xs text-slate-400 italic">No hay video complementario para este tema.</p>';
        }

        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            modalContent.classList.remove("scale-95");
            modalContent.classList.add("scale-100");
        }, 10);
        document.body.style.overflow = "hidden";
    }

    function closeAscensoModal() {
        const modal = document.getElementById("ascenso-modal");
        const modalContent = document.getElementById("modal-content");

        modal.classList.add("opacity-0");
        modalContent.classList.remove("scale-100");
        modalContent.classList.add("scale-95");

        setTimeout(() => {
            modal.classList.add("hidden");
            const iframes = modal.querySelectorAll("iframe");
            iframes.forEach(i => i.src = i.src);
        }, 200);
        document.body.style.overflow = "";
    }

    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("ascenso-modal");
        if (modal) {
            modal.addEventListener("click", function(e) {
                if (e.target === this) closeAscensoModal();
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>

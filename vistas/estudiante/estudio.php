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
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Estudio Teórico</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Material de apoyo para todos los grados</p>
        </div>

        <div class="flex">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 transition-colors">
                <button onclick="switchTab('all')" id="tab-all-btn" class="px-4 py-2 rounded-md font-semibold text-sm transition-colors bg-tkd-blue text-white">
                    Teoría
                </button>
                <button onclick="switchTab('mine')" id="tab-mine-btn" class="px-4 py-2 rounded-md font-semibold text-sm transition-colors text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-800/50">
                    Mi Teoría
                </button>
            </div>
        </div>
    </div>

    <div id="teoria-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($teorias as $index => $teoria): 
            $beltClass = getBeltColor($teoria['nivel_nombre'] ?? '');
            $is_personal = in_array($teoria['id'], $mi_teoria_ids);
        ?>
            <div data-teoria-id="<?= $teoria['id'] ?>" class="teoria-card bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-600 hover:shadow-md transition-all duration-300 flex flex-col cursor-pointer group">
                
                <div class="p-5 flex-grow flex flex-col relative">
                    
                    <button onclick="event.stopPropagation(); toggleMiTeoria(<?= $teoria['id'] ?>)" class="absolute top-5 right-5 p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-yellow-500 transition-colors z-10 mi-teoria-toggle <?= $is_personal ? 'text-yellow-500' : '' ?>" title="Añadir a mi teoría">
                        <span class="material-icons-outlined text-xl"><?= $is_personal ? 'star' : 'star_border' ?></span>
                    </button>

                    <div onclick='openAscensoModal(<?= json_encode($teoria) ?>)' class="flex-grow flex flex-col pt-1">
                        <div class="mb-3">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded transition-colors <?= $beltClass ?>">
                                <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2 group-hover:text-tkd-blue dark:group-hover:text-blue-400 transition-colors pr-8">
                            <?= htmlspecialchars($teoria['titulo']) ?>
                        </h3>
                        
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-5 line-clamp-3 flex-grow leading-relaxed transition-colors">
                            <?= nl2br(htmlspecialchars($teoria['descripcion'])) ?>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-slate-500 mt-auto transition-colors">
                            <span class="flex items-center gap-1.5">
                                <span class="material-icons-outlined text-base">folder_open</span>
                                <?= count($teoria['recursos']) ?> Recursos
                            </span>
                            <span class="group-hover:text-tkd-blue dark:group-hover:text-blue-400 transition-colors flex items-center gap-1">
                                Ver más <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="mi-teoria-empty" class="hidden text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-dashed border-slate-300 dark:border-slate-800 transition-colors duration-300">
        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500 transition-colors">
            <span class="material-icons-outlined text-3xl">star_outline</span>
        </div>
        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-1 transition-colors">Tu lista está vacía</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm transition-colors">Marca con una estrella el material que desees guardar.</p>
    </div>

</main>

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
            
            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg border border-slate-200 dark:border-slate-800/50 transition-colors">
                <p id="modal-description"></p>
            </div>

            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2 transition-colors">
                    <span class="material-icons-outlined text-tkd-blue">play_circle</span>
                    Material Multimedia
                </h3>
                
                <div id="modal-resources" class="grid grid-cols-1 gap-4">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    const miTeoriaIds = <?= json_encode($mi_teoria_ids) ?>;
</script>
<script src="<?= asset('js/modules/estudiante-ascensos.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>

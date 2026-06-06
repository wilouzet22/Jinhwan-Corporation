<?php 
// Helper function for belt colors (same as before, but with dark mode classes)
function getBeltColor($levelName) {
    // Handling array or string just in case
    $levelName = is_array($levelName) ? ($levelName['nombre'] ?? '') : $levelName;
    
    $levelName = strtolower($levelName);
    if (strpos($levelName, 'blanco') !== false) return 'border-slate-500/50 bg-slate-800/40 text-slate-300';
    if (strpos($levelName, 'amarillo') !== false) return 'border-yellow-500/50 bg-yellow-950/20 text-yellow-400';
    if (strpos($levelName, 'verde') !== false) return 'border-green-500/50 bg-green-950/20 text-green-400';
    if (strpos($levelName, 'azul') !== false) return 'border-blue-600/50 bg-blue-950/20 text-blue-400';
    if (strpos($levelName, 'rojo') !== false) return 'border-red-600/50 bg-red-950/20 text-red-400';
    if (strpos($levelName, 'negro') !== false) return 'border-slate-700/80 bg-slate-950/60 text-white';
    return 'border-tkd-blue/50 bg-blue-950/20 text-blue-400'; // Default
}

include __DIR__ . '/../layout/sitio_cabecera.php'; 
?>

<!-- Page Header -->
<section class="bg-tkd-black text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-l from-tkd-red/20 to-transparent"></div>
    <!-- Decorative Glowing Orb -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] h-[350px] rounded-full bg-tkd-red/5 blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="text-xs font-bold text-tkd-red tracking-[0.25em] uppercase mb-3 block animate-fade-in-up">Material de Apoyo</span>
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up">
            Estudio <span class="text-tkd-red">Teórico</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up" style="animation-delay: 0.2s;">
            Todo el material teórico de todos los grados. Estudia con disciplina.
        </p>
    </div>
    <!-- Decorative Shape -->
    <div class="absolute bottom-0 left-0 w-full h-16 bg-[#0b0f19]" style="clip-path: polygon(0 0, 0 100%, 100% 100%);"></div>
</section>

<section class="py-12 bg-[#0b0f19] min-h-screen relative overflow-hidden">
    <!-- Ambient backgrounds -->
    <div class="absolute top-1/4 left-1/10 w-[40%] h-[40%] rounded-full bg-tkd-red/5 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/10 w-[40%] h-[40%] rounded-full bg-tkd-blue/5 blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Tabs Navigation -->
        <div class="flex justify-center mb-12">
            <div class="inline-flex p-1 bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800/80">
                <button onclick="switchTab('all')" id="tab-all-btn" class="px-8 py-3 rounded-xl font-display font-bold uppercase tracking-widest transition-all duration-300 bg-tkd-red text-white shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                    Teoría
                </button>
                <button onclick="switchTab('mine')" id="tab-mine-btn" class="px-8 py-3 rounded-xl font-display font-bold uppercase tracking-widest transition-all duration-300 text-slate-400 hover:text-white">
                    Mi Teoría
                </button>
            </div>
        </div>

        <div id="teoria-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($teorias as $index => $teoria): 
                $beltClass = getBeltColor($teoria['nivel_nombre'] ?? '');
                $is_personal = in_array($teoria['id'], $mi_teoria_ids);
            ?>
                <div data-teoria-id="<?= $teoria['id'] ?>" class="teoria-card glass-card rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col animate-fade-in-up cursor-pointer" style="animation-delay: <?= $index * 0.05 ?>s;">
                    <!-- Belt Indicator Top Bar -->
                    <div class="h-1.5 w-full <?= strpos($beltClass, 'bg-') !== false ? str_replace('text-', 'bg-', explode(' ', $beltClass)[0]) : 'bg-tkd-blue' ?>"></div>
                    
                    <div class="p-6 flex-grow flex flex-col relative">
                        <!-- My Theory Toggle (Absolute) -->
                        <button onclick="event.stopPropagation(); toggleMiTeoria(<?= $teoria['id'] ?>)" class="absolute top-4 right-4 p-2 rounded-full bg-slate-955/60 border border-slate-800 text-slate-450 hover:scale-110 hover:text-tkd-gold transition-all duration-200 z-10 mi-teoria-toggle <?= $is_personal ? 'text-tkd-gold' : 'text-slate-400' ?>" title="Añadir a mi teoría">
                            <span class="material-icons-outlined text-sm"><?= $is_personal ? 'star' : 'star_border' ?></span>
                        </button>

                        <div onclick='openAscensoModal(<?= json_encode($teoria) ?>)' class="flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border <?= $beltClass ?>">
                                    <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-display font-bold text-white mb-3 group-hover:text-tkd-red transition-colors tracking-wide">
                                <?= htmlspecialchars($teoria['titulo']) ?>
                            </h3>
                            
                            <div class="text-sm text-slate-400 mb-6 line-clamp-3 flex-grow font-light leading-relaxed">
                                <?= nl2br(htmlspecialchars($teoria['descripcion'])) ?>
                            </div>

                            <!-- Resources Count -->
                            <div class="border-t border-slate-800/80 pt-4 mt-auto flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <span class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-sm">folder_open</span>
                                    <?= count($teoria['recursos']) ?> Recursos
                                </span>
                                <span class="group-hover:text-tkd-red transition-colors flex items-center gap-1">
                                    Ver más <span class="material-icons-outlined text-sm">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State for My Theory -->
        <div id="mi-teoria-empty" class="hidden text-center py-20 animate-fade-in-up">
            <div class="w-24 h-24 bg-slate-900/60 border border-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-icons-outlined text-5xl text-slate-600">star_outline</span>
            </div>
            <h3 class="text-2xl font-display font-bold text-white mb-2 uppercase tracking-wide">Tu lista está vacía</h3>
            <p class="text-slate-400 max-w-md mx-auto font-light text-sm">Marca con una estrella el material teórico que desees guardar para estudiarlo más tarde.</p>
        </div>
    </div>
</section>

<!-- Ascenso Detail Modal -->
<div id="ascenso-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4 md:p-10 backdrop-blur-md transition-opacity duration-300 opacity-0">
    <div class="glass-panel w-full max-w-4xl h-fit max-h-[90vh] md:h-[85vh] overflow-hidden flex flex-col transform scale-95 transition-transform duration-300 border border-slate-800/80 rounded-3xl" id="modal-content">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-800/60 flex justify-between items-center bg-slate-950/40">
            <div>
                <span id="modal-category" class="text-xs font-bold uppercase tracking-widest text-tkd-blue mb-1 block">Teoría</span>
                <h2 id="modal-title" class="text-2xl md:text-3xl font-display font-bold text-white tracking-wide">Título de la Teoría</h2>
            </div>
            <button onclick="closeAscensoModal()" class="p-2 rounded-full hover:bg-slate-800/60 text-slate-400 hover:text-white transition-colors">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-grow space-y-8 custom-scrollbar">
            <!-- Description -->
            <div class="prose prose-invert max-w-none">
                <p id="modal-description" class="text-base text-slate-300 leading-relaxed font-light">
                    Descripción detallada...
                </p>
            </div>

            <!-- Resources Section -->
            <div>
                <h3 class="text-lg font-display font-bold text-white mb-6 flex items-center gap-2 border-b border-slate-800/80 pb-2 uppercase tracking-wider">
                    <span class="material-icons-outlined text-tkd-red">play_circle</span>
                    Material Multimedia
                </h3>
                
                <div id="modal-resources" class="grid grid-cols-1 gap-6">
                    <!-- Resources will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data passed from PHP
    const miTeoriaIds = <?= json_encode($mi_teoria_ids) ?>;
</script>
<script src="<?= asset('js/modules/estudiante-ascensos.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

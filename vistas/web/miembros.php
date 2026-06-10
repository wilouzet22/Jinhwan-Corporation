<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero Section -->
<section class="relative py-20 bg-[#0b0f19] overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0b0f19] via-[#0b0f19]/90 to-tkd-blue/15"></div>
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold text-white mb-4 uppercase tracking-tight">
            Nuestros <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red to-tkd-gold">Miembros</span>
        </h1>
        <div class="w-24 h-1.5 bg-gradient-to-r from-tkd-blue via-tkd-red to-tkd-gold mx-auto rounded-full mb-6 shadow-[0_0_10px_rgba(220,38,38,0.5)]"></div>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light">
            Conoce a quienes hacen posible Jinhwa Corporation.
        </p>
    </div>
</section>

<!-- Miembros Section -->
<section class="py-12 bg-[#0b0f19] relative overflow-hidden min-h-screen">
    <!-- Abstract Ambient Glows -->
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Filters -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="filter-btn active px-6 py-2 rounded-full border-2 border-tkd-red bg-tkd-red/20 text-white font-medium hover:bg-tkd-red hover:text-white transition-all" data-filter="all">Todos</button>
            <button class="filter-btn px-6 py-2 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 font-medium hover:bg-slate-700 hover:text-white transition-all" data-filter="Administracion">Administración</button>
            <button class="filter-btn px-6 py-2 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 font-medium hover:bg-slate-700 hover:text-white transition-all" data-filter="Maestros">Maestros</button>
            <button class="filter-btn px-6 py-2 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 font-medium hover:bg-slate-700 hover:text-white transition-all" data-filter="Profesores">Profesores</button>
            <button class="filter-btn px-6 py-2 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 font-medium hover:bg-slate-700 hover:text-white transition-all" data-filter="Monitores">Monitores</button>
            <button class="filter-btn px-6 py-2 rounded-full border border-slate-700 bg-slate-800/50 text-slate-300 font-medium hover:bg-slate-700 hover:text-white transition-all" data-filter="Deportistas">Deportistas</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="miembros-grid">
            <?php 
            // Función auxiliar para obtener el iframe limpio
            function getCleanEmbedUrl($url) {
                if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
                    $videoId = '';
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
                        $videoId = $match[1];
                    } elseif (preg_match('/youtube\.com\/shorts\/([^"&?\/\s]{11})/i', $url, $match)) {
                        $videoId = $match[1];
                    }
                    if ($videoId) {
                        return "https://www.youtube.com/embed/{$videoId}?controls=0&modestbranding=1&rel=0&showinfo=0&autoplay=1&mute=1&loop=1&playlist={$videoId}";
                    }
                } elseif (strpos($url, 'vimeo.com') !== false) {
                    if (preg_match('/vimeo\.com\/(\d+)/i', $url, $match)) {
                        $videoId = $match[1];
                        return "https://player.vimeo.com/video/{$videoId}?background=1&autoplay=1&loop=1&byline=0&title=0";
                    }
                }
                return null;
            }
            ?>
            <?php if(!empty($miembros)): ?>
                <?php foreach($miembros as $m): ?>
                    <div class="miembro-card glass-card rounded-2xl overflow-hidden border border-slate-700 flex flex-col xl:flex-row shadow-2xl transition-all duration-300" data-category="<?= htmlspecialchars($m['rol_id']) ?>">
                        <!-- Left Side: Multimedia -->
                        <div class="xl:w-1/2 p-4 bg-black/20 flex items-center justify-center min-h-[400px]">
                            <?php 
                                $embed_url = !empty($m['instagram_url']) ? getCleanEmbedUrl($m['instagram_url']) : null;
                                if($embed_url): 
                            ?>
                                <iframe src="<?= htmlspecialchars($embed_url) ?>" class="w-full h-full min-h-[500px] border-0 rounded-xl shadow-lg pointer-events-auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            <?php else: ?>
                                <div class="text-slate-500 text-center p-8 flex flex-col items-center">
                                    <span class="material-icons-outlined text-4xl mb-2">person</span>
                                    <p class="text-sm">Sin multimedia</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Side: Info -->
                        <div class="xl:w-1/2 p-8 flex flex-col justify-center space-y-4">
                            <div>
                                <h3 class="text-3xl font-display font-bold text-white mb-2"><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></h3>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-tkd-blue/10 border border-tkd-blue/30 text-tkd-blue backdrop-blur-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-tkd-blue animate-pulse"></span>
                                    <?= htmlspecialchars($m['rol_id']) ?>
                                </span>
                            </div>
                            
                            <?php if(!empty($m['descripcion_perfil'])): ?>
                            <div class="pt-4 border-t border-slate-800/80 prose prose-invert prose-sm">
                                <p class="text-slate-300 font-light leading-relaxed whitespace-pre-line"><?= htmlspecialchars($m['descripcion_perfil']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 text-slate-500">
                    <span class="material-icons-outlined text-5xl mb-4 text-slate-700">group_off</span>
                    <h3 class="text-xl font-medium text-slate-400">No hay miembros públicos disponibles aún.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Instagram Embed Script -->
<script async src="//www.instagram.com/embed.js"></script>

<!-- Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.miembro-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => {
                b.classList.remove('active', 'border-2', 'border-tkd-red', 'bg-tkd-red/20', 'text-white');
                b.classList.add('border', 'border-slate-700', 'bg-slate-800/50', 'text-slate-300');
            });
            btn.classList.add('active', 'border-2', 'border-tkd-red', 'bg-tkd-red/20', 'text-white');
            btn.classList.remove('border', 'border-slate-700', 'bg-slate-800/50', 'text-slate-300');

            const filterValue = btn.getAttribute('data-filter');

            cards.forEach(card => {
                if (filterValue === 'all') {
                    card.style.display = 'flex';
                } else {
                    if (card.getAttribute('data-category') === filterValue) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

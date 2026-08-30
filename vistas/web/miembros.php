<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="relative py-20 overflow-hidden transition-colors duration-300">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-100 dark:from-[#0b0f19] via-slate-100/90 dark:via-[#0b0f19]/90 to-blue-600/10"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-tight transition-colors">
            Nuestros <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-amber-500">Miembros</span>
        </h1>
        <div class="w-24 h-1.5 bg-gradient-to-r from-blue-600 via-rose-600 to-amber-500 mx-auto rounded-full mb-6"></div>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-light transition-colors">
            Conoce a quienes hacen posible Jinhwan Corporation.
        </p>
    </div>
</section>

<section class="py-12 relative overflow-hidden min-h-screen transition-colors duration-300">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 60%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Filter Buttons: Todos / Maestros / Profesores / Deportistas -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <button class="filter-btn active px-6 py-2.5 rounded-full font-display font-bold text-sm uppercase tracking-wider border-2 border-rose-600 bg-rose-600 text-white transition-all" data-filter="all">
                <span class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base">group</span>
                    Todos
                </span>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-display font-bold text-sm uppercase tracking-wider border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-blue-600 hover:bg-blue-600 hover:text-white transition-all" data-filter="Maestros">
                <span class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base">school</span>
                    Maestros
                </span>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-display font-bold text-sm uppercase tracking-wider border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-amber-500 hover:bg-amber-500 hover:text-white transition-all" data-filter="Profesores">
                <span class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base">person_outline</span>
                    Profesores
                </span>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-display font-bold text-sm uppercase tracking-wider border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-emerald-500 hover:bg-emerald-500 hover:text-white transition-all" data-filter="Deportistas">
                <span class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base">sports_martial_arts</span>
                    Deportistas
                </span>
            </button>
        </div>

        <!-- Member Cards Grid (PHP rendered, filtered by JS) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="miembros-grid">
            <?php
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
                <?php foreach($miembros as $m):
                    // Skip administrators — they don't appear on the public page
                    $rol = strtolower($m['rol_id'] ?? '');
                    if (str_contains($rol, 'admin') || str_contains($rol, 'administr')) continue;
                ?>
                    <?php $embed_url = !empty($m['instagram_url']) ? getCleanEmbedUrl($m['instagram_url']) : null; ?>
                    <div class="miembro-card bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden flex flex-col <?= $embed_url ? 'xl:flex-row' : '' ?> shadow-md hover:shadow-xl transition-all duration-300 hover:border-rose-500/40 dark:hover:border-rose-500/30" data-category="<?= htmlspecialchars($m['rol_id']) ?>">
                        <?php if($embed_url): ?>
                        <div class="xl:w-1/2 p-4 bg-slate-100 dark:bg-black/20 flex items-center justify-center min-h-[400px] transition-colors">
                            <iframe src="<?= htmlspecialchars($embed_url) ?>" class="w-full h-full min-h-[500px] border-0 rounded-xl shadow-lg pointer-events-auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <?php endif; ?>

                        <div class="<?= $embed_url ? 'xl:w-1/2' : 'w-full' ?> p-8 flex flex-col justify-center space-y-4">
                            <div class="flex items-center gap-5">
                                <?php if (!empty($m['foto_perfil'])): ?>
                                    <img src="<?= base_url('/public/uploads/perfiles/' . $m['foto_perfil']) ?>" class="w-20 h-20 rounded-full object-cover shadow-md border-2 border-slate-200 dark:border-slate-700 shrink-0">
                                <?php else: ?>
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-rose-600 flex items-center justify-center text-white font-display font-bold text-3xl shadow-md shrink-0">
                                        <?= strtoupper(substr($m['nombre'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-2 transition-colors"><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></h3>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-600/10 border border-rose-200 dark:border-rose-500/30 text-rose-700 dark:text-rose-400 uppercase tracking-wider transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        <?= htmlspecialchars($m['rol_id']) ?>
                                    </span>
                                </div>
                            </div>

                            <?php if(!empty($m['descripcion_perfil'])): ?>
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800/80 transition-colors">
                                <p class="text-slate-600 dark:text-slate-300 font-light leading-relaxed whitespace-pre-line transition-colors"><?= htmlspecialchars($m['descripcion_perfil']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 text-slate-500">
                    <span class="material-icons-outlined text-5xl mb-4 text-slate-400 dark:text-slate-700 block">group_off</span>
                    <h3 class="text-xl font-medium text-slate-500 dark:text-slate-400 transition-colors">No hay miembros públicos disponibles aún.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script async src="//www.instagram.com/embed.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.miembro-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset all buttons
            filterBtns.forEach(b => {
                b.classList.remove('active', 'border-2', 'border-rose-600', 'bg-rose-600', 'text-white');
                b.classList.add('border', 'border-slate-300', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-200');
            });

            // Activate clicked button
            btn.classList.add('active', 'border-2', 'border-rose-600', 'bg-rose-600', 'text-white');
            btn.classList.remove('border-slate-300', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-200');

            const filterValue = btn.getAttribute('data-filter');

            cards.forEach(card => {
                const cat = (card.getAttribute('data-category') || '').toLowerCase();
                if (filterValue === 'all') {
                    card.style.display = '';
                } else if (filterValue === 'Deportistas') {
                    card.style.display = (cat.includes('deport') || cat.includes('estudiant') || cat.includes('alumn')) ? '' : 'none';
                } else if (filterValue === 'Profesores') {
                    card.style.display = (cat.includes('profesor') || cat.includes('monitor')) ? '' : 'none';
                } else if (filterValue === 'Maestros') {
                    card.style.display = (cat.includes('maestr')) ? '' : 'none';
                } else {
                    card.style.display = cat === filterValue.toLowerCase() ? '' : 'none';
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

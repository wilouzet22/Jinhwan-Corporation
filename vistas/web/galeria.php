<?php
include __DIR__ . '/../layout/sitio_cabecera.php';

$totalPubs = !empty($publicaciones) ? count($publicaciones) : 0;
?>

<section class="py-16 md:py-24 relative min-h-screen overflow-hidden transition-colors duration-300" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]/80"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Encabezado Principal -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-amber-500/10 border border-pink-500/20 text-pink-600 dark:text-pink-400 text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.13-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                <span>Comunidad Oficial</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wider transition-colors">
                Galería
            </h1>
            <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full mb-5"></div>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto mb-6">
                Explora nuestros momentos destacados, ceremonias de ascenso, entrenamientos y torneos más recientes a través de nuestra galería oficial de Instagram.
            </p>
            <a href="https://www.instagram.com/corporacion_jinhwan/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 hover:from-purple-500 hover:via-pink-500 hover:to-amber-400 text-white font-semibold text-xs shadow-md hover:shadow-lg hover:scale-105 transition-all">
                <span>Seguir a @corporacion_jinhwan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        <!-- Cuadrícula de Publicaciones de Instagram -->
        <div>
            <?php if (!empty($publicaciones)): ?>
                <div class="<?= $totalPubs === 1 ? 'max-w-md mx-auto' : ($totalPubs === 2 ? 'max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 justify-items-center' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center') ?>">
                    <?php foreach ($publicaciones as $pub): ?>
                        <div class="w-full bg-white dark:bg-slate-900/90 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-xl overflow-hidden flex flex-col transition-all duration-300">
                            <div class="flex-1 w-full flex justify-center bg-slate-50 dark:bg-slate-950/60 pt-4 px-3 sm:px-4 overflow-hidden">
                                <blockquote class="instagram-media w-full m-0" data-instgrm-permalink="<?= htmlspecialchars($pub['url']) ?>" data-instgrm-version="14" style="max-width: 100%; min-width: 280px; width: calc(100% - 2px);"></blockquote>
                            </div>
                            <?php if (!empty($pub['descripcion'])): ?>
                                <div class="p-5 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                                    <p class="text-slate-700 dark:text-slate-300 leading-relaxed text-xs sm:text-sm">
                                        <?= nl2br(htmlspecialchars($pub['descripcion'])) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="max-w-md mx-auto text-center p-10 bg-white/60 dark:bg-slate-900/60 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-purple-500/10 via-pink-500/10 to-amber-500/10 border border-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.13-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <p class="text-slate-900 dark:text-white font-bold text-base mb-1">Descubre más contenido en Instagram</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-5 leading-relaxed">
                        Visita nuestra cuenta oficial para ver las últimas fotos de entrenamientos, ascensos de grado y transmisiones de competencias.
                    </p>
                    <a href="https://www.instagram.com/corporacion_jinhwan/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-bold text-pink-600 dark:text-pink-400 hover:underline">
                        <span>Ir a @corporacion_jinhwan</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script async src="//www.instagram.com/embed.js"></script>
<script>
    // Asegurar que el embed de Instagram se procese correctamente tras cargar el DOM
    document.addEventListener('DOMContentLoaded', function() {
        if (window.instgrm) {
            window.instgrm.Embeds.process();
        } else {
            setTimeout(function() {
                if (window.instgrm) window.instgrm.Embeds.process();
            }, 1000);
        }
    });
</script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

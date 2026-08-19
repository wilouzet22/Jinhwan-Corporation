<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="py-20 relative min-h-screen overflow-hidden transition-colors duration-300" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase text-center tracking-wider transition-colors">
            Galería
        </h1>
        <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full mb-8"></div>
        <p class="text-center text-slate-600 dark:text-slate-400 mb-16 max-w-2xl mx-auto">Explora nuestros momentos destacados y actividades más recientes a través de nuestra galería de Instagram.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($publicaciones)): ?>
                <div class="col-span-full text-center text-slate-500 dark:text-slate-400 py-12">
                    Aún no hay publicaciones en la galería.
                </div>
            <?php else: ?>
                <?php foreach ($publicaciones as $pub): ?>
                     <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-xl overflow-hidden flex flex-col transition-all duration-300">
                        <div class="flex-1 w-full flex justify-center bg-slate-50 dark:bg-slate-950/50 pt-4 px-4 overflow-hidden">
                            <blockquote class="instagram-media w-full m-0" data-instgrm-permalink="<?= htmlspecialchars($pub['url']) ?>" data-instgrm-version="14" style="max-width: 100%; min-width: 320px; width: calc(100% - 2px);"></blockquote>
                        </div>
                        <?php if (!empty($pub['descripcion'])): ?>
                            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                                <p class="text-slate-700 dark:text-slate-300 leading-relaxed text-sm">
                                    <?= nl2br(htmlspecialchars($pub['descripcion'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                     </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script async src="//www.instagram.com/embed.js"></script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Page Header -->
<section class="text-slate-900 dark:text-white py-20 relative overflow-hidden transition-colors duration-300" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-tkd-blue/10 dark:from-tkd-blue/15 to-transparent"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up transition-colors">
            Nuestras <span class="text-tkd-blue">Sedes</span>
        </h1>
        <div class="w-24 h-1 bg-gradient-to-r from-tkd-blue to-tkd-red mx-auto rounded-full mb-6"></div>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up transition-colors" style="animation-delay: 0.2s;">
            Encuentra el dojang más cercano y comienza tu camino hacia la excelencia.
        </p>
    </div>
</section>

<section class="py-16 relative min-h-[60vh] overflow-hidden transition-colors duration-300" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 60%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 60%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($sedes as $index => $cede): ?>
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl overflow-hidden group shadow-sm hover:shadow-lg animate-fade-in-up transition-all duration-300" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <!-- Map Container -->
                    <div class="aspect-video relative overflow-hidden border-b border-slate-200 dark:border-slate-800/80 transition-colors">
                        <div class="absolute inset-0 bg-slate-100/30 dark:bg-[#0b0f19]/35 group-hover:bg-transparent transition-colors z-10 pointer-events-none"></div>
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?= urlencode($cede['direccion']) ?>&output=embed" class="grayscale dark:invert opacity-80 group-hover:grayscale-0 group-hover:dark:invert-0 group-hover:opacity-100 transition-all duration-500"></iframe>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-6 transform -translate-y-1/2 w-12 h-12 bg-tkd-red rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-all duration-300">
                            <span class="material-icons-outlined">place</span>
                        </div>
                        
                        <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-2 group-hover:text-tkd-blue transition-colors">
                            <?= htmlspecialchars($cede['nombre']) ?>
                        </h3>
                        
                        <div class="space-y-3 mt-4">
                            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="material-icons-outlined text-tkd-blue">phone</span>
                                <span class="font-medium text-sm"><?= htmlspecialchars($cede['telefono'] ?? 'N/A') ?></span>
                            </div>
                            
                            <div class="flex items-start gap-3 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="material-icons-outlined text-tkd-blue mt-0.5">location_on</span>
                                <span class="text-sm leading-relaxed"><?= htmlspecialchars($cede['direccion']) ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800/80 transition-colors">
                            <a href="https://maps.google.com/maps?q=<?= urlencode($cede['direccion']) ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold uppercase text-sm hover:bg-tkd-blue hover:border-tkd-blue hover:text-white hover:shadow-lg transition-all">
                                <span>Cómo llegar</span>
                                <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

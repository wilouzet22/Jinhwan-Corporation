<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="bg-tkd-black text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-l from-tkd-blue/20 to-transparent"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up">
            Portal del <span class="text-tkd-blue">Alumno</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up" style="animation-delay: 0.2s;">
            Bienvenido, <?= htmlspecialchars($estudiante['nombre'] ?? 'Estudiante') ?>.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-16 bg-tkd-gray dark:bg-tkd-black" style="clip-path: polygon(0 0, 0 100%, 100% 100%);"></div>
</section>

<section class="py-16 bg-tkd-gray dark:bg-tkd-black min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Profile Card -->
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden text-center p-8 border-t-4 border-tkd-blue transform transition hover:-translate-y-1">
                    <div class="w-32 h-32 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-5xl font-bold text-slate-400 mb-6 shadow-inner uppercase">
                        <?= substr($estudiante['nombre'] ?? 'A', 0, 1) ?>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-1"><?= htmlspecialchars(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')) ?></h2>
                    <p class="text-slate-500 mb-6">Documento: <?= htmlspecialchars($estudiante['numero_documento'] ?? '') ?></p>
                    
                    <div class="inline-block px-4 py-2 bg-slate-100 dark:bg-slate-700 rounded-full font-bold uppercase tracking-wider text-sm mb-4 border border-slate-200 dark:border-slate-600">
                        Cinturón: <span class="text-tkd-blue"><?= htmlspecialchars($estudiante['nombre_nivel'] ?? 'Blanco') ?></span>
                    </div>
                    <div class="text-sm text-slate-500">
                        Sede: <span class="font-semibold text-slate-700 dark:text-slate-300"><?= htmlspecialchars($estudiante['nombre_sede'] ?? 'Sin Asignar') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="md:col-span-2 flex flex-col gap-6">
                <a href="<?= base_url('/estudiante/estudio') ?>" class="group bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border-l-4 border-tkd-red flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full bg-red-50 text-tkd-red flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined text-3xl">menu_book</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-2 group-hover:text-tkd-red transition-colors">Estudio Teórico</h3>
                        <p class="text-slate-500 dark:text-slate-400">Accede a todo el material teórico, videos y documentos para preparar tu próximo ascenso o repasar temas anteriores.</p>
                    </div>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all text-tkd-red">
                        <span class="material-icons-outlined text-3xl">arrow_forward</span>
                    </div>
                </a>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 flex items-center gap-6 opacity-60">
                    <div class="w-16 h-16 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-outlined text-3xl">history</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-display font-bold text-slate-500 dark:text-slate-400 mb-2">Mi Historial (Próximamente)</h3>
                        <p class="text-slate-400 dark:text-slate-500">Aquí podrás ver tu progreso, asistencias y fechas de ascensos anteriores.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

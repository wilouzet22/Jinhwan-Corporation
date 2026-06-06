<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="bg-tkd-black text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-l from-tkd-blue/10 via-transparent to-transparent"></div>
    <!-- Decorative Glowing Orb -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] h-[350px] rounded-full bg-tkd-blue/5 blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="text-xs font-bold text-tkd-blue tracking-[0.25em] uppercase mb-3 block animate-fade-in-up">Jinhwan Academy</span>
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up">
            Portal del <span class="text-tkd-blue">Alumno</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up" style="animation-delay: 0.2s;">
            Bienvenido, <?= htmlspecialchars($estudiante['nombre'] ?? 'Estudiante') ?>.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-16 bg-[#0b0f19]" style="clip-path: polygon(0 0, 0 100%, 100% 100%);"></div>
</section>

<section class="py-16 bg-[#0b0f19] min-h-screen relative overflow-hidden">
    <!-- Ambient backgrounds -->
    <div class="absolute top-1/4 left-1/10 w-[40%] h-[40%] rounded-full bg-tkd-blue/5 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/10 w-[40%] h-[40%] rounded-full bg-tkd-red/5 blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 max-w-5xl relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Profile Card -->
            <div class="md:col-span-1">
                <div class="glass-panel rounded-3xl shadow-2xl overflow-hidden text-center p-8 border-t-4 border-t-tkd-blue transition-all duration-300 hover:shadow-[0_0_30px_rgba(37,99,235,0.15)] hover:border-t-blue-500">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-slate-800 to-slate-900 rounded-full flex items-center justify-center text-5xl font-display font-bold text-tkd-blue mb-6 shadow-inner border border-slate-700/50 uppercase">
                        <?= substr($estudiante['nombre'] ?? 'A', 0, 1) ?>
                    </div>
                    <h2 class="text-2xl font-display font-bold text-white mb-1 tracking-wide"><?= htmlspecialchars(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')) ?></h2>
                    <p class="text-slate-400 mb-6 text-sm font-light">Documento: <?= htmlspecialchars($estudiante['numero_documento'] ?? '') ?></p>
                    
                    <div class="inline-block px-4 py-2 bg-slate-950/40 rounded-full font-bold uppercase tracking-wider text-xs mb-4 border border-slate-800 text-slate-300">
                        Cinturón: <span class="text-tkd-blue font-extrabold ml-1"><?= htmlspecialchars($estudiante['nombre_nivel'] ?? 'Blanco') ?></span>
                    </div>
                    <div class="text-sm text-slate-400">
                        Sede: <span class="font-semibold text-white ml-1"><?= htmlspecialchars($estudiante['nombre_sede'] ?? 'Sin Asignar') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="md:col-span-2 flex flex-col gap-6">
                <a href="<?= base_url('/estudiante/estudio') ?>" class="group glass-card rounded-3xl p-8 shadow-xl border-l-4 border-l-tkd-red flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full bg-red-950/40 border border-red-900/40 text-tkd-red flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(220,38,38,0.2)]">
                        <span class="material-icons-outlined text-3xl">menu_book</span>
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-2xl font-display font-bold text-white mb-2 group-hover:text-tkd-red transition-colors tracking-wide">Estudio Teórico</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-light">Accede a todo el material teórico, videos y documentos para preparar tu próximo ascenso o repasar temas anteriores.</p>
                    </div>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all text-tkd-red">
                        <span class="material-icons-outlined text-3xl">arrow_forward</span>
                    </div>
                </a>

                <div class="glass-card rounded-3xl p-8 border-l-4 border-l-slate-700/50 flex items-center gap-6 opacity-60">
                    <div class="w-16 h-16 rounded-full bg-slate-950/40 border border-slate-800 text-slate-500 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-outlined text-3xl">history</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-display font-bold text-slate-400 mb-2 tracking-wide">Mi Historial (Próximamente)</h3>
                        <p class="text-slate-500 text-sm leading-relaxed font-light">Aquí podrás ver tu progreso, asistencias y fechas de ascensos anteriores.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

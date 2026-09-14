<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="text-slate-900 dark:text-white py-20 relative overflow-hidden transition-colors duration-300" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-tkd-blue/10 dark:from-tkd-blue/15 to-transparent"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up transition-colors">
            Nuestros <span class="text-tkd-blue">Grupos</span>
        </h1>
        <div class="w-24 h-1 bg-gradient-to-r from-tkd-blue to-tkd-red mx-auto rounded-full mb-6"></div>
        <p class="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up transition-colors" style="animation-delay: 0.2s;">
            Encuentra el grupo de entrenamiento perfecto para ti según tu edad, nivel técnico y objetivos marciales.
        </p>
    </div>
</section>

<section class="py-16 relative min-h-[60vh] overflow-hidden transition-colors duration-300" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 60%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 60%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <?php if (empty($grupos)): ?>
            <div class="text-center py-16">
                <span class="material-icons-outlined text-6xl text-slate-400">groups</span>
                <p class="text-lg text-slate-500 mt-2">No hay grupos disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($grupos as $index => $g): ?>
                    <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden group shadow-sm hover:shadow-xl animate-fade-in-up transition-all duration-300 flex flex-col justify-between" style="animation-delay: <?= $index * 0.1 ?>s;">
                        
                        <div>
                            <!-- Header de la tarjeta -->
                            <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800/80 bg-gradient-to-r from-slate-50 to-white dark:from-slate-950/60 dark:to-slate-900/60 flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-tkd-blue/20 text-tkd-blue border border-blue-200 dark:border-blue-800/40 mb-2">
                                        Grupo Oficial
                                    </span>
                                    <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white group-hover:text-tkd-blue transition-colors leading-tight">
                                        <?= htmlspecialchars($g['nombre']) ?>
                                    </h3>
                                </div>
                                <div class="w-12 h-12 bg-tkd-blue rounded-xl flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-300 shrink-0">
                                    <span class="material-icons-outlined text-2xl">groups</span>
                                </div>
                            </div>

                            <!-- Cuerpo de la tarjeta -->
                            <div class="p-6 space-y-4">
                                <?php if (!empty($g['descripcion'])): ?>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                                        <?= htmlspecialchars($g['descripcion']) ?>
                                    </p>
                                <?php endif; ?>

                                <div class="space-y-3 pt-2">
                                    <!-- Sede -->
                                    <div class="flex items-start gap-3 text-slate-700 dark:text-slate-300">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-tkd-blue shrink-0 mt-0.5">
                                            <span class="material-icons-outlined text-lg">place</span>
                                        </div>
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">Sede</p>
                                            <p class="text-sm font-semibold"><?= htmlspecialchars($g['nombre_sede'] ?? 'Sin asignar') ?></p>
                                        </div>
                                    </div>

                                    <!-- Instructor -->
                                    <div class="flex items-start gap-3 text-slate-700 dark:text-slate-300">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0 mt-0.5">
                                            <span class="material-icons-outlined text-lg">sports_martial_arts</span>
                                        </div>
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">Instructor a Cargo</p>
                                            <p class="text-sm font-semibold"><?= htmlspecialchars($g['nombre_maestro'] ?? 'Instructor asignado por sede') ?></p>
                                        </div>
                                    </div>

                                    <!-- Horario -->
                                    <div class="flex items-start gap-3 text-slate-700 dark:text-slate-300">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5">
                                            <span class="material-icons-outlined text-lg">schedule</span>
                                        </div>
                                        <div>
                                            <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400">Horario de Entrenamiento</p>
                                            <p class="text-sm font-semibold"><?= htmlspecialchars($g['horario'] ?? 'Consultar en recepción') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

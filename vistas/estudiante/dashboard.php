<?php 
$current_page = 'dashboard';
include __DIR__ . '/../layout/estudiante_cabecera.php'; 
?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">

    <!-- Student Hero Banner -->
    <div class="bg-gradient-to-r from-blue-950 via-slate-900 to-cyan-950 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-blue-900/40">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold tracking-wider uppercase border border-cyan-500/30">
                    <span class="pulse-dot bg-cyan-400"></span>
                    <span>Portal del Alumno</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white">¡Bienvenido, <?= htmlspecialchars($estudiante['nombre'] ?? 'Estudiante') ?>!</h1>
                <p class="text-slate-300 text-sm max-w-xl">Revisa tu grado actual, repasa la teoría para tus exámenes y consulta los eventos de la academia.</p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-200 flex items-center gap-2 border border-white/15 shadow-inner">
                    <span class="material-icons-outlined text-cyan-400 text-lg">calendar_today</span>
                    <?= date('d M, Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- React Interactive Belt Tracker -->
    <div 
        data-react-component="BeltProgressVisualizer"
        data-props='<?= json_encode([
            'currentGrade' => $estudiante['nombre_nivel'] ?? 'Blanco',
            'nextGrade' => 'Siguiente Grado Evaluativo',
            'timeInGrade' => 'Tiempo activo en sede'
        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'
    ></div>

    <!-- Main Grid: Profile & Learning Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Student Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 flex flex-col items-center text-center relative overflow-hidden">
                <div class="w-24 h-24 bg-gradient-to-br from-tkd-blue via-blue-600 to-cyan-500 rounded-3xl flex items-center justify-center text-4xl font-black text-white mb-4 shadow-lg shadow-blue-500/20 shrink-0 border-2 border-white/20">
                    <?= strtoupper(substr($estudiante['nombre'] ?? 'A', 0, 1)) ?>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-0.5"><?= htmlspecialchars(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')) ?></h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mb-6 font-medium">Documento: <?= htmlspecialchars($estudiante['numero_documento'] ?? 'N/A') ?></p>
                
                <div class="w-full space-y-3">
                    <div class="bg-blue-50/70 dark:bg-blue-500/10 rounded-xl p-3.5 border border-blue-100 dark:border-blue-500/20 flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Cinturón Actual</span>
                        <span class="px-3 py-1 bg-tkd-blue text-white rounded-full font-black text-xs shadow-sm uppercase tracking-wider"><?= htmlspecialchars($estudiante['nombre_nivel'] ?? 'Blanco') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5 border border-slate-200 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Sede Asignada</span>
                        <span class="text-slate-900 dark:text-white font-bold text-xs"><?= htmlspecialchars($estudiante['nombre_sede'] ?? 'Sin Asignar') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning & Activity Modules -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            <a href="<?= base_url('/estudiante/estudio') ?>" class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex items-center gap-6 hover-lift hover:border-red-500 transition-all group">
                <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-500/10 text-tkd-red flex items-center justify-center shrink-0 border border-red-100 dark:border-red-500/20 group-hover:scale-110 transition-transform shadow-inner">
                    <span class="material-icons-outlined text-3xl">menu_book</span>
                </div>
                <div class="flex-grow">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1 group-hover:text-tkd-red transition-colors flex items-center gap-2">
                        Estudio Teórico
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-red-500/10 text-tkd-red font-bold uppercase tracking-wider">Material Interactivo</span>
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Accede a módulos teóricos, formas, técnicas y glosario para preparar tu próximo examen de grado.</p>
                </div>
                <div class="hidden sm:block ml-auto text-slate-400 dark:text-slate-500 group-hover:text-tkd-red group-hover:translate-x-1 transition-all">
                    <span class="material-icons-outlined text-xl">arrow_forward</span>
                </div>
            </a>

            <a href="<?= base_url('/estudiante/historial') ?>" class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-800 flex items-center gap-6 hover-lift hover:border-amber-400 transition-all group">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500 flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-500/20 group-hover:scale-110 transition-transform shadow-inner">
                    <span class="material-icons-outlined text-3xl">history</span>
                </div>
                <div class="flex-grow">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1 group-hover:text-amber-600 transition-colors">Mi Historial de Ascensos</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Revisa la cronología de tus cinturones obtenidos y fechas de exámenes pasados.</p>
                </div>
                <div class="hidden sm:block ml-auto text-slate-400 dark:text-slate-500 group-hover:text-amber-600 group-hover:translate-x-1 transition-all">
                    <span class="material-icons-outlined text-xl">arrow_forward</span>
                </div>
            </a>

            <!-- Upcoming Events Block -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-icons-outlined text-tkd-blue text-xl">event</span>
                        Próximos Eventos de la Academia
                    </h3>
                    <a href="<?= base_url('/usuario/calendario') ?>" class="text-tkd-blue hover:text-blue-700 text-xs font-bold uppercase tracking-wider transition-colors">Ver calendario →</a>
                </div>
                <div class="p-6 divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php if (!empty($proximos_eventos)): ?>
                        <?php foreach($proximos_eventos as $evento): ?>
                            <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-4">
                                <div class="bg-blue-50 dark:bg-blue-500/10 text-tkd-blue dark:text-blue-400 w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 border border-blue-200 dark:border-blue-500/20 shadow-sm">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider"><?= date('M', strtotime($evento['start'])) ?></span>
                                    <span class="text-lg font-black leading-none"><?= date('d', strtotime($evento['start'])) ?></span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($evento['title']) ?></h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1">
                                        <span class="material-icons-outlined text-xs">schedule</span>
                                        <?= date('h:i A', strtotime($evento['start'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-slate-500 dark:text-slate-400 py-4 text-center">No hay eventos próximos en este momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>

</main>

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>


<?php require_once __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Navegación -->
        <div>
            <a href="<?php echo base_url('admin/cronogramas'); ?>" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 hover:text-blue-700 transition mb-3">
                <span class="material-icons-outlined text-sm">arrow_back</span>
                Volver a Cronogramas
            </a>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm transition-colors">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                <?php echo htmlspecialchars($cronograma['grupo_nombre'] ?? 'Grupo'); ?>
                            </span>
                            <?php if (!empty($cronograma['grupo_sede'])): ?>
                                <span class="text-xs text-slate-400 dark:text-slate-500">• <?php echo htmlspecialchars($cronograma['grupo_sede']); ?></span>
                            <?php endif; ?>
                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                • <span class="font-medium">Maestro:</span> <?php echo htmlspecialchars($cronograma['maestro_nombre'] ?? 'N/A'); ?>
                            </span>
                        </div>
<?php
$diasEsp = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$mesesEsp = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$ts = strtotime($cronograma['fecha']);
$fechaTexto = $diasEsp[date('w', $ts)] . ', ' . date('j', $ts) . ' de ' . $mesesEsp[(int)date('n', $ts)] . ' de ' . date('Y', $ts);
?>
                        <h1 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                            Sesión del <?php echo $fechaTexto; ?>
                        </h1>
                    </div>
                    <!-- Badge solo lectura -->
                    <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-xl text-amber-700 dark:text-amber-400 text-xs font-semibold shrink-0">
                        <span class="material-icons-outlined text-sm">visibility</span>
                        Solo lectura
                    </div>
                </div>

                <?php if (!empty($cronograma['objetivo']) || !empty($cronograma['observaciones'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-sm">
                        <?php if (!empty($cronograma['objetivo'])): ?>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Objetivo Principal:</span>
                                <p class="text-slate-800 dark:text-slate-200 font-medium"><?php echo nl2br(htmlspecialchars($cronograma['objetivo'])); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($cronograma['observaciones'])): ?>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Observaciones:</span>
                                <p class="text-slate-700 dark:text-slate-300"><?php echo nl2br(htmlspecialchars($cronograma['observaciones'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fases -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- FASE INICIAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col overflow-hidden transition-colors">
                <div class="p-4 border-b border-amber-200/40 dark:border-amber-900/30 bg-amber-500/10 flex items-center gap-2.5">
                    <span class="p-1.5 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 rounded-xl material-icons-outlined text-base">wb_sunny</span>
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Inicial</h2>
                        <span class="text-[11px] text-amber-600 dark:text-amber-400">Calentamiento y movilidad</span>
                    </div>
                    <span class="ml-auto text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-950/40 px-2 py-0.5 rounded-full">
                        <?php echo count($fases['inicial']); ?> ejercicios
                    </span>
                </div>
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['inicial'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['inicial'] as $item): ?>
                            <?php renderAdminEjercicioCard($item); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FASE CENTRAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col overflow-hidden transition-colors">
                <div class="p-4 border-b border-blue-200/40 dark:border-blue-900/30 bg-blue-500/10 flex items-center gap-2.5">
                    <span class="p-1.5 bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 rounded-xl material-icons-outlined text-base">sports_martial_arts</span>
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Central</h2>
                        <span class="text-[11px] text-blue-600 dark:text-blue-400">Técnica, combate y carga</span>
                    </div>
                    <span class="ml-auto text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-950/40 px-2 py-0.5 rounded-full">
                        <?php echo count($fases['central']); ?> ejercicios
                    </span>
                </div>
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['central'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['central'] as $item): ?>
                            <?php renderAdminEjercicioCard($item); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FASE FINAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col overflow-hidden transition-colors">
                <div class="p-4 border-b border-emerald-200/40 dark:border-emerald-900/30 bg-emerald-500/10 flex items-center gap-2.5">
                    <span class="p-1.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-xl material-icons-outlined text-base">self_improvement</span>
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Final</h2>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400">Vuelta a la calma y estiramiento</span>
                    </div>
                    <span class="ml-auto text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">
                        <?php echo count($fases['final']); ?> ejercicios
                    </span>
                </div>
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['final'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['final'] as $item): ?>
                            <?php renderAdminEjercicioCard($item); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php
function renderAdminEjercicioCard($item) {
?>
    <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl p-3 transition">
        <span class="inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-lg bg-white dark:bg-slate-900 text-blue-700 dark:text-blue-300 border border-slate-200 dark:border-slate-800 mb-1">
            <?php echo htmlspecialchars($item['ejercicio_tipo']); ?>
        </span>
        <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">
            <?php echo htmlspecialchars($item['ejercicio_nombre']); ?>
        </h4>
        <?php if (!empty($item['series_o_tiempo'])): ?>
            <div class="mt-2 flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 font-semibold">
                <span class="material-icons-outlined text-xs">timer</span>
                <span><?php echo htmlspecialchars($item['series_o_tiempo']); ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($item['observaciones_especificas'])): ?>
            <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 italic bg-white dark:bg-slate-900 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800">
                "<?php echo htmlspecialchars($item['observaciones_especificas']); ?>"
            </p>
        <?php endif; ?>
        <?php if (!empty($item['ejercicio_explicacion'])): ?>
            <details class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                <summary class="cursor-pointer text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-semibold select-none">Ver técnica de biblioteca</summary>
                <div class="p-2.5 mt-1 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300">
                    <?php echo nl2br(htmlspecialchars($item['ejercicio_explicacion'])); ?>
                </div>
            </details>
        <?php endif; ?>
    </div>
<?php
}
?>

<?php require_once __DIR__ . '/../layout/administracion_pie.php'; ?>

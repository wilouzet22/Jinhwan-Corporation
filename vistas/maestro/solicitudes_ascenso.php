<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Solicitudes de Ascenso</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Historial y estado de las propuestas de grado que has enviado al administrador.</p>
        </div>
        <a href="<?= base_url('/maestro/alumnos') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-display font-bold uppercase tracking-wider py-3 px-5 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 focus:outline-none shrink-0 self-start md:self-auto">
            <span class="material-icons-outlined">person</span>
            Nueva Propuesta
        </a>
    </div>

    <!-- Feedback Message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 rounded-xl text-emerald-800 dark:text-emerald-400 text-sm font-semibold flex items-center gap-2">
            <span class="material-icons-outlined text-lg">check_circle</span>
            ¡Solicitud de ascenso enviada con éxito! Queda a la espera de la aprobación del administrador.
        </div>
    <?php endif; ?>

    <!-- Solicitudes Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                    <tr>
                        <th scope="col" class="px-6 py-4">Deportista</th>
                        <th scope="col" class="px-6 py-4 text-center">Cambio de Cinturón</th>
                        <th scope="col" class="px-6 py-4">Fecha Solicitud</th>
                        <th scope="col" class="px-6 py-4">Observaciones</th>
                        <th scope="col" class="px-6 py-4 text-center">Estado</th>
                        <th scope="col" class="px-6 py-4">Resolución</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    <?php if (empty($solicitudes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                <span class="material-icons-outlined text-4xl block mb-2 text-slate-300 dark:text-slate-700">history_toggle_off</span>
                                No has enviado ninguna solicitud de ascenso de grado todavía.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all">
                                <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-tkd-purple flex items-center justify-center mr-3 font-bold shrink-0">
                                            <?= strtoupper(substr($s['nombre_alumno'], 0, 1)) ?>
                                        </div>
                                        <span class="text-slate-800 dark:text-white"><?= htmlspecialchars($s['nombre_alumno'] . ' ' . $s['apellido_alumno']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2 text-xs font-semibold">
                                        <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300"><?= htmlspecialchars($s['grado_actual']) ?></span>
                                        <span class="material-icons-outlined text-slate-400 text-sm">arrow_forward</span>
                                        <span class="px-2.5 py-1 rounded bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400"><?= htmlspecialchars($s['grado_solicitado']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    <?= date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) ?>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-300 max-w-[200px] truncate" title="<?= htmlspecialchars($s['observaciones'] ?? '') ?>">
                                    <?= htmlspecialchars($s['observaciones'] ?? 'Sin observaciones') ?>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <?php if ($s['estado'] === 'pendiente'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 uppercase tracking-wider">
                                            Pendiente
                                        </span>
                                    <?php elseif ($s['estado'] === 'aprobado'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 uppercase tracking-wider">
                                            Aprobado
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 uppercase tracking-wider">
                                            Rechazado
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    <?= $s['fecha_resolucion'] ? date('d/m/Y', strtotime($s['fecha_resolucion'])) : '---' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

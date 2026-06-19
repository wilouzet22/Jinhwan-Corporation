<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Panel de Control del Maestro</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Resumen general y propuestas de ascenso</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-slate-900 px-4 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 flex items-center gap-2 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
                <span class="material-icons-outlined text-sm">calendar_today</span>
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Total Students -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-purple-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-tkd-purple shrink-0">
                <span class="material-icons-outlined text-2xl">people</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Deportistas</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['total_alumnos'] ?></h3>
            </div>
        </div>

        <!-- Pending Promotions -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-amber-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-500 shrink-0">
                <span class="material-icons-outlined text-2xl">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Ascensos Pendientes</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['solicitudes_pendientes'] ?></h3>
            </div>
        </div>

        <!-- Approved Promotions -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-emerald-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-500 shrink-0">
                <span class="material-icons-outlined text-2xl">task_alt</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Ascensos Aprobados</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['solicitudes_aprobadas'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Belt Distribution Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Distribución de Cinturones</h3>
                <span class="material-icons-outlined text-slate-400 dark:text-slate-500 text-sm">donut_large</span>
            </div>
            <div class="h-64 flex-grow relative">
                <canvas id="gradosChart"></canvas>
            </div>
        </div>

        <!-- Quick actions / Info -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col transition-colors duration-300">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 mb-4">Acciones del Instructor</h3>
            <div class="space-y-3 flex-1 flex flex-col justify-center">
                <a href="<?= base_url('/maestro/alumnos') ?>" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 dark:border-slate-800 hover:bg-purple-50 dark:hover:bg-purple-950/20 hover:border-purple-200 dark:hover:border-purple-900/40 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-tkd-purple group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined">people</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Ver Mis Alumnos</p>
                        <p class="text-[11px] text-slate-500">Consulta y propón ascensos</p>
                    </div>
                </a>
                <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 dark:border-slate-800 hover:bg-purple-50 dark:hover:bg-purple-950/20 hover:border-purple-200 dark:hover:border-purple-900/40 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-tkd-purple group-hover:scale-110 transition-transform">
                        <span class="material-icons-outlined">timeline</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Historial de Solicitudes</p>
                        <p class="text-[11px] text-slate-500">Estado de tus propuestas de grado</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Deportistas Registrados Recientemente</h3>
            <a href="<?= base_url('/maestro/alumnos') ?>" class="text-tkd-purple hover:text-purple-700 dark:hover:text-purple-400 text-xs font-semibold transition-colors">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Deportista</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Fecha Nacimiento</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($ultimos_alumnos as $miembro): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center text-tkd-purple font-bold text-xs shrink-0">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm"><?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                <?= $miembro['fecha'] ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($miembro['activo']): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 uppercase tracking-wider">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 uppercase tracking-wider">
                                        Pendiente
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = () => document.documentElement.classList.contains('dark');
        
        const getChartColors = () => ({
            text: isDarkMode() ? '#94a3b8' : '#64748b',
            grid: isDarkMode() ? 'rgba(148, 163, 184, 0.1)' : 'rgba(100, 116, 139, 0.1)',
            borderColor: isDarkMode() ? '#0f172a' : '#ffffff'
        });

        // ── GRADOS CHART (DOUGHNUT) ─────────────────────────────
        const gradosData = <?= json_encode($distribucion_grados) ?>;
        const ctxGrados = document.getElementById('gradosChart');
        
        if (ctxGrados && gradosData.length > 0) {
            const colors = getChartColors();
            
            const chart = new Chart(ctxGrados, {
                type: 'doughnut',
                data: {
                    labels: gradosData.map(d => d.nombre),
                    datasets: [{
                        data: gradosData.map(d => d.cantidad),
                        backgroundColor: [
                            '#F1F5F9', // Blanco (Gray 100)
                            '#FEF08A', // Amarillo
                            '#BBF7D0', // Verde
                            '#BFDBFE', // Azul
                            '#FCA5A5', // Rojo
                            '#1E293B', // Negro
                            '#E9D5FF', // Violeta/Otros
                            '#FED7AA'  // Naranja
                        ],
                        borderWidth: 2,
                        borderColor: colors.borderColor
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: colors.text,
                                font: { family: 'Inter', size: 12, weight: '500' },
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Update on theme change
            window.addEventListener('themeChanged', () => {
                const newColors = getChartColors();
                chart.options.plugins.legend.labels.color = newColors.text;
                chart.data.datasets[0].borderColor = newColors.borderColor;
                chart.update();
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

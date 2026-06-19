<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Panel de Control</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Resumen general y métricas de la academia</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-slate-900 px-4 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 flex items-center gap-2 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
                <span class="material-icons-outlined text-sm">calendar_today</span>
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-blue-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-500 shrink-0">
                <span class="material-icons-outlined text-2xl">people</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Miembros</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['total_miembros'] ?></h3>
            </div>
        </div>

        <!-- Active Members -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-emerald-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-500 shrink-0">
                <span class="material-icons-outlined text-2xl">verified</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Activos</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['activos'] ?></h3>
            </div>
        </div>

        <!-- Pending Registrations -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-amber-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-500 shrink-0">
                <span class="material-icons-outlined text-2xl">how_to_reg</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pendientes</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['pendientes'] ?></h3>
            </div>
        </div>

        <!-- Total Sedes -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-purple-300 dark:hover:border-slate-700 transition-colors shadow-sm duration-300">
            <div class="w-12 h-12 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-500 shrink-0">
                <span class="material-icons-outlined text-2xl">store</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Sedes</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100"><?= $stats['total_sedes'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Belt Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Distribución por Grados</h3>
                <span class="material-icons-outlined text-slate-400 dark:text-slate-500 text-sm">donut_large</span>
            </div>
            <div class="h-64 flex-grow relative">
                <canvas id="gradosChart"></canvas>
            </div>
        </div>

        <!-- Sedes Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Alumnos por Sede</h3>
                <span class="material-icons-outlined text-slate-400 dark:text-slate-500 text-sm">bar_chart</span>
            </div>
            <div class="h-64 flex-grow relative">
                <canvas id="sedesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Últimos Registros</h3>
            <a href="<?= base_url('/admin/miembros') ?>" class="text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 text-xs font-semibold transition-colors">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Miembro</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Fecha</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($ultimos_miembros as $miembro): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold text-xs shrink-0">
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

        // Data for Grados Chart
        const gradosData = <?= json_encode($distribucion_grados) ?>;
        const ctxGrados = document.getElementById('gradosChart').getContext('2d');
        const gradosChart = new Chart(ctxGrados, {
            type: 'doughnut',
            data: {
                labels: gradosData.map(d => d.nombre),
                datasets: [{
                    data: gradosData.map(d => d.cantidad),
                    backgroundColor: [
                        '#f8fafc', '#eab308', '#22c55e', '#3b82f6', '#ef4444', '#1e293b',
                        '#0ea5e9', '#ec4899'
                    ],
                    borderColor: getChartColors().borderColor,
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getChartColors().text,
                            font: { family: 'Inter', size: 11 },
                            usePointStyle: true,
                            padding: 15
                        }
                    }
                },
                cutout: '75%'
            }
        });

        // Data for Sedes Chart
        const sedesData = <?= json_encode($distribucion_sedes) ?>;
        const ctxSedes = document.getElementById('sedesChart').getContext('2d');
        const sedesChart = new Chart(ctxSedes, {
            type: 'bar',
            data: {
                labels: sedesData.map(d => d.nombre),
                datasets: [{
                    label: 'Alumnos',
                    data: sedesData.map(d => d.cantidad),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: getChartColors().grid, drawBorder: false },
                        ticks: { color: getChartColors().text, font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: getChartColors().text, font: { size: 11 } }
                    }
                }
            }
        });

        // Listen for theme change events to update charts
        window.addEventListener('themeChanged', () => {
            const colors = getChartColors();
            
            // Update Doughnut Chart
            gradosChart.data.datasets[0].borderColor = colors.borderColor;
            gradosChart.options.plugins.legend.labels.color = colors.text;
            gradosChart.update();
            
            // Update Bar Chart
            sedesChart.options.scales.y.grid.color = colors.grid;
            sedesChart.options.scales.y.ticks.color = colors.text;
            sedesChart.options.scales.x.ticks.color = colors.text;
            sedesChart.update();
        });
    });
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

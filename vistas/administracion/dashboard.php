<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-100">Panel de Control</h1>
            <p class="text-slate-400 mt-1 text-sm">Resumen general y métricas de la academia</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-slate-900 px-4 py-2 rounded-lg text-sm font-medium text-slate-400 flex items-center gap-2 border border-slate-800 shadow-sm">
                <span class="material-icons-outlined text-sm">calendar_today</span>
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 flex items-center gap-5 hover:border-slate-700 transition-colors shadow-sm">
            <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                <span class="material-icons-outlined text-2xl">people</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Miembros</p>
                <h3 class="text-2xl font-bold text-slate-100"><?= $stats['total_miembros'] ?></h3>
            </div>
        </div>

        <!-- Active Members -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 flex items-center gap-5 hover:border-slate-700 transition-colors shadow-sm">
            <div class="w-12 h-12 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <span class="material-icons-outlined text-2xl">verified</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Activos</p>
                <h3 class="text-2xl font-bold text-slate-100"><?= $stats['activos'] ?></h3>
            </div>
        </div>

        <!-- Pending Registrations -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 flex items-center gap-5 hover:border-slate-700 transition-colors shadow-sm">
            <div class="w-12 h-12 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                <span class="material-icons-outlined text-2xl">how_to_reg</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pendientes</p>
                <h3 class="text-2xl font-bold text-slate-100"><?= $stats['pendientes'] ?></h3>
            </div>
        </div>

        <!-- Total Sedes -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 flex items-center gap-5 hover:border-slate-700 transition-colors shadow-sm">
            <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-500">
                <span class="material-icons-outlined text-2xl">store</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Sedes</p>
                <h3 class="text-2xl font-bold text-slate-100"><?= $stats['total_sedes'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Belt Distribution Chart -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-slate-100">Distribución por Grados</h3>
                <span class="material-icons-outlined text-slate-500 text-sm">donut_large</span>
            </div>
            <div class="h-64 flex-grow relative">
                <canvas id="gradosChart"></canvas>
            </div>
        </div>

        <!-- Sedes Distribution Chart -->
        <div class="bg-slate-900 p-6 rounded-xl border border-slate-800 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-slate-100">Alumnos por Sede</h3>
                <span class="material-icons-outlined text-slate-500 text-sm">bar_chart</span>
            </div>
            <div class="h-64 flex-grow relative">
                <canvas id="sedesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-slate-900 rounded-xl border border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-100">Últimos Registros</h3>
            <a href="<?= base_url('/admin/miembros') ?>" class="text-blue-500 hover:text-blue-400 text-xs font-medium transition-colors">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/50">
                        <th class="px-6 py-3 text-xs font-semibold text-slate-400 uppercase">Miembro</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-400 uppercase text-center">Fecha</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-400 uppercase text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    <?php foreach ($ultimos_miembros as $miembro): ?>
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-medium text-xs">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="font-medium text-slate-200 text-sm"><?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-slate-400">
                                <?= $miembro['fecha'] ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($miembro['activo']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-500 border border-amber-500/20">
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
        // Data for Grados Chart
        const gradosData = <?= json_encode($distribucion_grados) ?>;
        const ctxGrados = document.getElementById('gradosChart').getContext('2d');
        new Chart(ctxGrados, {
            type: 'doughnut',
            data: {
                labels: gradosData.map(d => d.nombre),
                datasets: [{
                    data: gradosData.map(d => d.cantidad),
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#64748b',
                        '#0ea5e9', '#ec4899'
                    ],
                    borderColor: '#0f172a',
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
                            color: '#94a3b8',
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
        new Chart(ctxSedes, {
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
                        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

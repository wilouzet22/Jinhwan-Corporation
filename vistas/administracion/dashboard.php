<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">Panel de Control</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Bienvenido al ecosistema de gestión de Jinhwa Corporation</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-slate-900 px-4 py-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 text-sm font-bold text-slate-500 flex items-center gap-2">
                <span class="material-icons-outlined text-sm">calendar_today</span>
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:border-tkd-blue transition-all">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-tkd-blue group-hover:bg-tkd-blue group-hover:text-white transition-all">
                <span class="material-icons-outlined text-3xl">people</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Miembros</p>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white"><?= $stats['total_miembros'] ?></h3>
            </div>
        </div>

        <!-- Active Members -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:border-emerald-500 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <span class="material-icons-outlined text-3xl">verified</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Activos</p>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white"><?= $stats['activos'] ?></h3>
            </div>
        </div>

        <!-- Pending Registrations -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:border-tkd-red transition-all">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-tkd-red group-hover:bg-tkd-red group-hover:text-white transition-all">
                <span class="material-icons-outlined text-3xl">how_to_reg</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pendientes</p>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white"><?= $stats['pendientes'] ?></h3>
            </div>
        </div>

        <!-- Total Sedes -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:border-tkd-gold transition-all">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-tkd-gold group-hover:bg-tkd-gold group-hover:text-white transition-all">
                <span class="material-icons-outlined text-3xl">store</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sedes</p>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white"><?= $stats['total_sedes'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Belt Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-display font-bold text-slate-800 dark:text-white uppercase tracking-wider">Distribución por Grados</h3>
                <span class="material-icons-outlined text-slate-300">donut_large</span>
            </div>
            <div class="h-64">
                <canvas id="gradosChart"></canvas>
            </div>
        </div>

        <!-- Sedes Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-display font-bold text-slate-800 dark:text-white uppercase tracking-wider">Alumnos por Sede</h3>
                <span class="material-icons-outlined text-slate-300">bar_chart</span>
            </div>
            <div class="h-64">
                <canvas id="sedesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-xl font-display font-bold text-slate-800 dark:text-white uppercase tracking-wider">Últimos Registros</h3>
            <a href="<?= base_url('/admin/miembros') ?>" class="text-tkd-blue hover:text-blue-700 text-xs font-bold uppercase tracking-widest">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Miembro</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Fecha</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php foreach ($ultimos_miembros as $miembro): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-bold text-sm">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-200"><?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                <?= $miembro['fecha'] ?>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $miembro['activo'] ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                    <?= $miembro['activo'] ? 'Activo' : 'Pendiente' ?>
                                </span>
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
                        '#e2e8f0', '#facc15', '#22c55e', '#3b82f6', '#ef4444', '#000000',
                        '#94a3b8', '#64748b'
                    ],
                    borderColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1e293b' : '#cbd5e1',
                    borderWidth: 1,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#94a3b8' : '#64748b',
                            font: { family: 'Roboto', size: 10 },
                            usePointStyle: true,
                            padding: 15
                        }
                    }
                },
                cutout: '70%'
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
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                    maxBarThickness: 40
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
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

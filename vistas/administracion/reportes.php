<?php
use App\Config\Roles;
include __DIR__ . '/../layout/administracion_cabecera.php';
?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-x-hidden transition-colors duration-300">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10 gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Reportes y Consultas</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Filtra y analiza los datos de todos los miembros en tiempo real.</p>
            </div>
            <!-- Contador dinámico -->
            <div class="flex items-center gap-3 bg-tkd-blue/10 dark:bg-tkd-blue/20 border border-tkd-blue/20 dark:border-tkd-blue/30 rounded-xl px-5 py-3">
                <span class="material-icons-outlined text-tkd-blue text-2xl">groups</span>
                <div>
                    <p id="results-count" class="text-2xl font-display font-bold text-tkd-blue leading-none"><?= count($miembros) ?></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Miembros encontrados</p>
                </div>
            </div>
        </div>

        <!-- Panel de Filtros -->
        <div class="mb-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300 relative z-10">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2 mr-2">
                    <span class="material-icons-outlined text-tkd-blue">filter_alt</span>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Filtros</span>
                </div>

                <div class="flex-1 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    <!-- Búsqueda por nombre -->
                    <div class="col-span-2 lg:col-span-1 relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <span class="material-icons-outlined text-slate-400 text-sm">search</span>
                        </span>
                        <input type="text" id="filter-search" placeholder="Buscar nombre..."
                               class="w-full pl-8 pr-3 py-2 text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                    </div>

                    <!-- Sede -->
                    <select id="filter-sede" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                        <option value="all">Todas las Sedes</option>
                        <?php foreach($sedes_list as $sede): ?>
                            <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Cinturón -->
                    <select id="filter-nivel" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                        <option value="all">Todos los Cinturones</option>
                        <?php foreach($niveles_list as $nivel): ?>
                            <option value="<?= htmlspecialchars($nivel['nombre']) ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Rol -->
                    <select id="filter-rol" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                        <option value="all">Todos los Roles</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Instructor">Instructor</option>
                        <option value="Alumno">Alumno</option>
                    </select>

                    <!-- Estado -->
                    <select id="filter-estado" class="w-full text-sm rounded-lg bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-tkd-blue transition-colors">
                        <option value="all">Todos los Estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Pendiente">Pendiente</option>
                    </select>
                </div>

                <button id="btn-reset-filters" class="flex items-center gap-1 text-sm text-slate-400 hover:text-tkd-blue transition-colors font-medium focus:outline-none whitespace-nowrap">
                    <span class="material-icons-outlined text-sm">restart_alt</span>
                    Limpiar
                </button>
            </div>

            <!-- Filtros activos (badges) -->
            <div id="active-filters" class="flex flex-wrap gap-2 mt-3 hidden"></div>
        </div>

        <!-- Gráficas -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
            <!-- Gráfica de Cinturones -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons-outlined text-tkd-blue text-lg">sports_martial_arts</span>
                        Distribución por Cinturón
                    </h3>
                    <span id="chart-cinturones-total" class="text-xs text-slate-400 font-medium"></span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[220px]">
                    <canvas id="chart-cinturones"></canvas>
                    <div id="chart-cinturones-empty" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 hidden">
                        <span class="material-icons-outlined text-3xl mb-2">bar_chart</span>
                        <span class="text-sm">Sin datos para mostrar</span>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Sedes -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm flex flex-col transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-icons-outlined text-tkd-blue text-lg">place</span>
                        Distribución por Sede
                    </h3>
                    <span id="chart-sedes-total" class="text-xs text-slate-400 font-medium"></span>
                </div>
                <div class="relative flex-grow flex items-center justify-center min-h-[220px]">
                    <canvas id="chart-sedes"></canvas>
                    <div id="chart-sedes-empty" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 hidden">
                        <span class="material-icons-outlined text-3xl mb-2">pie_chart</span>
                        <span class="text-sm">Sin datos para mostrar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Resultados -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-blue text-lg">table_rows</span>
                    Resultados de la Consulta
                </h3>
                <span id="table-count" class="text-xs font-semibold text-slate-400"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nombre</th>
                        <th scope="col" class="px-6 py-4">Documento</th>
                        <th scope="col" class="px-6 py-4">Cinturón</th>
                        <th scope="col" class="px-6 py-4">Sede</th>
                        <th scope="col" class="px-6 py-4">Rol</th>
                        <th scope="col" class="px-6 py-4">Contacto</th>
                    </tr>
                    </thead>
                    <tbody id="results-tbody" class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    <?php foreach ($miembros as $miembro):
                        $nivel = strtolower($miembro['nombre_nivel'] ?? '');
                        $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-900/60 dark:text-slate-400 dark:border-slate-800/80';
                        if (str_contains($nivel, 'blanco')) {
                            $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-200 shadow-sm';
                        } elseif (str_contains($nivel, 'amarillo')) {
                            $beltClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-950/60 dark:text-yellow-400 dark:border-yellow-800/30';
                        } elseif (str_contains($nivel, 'verde')) {
                            $beltClass = 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/60 dark:text-green-400 dark:border-green-800/30';
                        } elseif (str_contains($nivel, 'azul')) {
                            $beltClass = 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-800/30';
                        } elseif (str_contains($nivel, 'rojo')) {
                            $beltClass = 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/60 dark:text-red-400 dark:border-red-800/30';
                        } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                            $beltClass = 'bg-slate-900 text-white border border-slate-900 dark:bg-slate-950 dark:border-slate-800 shadow-md';
                        }

                        $rolName = 'Invitado';
                        $rolClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-900/60 dark:text-slate-400 dark:border-slate-800/80';
                        switch ($miembro['rol_id']) {
                            case Roles::ADMINISTRADOR:
                                $rolName = 'Administrador'; $rolClass = 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/60 dark:text-red-400 dark:border-red-800/30'; break;
                            case Roles::MAESTRO:
                                $rolName = 'Instructor'; $rolClass = 'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/60 dark:text-purple-400 dark:border-purple-800/30'; break;
                            case Roles::ESTUDIANTE:
                                $rolName = 'Alumno'; $rolClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800/30'; break;
                        }
                    ?>
                        <tr class="member-row hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all"
                            data-nombre="<?= strtolower(htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido'])) ?>"
                            data-sede="<?= htmlspecialchars($miembro['nombre_sede'] ?? '') ?>"
                            data-nivel="<?= htmlspecialchars($miembro['nombre_nivel'] ?? '') ?>"
                            data-rol="<?= $rolName ?>"
                            data-estado="<?= ($miembro['activo'] ?? 1) ? 'Activo' : 'Pendiente' ?>">
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-tkd-blue to-blue-700 text-white flex items-center justify-center mr-3 font-bold text-sm shrink-0 shadow-sm">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-800 dark:text-white"><?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-2 py-1 rounded mr-1"><?= htmlspecialchars($miembro['tipo_documento']) ?></span>
                                <?= htmlspecialchars($miembro['numero_documento']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                    <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm text-tkd-blue">place</span>
                                    <?= htmlspecialchars($miembro['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $rolClass ?>">
                                    <?= $rolName ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                <div class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($miembro['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[160px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Sin resultados -->
                <div id="no-results-msg" class="hidden flex flex-col items-center justify-center py-16 text-center">
                    <span class="material-icons-outlined text-5xl text-slate-300 dark:text-slate-700 mb-3">search_off</span>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">No se encontraron miembros con estos filtros.</p>
                    <button onclick="document.getElementById('btn-reset-filters').click()" class="mt-3 text-tkd-blue text-sm font-semibold hover:underline focus:outline-none">Limpiar filtros</button>
                </div>
            </div>
        </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const allRows = Array.from(document.querySelectorAll('.member-row'));

    const filters = { search: '', sede: 'all', nivel: 'all', rol: 'all', estado: 'all' };

    const searchInput    = document.getElementById('filter-search');
    const sedeSelect     = document.getElementById('filter-sede');
    const nivelSelect    = document.getElementById('filter-nivel');
    const rolSelect      = document.getElementById('filter-rol');
    const estadoSelect   = document.getElementById('filter-estado');
    const resetBtn       = document.getElementById('btn-reset-filters');
    const resultsCount   = document.getElementById('results-count');
    const tableCount     = document.getElementById('table-count');
    const noResultsMsg   = document.getElementById('no-results-msg');
    const activeFilters  = document.getElementById('active-filters');

    const beltColors = {
        'blanco':   { bg: '#f8fafc', border: '#cbd5e1', text: '#1e293b' },
        'amarillo': { bg: '#fef9c3', border: '#fde047', text: '#a16207' },
        'verde':    { bg: '#dcfce7', border: '#86efac', text: '#166534' },
        'azul':     { bg: '#dbeafe', border: '#93c5fd', text: '#1d4ed8' },
        'rojo':     { bg: '#fee2e2', border: '#fca5a5', text: '#b91c1c' },
        'negro':    { bg: '#1e293b', border: '#334155', text: '#f8fafc' },
        'default':  { bg: '#f1f5f9', border: '#cbd5e1', text: '#475569' },
    };

    const sedeColorPalette = [
        'rgba(37, 99, 235, 0.8)',
        'rgba(220, 38, 38, 0.8)',
        'rgba(250, 204, 21, 0.85)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(139, 92, 246, 0.8)',
        'rgba(249, 115, 22, 0.8)',
        'rgba(14, 165, 233, 0.8)',
    ];

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
    const tickColor = isDark ? '#94a3b8' : '#64748b';

    const ctxCinturones = document.getElementById('chart-cinturones').getContext('2d');
    const ctxSedes      = document.getElementById('chart-sedes').getContext('2d');

    const cinturonChart = new Chart(ctxCinturones, {
        type: 'doughnut',
        data: { labels: [], datasets: [{ data: [], backgroundColor: [], borderColor: [], borderWidth: 2, hoverOffset: 8 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: tickColor, font: { size: 11, weight: '600' }, padding: 14, usePointStyle: true, pointStyleWidth: 10 }
                },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} (${Math.round(ctx.parsed / ctx.dataset.data.reduce((a,b)=>a+b,0) * 100)}%)` } }
            },
            cutout: '60%',
            animation: { animateScale: true, duration: 500 }
        }
    });

    const sedesChart = new Chart(ctxSedes, {
        type: 'bar',
        data: { labels: [], datasets: [{ label: 'Miembros', data: [], backgroundColor: sedeColorPalette, borderRadius: 8, borderSkipped: false }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.raw} miembros` } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 11 } } },
                y: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 11 }, stepSize: 1 }, beginAtZero: true }
            },
            animation: { duration: 500 }
        }
    });

    function applyFilters() {
        const query = filters.search.toLowerCase();
        let visibleRows = [];

        allRows.forEach(row => {
            const nombre = row.dataset.nombre || '';
            const sede   = row.dataset.sede || '';
            const nivel  = row.dataset.nivel || '';
            const rol    = row.dataset.rol || '';
            const estado = row.dataset.estado || '';

            const matchSearch = !query || nombre.includes(query);
            const matchSede   = filters.sede  === 'all' || sede  === filters.sede;
            const matchNivel  = filters.nivel === 'all' || nivel === filters.nivel;
            const matchRol    = filters.rol   === 'all' || rol   === filters.rol;
            const matchEstado = filters.estado === 'all' || estado === filters.estado;

            const visible = matchSearch && matchSede && matchNivel && matchRol && matchEstado;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleRows.push(row);
        });

        updateCounters(visibleRows.length);
        updateCharts(visibleRows);
        updateActiveFiltersBadges();
        noResultsMsg.classList.toggle('hidden', visibleRows.length > 0);
    }

    function updateCounters(count) {
        resultsCount.textContent = count;
        tableCount.textContent   = `${count} resultado${count !== 1 ? 's' : ''}`;
    }

    function updateCharts(visibleRows) {
        // — Cinturones —
        const cinturonMap = {};
        visibleRows.forEach(row => {
            const n = row.dataset.nivel || 'Sin Asignar';
            cinturonMap[n] = (cinturonMap[n] || 0) + 1;
        });

        const cLabels = Object.keys(cinturonMap);
        const cData   = Object.values(cinturonMap);
        const cBg     = cLabels.map(l => {
            const k = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
            return beltColors[k].bg;
        });
        const cBorder = cLabels.map(l => {
            const k = Object.keys(beltColors).find(k => l.toLowerCase().includes(k)) || 'default';
            return beltColors[k].border;
        });

        cinturonChart.data.labels            = cLabels;
        cinturonChart.data.datasets[0].data  = cData;
        cinturonChart.data.datasets[0].backgroundColor = cBg;
        cinturonChart.data.datasets[0].borderColor     = cBorder;
        cinturonChart.update('active');

        const chartCEmpty = document.getElementById('chart-cinturones-empty');
        chartCEmpty.classList.toggle('hidden', cData.length > 0);
        document.getElementById('chart-cinturones-total').textContent = cData.length > 0 ? `Total: ${cData.reduce((a,b)=>a+b,0)}` : '';

        // — Sedes —
        const sedeMap = {};
        visibleRows.forEach(row => {
            const s = row.dataset.sede || 'Sin Asignar';
            sedeMap[s] = (sedeMap[s] || 0) + 1;
        });

        const sLabels = Object.keys(sedeMap);
        const sData   = Object.values(sedeMap);

        sedesChart.data.labels           = sLabels;
        sedesChart.data.datasets[0].data = sData;
        sedesChart.data.datasets[0].backgroundColor = sedeColorPalette.slice(0, sLabels.length);
        sedesChart.update('active');

        const chartSEmpty = document.getElementById('chart-sedes-empty');
        chartSEmpty.classList.toggle('hidden', sData.length > 0);
        document.getElementById('chart-sedes-total').textContent = sData.length > 0 ? `Total: ${sData.reduce((a,b)=>a+b,0)}` : '';
    }

    function updateActiveFiltersBadges() {
        const labels = {
            search: 'Búsqueda', sede: 'Sede', nivel: 'Cinturón', rol: 'Rol', estado: 'Estado'
        };
        const badges = [];
        Object.entries(filters).forEach(([key, val]) => {
            if (val && val !== 'all' && val !== '') {
                badges.push(`<span class="inline-flex items-center gap-1.5 bg-tkd-blue/10 text-tkd-blue text-xs font-semibold px-3 py-1 rounded-full border border-tkd-blue/20">
                    ${labels[key]}: ${val}
                    <button onclick="clearFilter('${key}')" class="hover:text-blue-800 transition-colors focus:outline-none">
                        <span class="material-icons-outlined text-xs leading-none">close</span>
                    </button>
                </span>`);
            }
        });
        activeFilters.innerHTML = badges.join('');
        activeFilters.classList.toggle('hidden', badges.length === 0);
    }

    window.clearFilter = function(key) {
        filters[key] = key === 'search' ? '' : 'all';
        if (key === 'search') searchInput.value = '';
        else document.getElementById('filter-' + key).value = 'all';
        applyFilters();
    };

    searchInput.addEventListener('input',  e => { filters.search = e.target.value; applyFilters(); });
    sedeSelect.addEventListener('change',  e => { filters.sede   = e.target.value; applyFilters(); });
    nivelSelect.addEventListener('change', e => { filters.nivel  = e.target.value; applyFilters(); });
    rolSelect.addEventListener('change',   e => { filters.rol    = e.target.value; applyFilters(); });
    estadoSelect.addEventListener('change',e => { filters.estado = e.target.value; applyFilters(); });

    resetBtn.addEventListener('click', () => {
        filters.search = ''; filters.sede = 'all'; filters.nivel = 'all'; filters.rol = 'all'; filters.estado = 'all';
        searchInput.value = ''; sedeSelect.value = 'all'; nivelSelect.value = 'all'; rolSelect.value = 'all'; estadoSelect.value = 'all';
        applyFilters();
    });

    applyFilters();
});
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

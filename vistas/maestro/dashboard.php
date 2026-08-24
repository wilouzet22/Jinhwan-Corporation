<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">

    <!-- Instructor Hero Banner -->
    <div class="bg-gradient-to-r from-purple-950 via-slate-900 to-indigo-950 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-purple-900/40">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-bold tracking-wider uppercase border border-purple-500/30">
                    <span class="pulse-dot bg-purple-400"></span>
                    <span>Panel de Instructor / Maestro</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white">¡Hola, Sabonim!</h1>
                <p class="text-slate-300 text-sm max-w-xl">Supervisa el avance de tus alumnos, propone grados de ascenso y revisa el calendario.</p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-200 flex items-center gap-2 border border-white/15 shadow-inner">
                    <span class="material-icons-outlined text-purple-400 text-lg">calendar_today</span>
                    <?= date('d M, Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover-lift hover-glow-purple transition-all shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-tkd-purple shrink-0 border border-purple-100 dark:border-purple-500/20 shadow-inner">
                <span class="material-icons-outlined text-3xl">people</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Deportistas</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white"><?= $stats['total_alumnos'] ?></h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover-lift hover:border-amber-400 transition-all shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-500 shrink-0 border border-amber-100 dark:border-amber-500/20 shadow-inner">
                <span class="material-icons-outlined text-3xl">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Ascensos Pendientes</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white"><?= $stats['solicitudes_pendientes'] ?></h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover-lift hover-glow-emerald transition-all shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-500 shrink-0 border border-emerald-100 dark:border-emerald-500/20 shadow-inner">
                <span class="material-icons-outlined text-3xl">task_alt</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Ascensos Aprobados</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white"><?= $stats['solicitudes_aprobadas'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Chart & Actions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-purple text-xl">pie_chart</span>
                    Distribución de Cinturones
                </h3>
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-500/10 px-3 py-1 rounded-full border border-purple-200 dark:border-purple-500/20">
                    <?= array_sum(array_column($distribucion_grados, 'cantidad')) ?> Deportistas
                </span>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-6 flex-grow">
                <!-- Doughnut Canvas -->
                <div class="w-full sm:w-1/2 h-64 relative flex items-center justify-center">
                    <canvas id="gradosChart"></canvas>
                </div>
                
                <!-- Custom HTML Legend -->
                <div class="w-full sm:w-1/2 max-h-64 overflow-y-auto pr-1 space-y-2 custom-scrollbar" id="gradosLegend">
                    <!-- Loaded dynamically via JavaScript -->
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col">
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Acciones del Instructor</h3>
            <div class="space-y-4 flex-1 flex flex-col justify-center">
                <a href="<?= base_url('/maestro/alumnos') ?>" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-purple-50 dark:hover:bg-purple-950/30 hover:border-purple-300 dark:hover:border-purple-800 transition-all hover-lift group">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-tkd-purple group-hover:scale-110 transition-transform shrink-0 border border-purple-100 dark:border-purple-500/20">
                        <span class="material-icons-outlined text-2xl">people</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-tkd-purple transition-colors">Ver Mis Alumnos</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Consulta deportistas y propone ascensos de grado</p>
                    </div>
                </a>
                <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-purple-50 dark:hover:bg-purple-950/30 hover:border-purple-300 dark:hover:border-purple-800 transition-all hover-lift group">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform shrink-0 border border-amber-100 dark:border-amber-500/20">
                        <span class="material-icons-outlined text-2xl">timeline</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-600 transition-colors">Historial de Solicitudes</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Estado de tus propuestas enviadas a administración</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-purple text-xl">sports_martial_arts</span>
                    Deportistas Registrados Recientemente
                </h3>
                <a href="<?= base_url('/maestro/alumnos') ?>" class="text-tkd-purple hover:text-purple-700 dark:hover:text-purple-400 text-xs font-bold uppercase tracking-wider transition-colors">Ver todos →</a>
            </div>
            <div class="overflow-x-auto flex-grow">
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
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-tkd-purple to-purple-800 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                                            <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                        </div>
                                        <span class="font-bold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                    <?= $miembro['fecha'] ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($miembro['activo']): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-400 uppercase tracking-wider border border-emerald-500/20">
                                            Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-400 uppercase tracking-wider border border-amber-500/20">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-purple-500 text-xl">event</span>
                    Próximos Eventos
                </h3>
                <a href="<?= base_url('/usuario/calendario') ?>" class="text-tkd-purple hover:text-purple-700 text-xs font-bold uppercase tracking-wider transition-colors">Ver calendario →</a>
            </div>
            <div class="p-6 divide-y divide-slate-100 dark:divide-slate-800/80 flex-grow">
                <?php if (!empty($proximos_eventos)): ?>
                    <?php foreach($proximos_eventos as $evento): ?>
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-4">
                            <div class="bg-purple-50 dark:bg-purple-500/10 text-tkd-purple dark:text-purple-400 w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 border border-purple-200 dark:border-purple-500/20 shadow-sm">
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
                    <p class="text-sm text-slate-500 dark:text-slate-400 py-4 text-center">No hay eventos próximos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = () => document.documentElement.classList.contains('dark');
        
        const getBeltColor = (nombre) => {
            const n = (nombre || '').toLowerCase();
            if (n.includes('blanco')) return '#f8fafc';
            if (n.includes('pinta amarillo') || n.includes('punta amarillo')) return '#fef08a';
            if (n.includes('amarillo')) return '#eab308';
            if (n.includes('pinta verde') || n.includes('punta verde')) return '#86efac';
            if (n.includes('verde')) return '#22c55e';
            if (n.includes('pinta azul') || n.includes('punta azul')) return '#93c5fd';
            if (n.includes('azul')) return '#3b82f6';
            if (n.includes('pinta rojo') || n.includes('punta rojo')) return '#fca5a5';
            if (n.includes('rojo')) return '#ef4444';
            if (n.includes('pinta negro') || n.includes('punta negro')) return '#c084fc';
            if (n.includes('negro')) return '#334155';
            return '#64748b';
        };

        const rawGradosData = <?= json_encode($distribucion_grados) ?> || [];
        let gradosData = rawGradosData.filter(d => parseInt(d.cantidad) > 0);
        if (gradosData.length === 0) {
            gradosData = rawGradosData;
        }

        const totalDeportistas = gradosData.reduce((sum, item) => sum + parseInt(item.cantidad), 0);
        const ctxGrados = document.getElementById('gradosChart');
        const legendContainer = document.getElementById('gradosLegend');
        
        if (ctxGrados && gradosData.length > 0) {
            const backgroundColors = gradosData.map(d => getBeltColor(d.nombre));

            const chart = new Chart(ctxGrados, {
                type: 'doughnut',
                data: {
                    labels: gradosData.map(d => d.nombre),
                    datasets: [{
                        data: gradosData.map(d => parseInt(d.cantidad)),
                        backgroundColor: backgroundColors,
                        borderColor: isDarkMode() ? '#0f172a' : '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed;
                                    const pct = totalDeportistas > 0 ? ((val / totalDeportistas) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${val} deportista(s) (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });

            if (legendContainer) {
                legendContainer.innerHTML = gradosData.map((item) => {
                    const cant = parseInt(item.cantidad);
                    const pct = totalDeportistas > 0 ? ((cant / totalDeportistas) * 100).toFixed(0) : 0;
                    const color = getBeltColor(item.nombre);
                    const isWhite = (item.nombre || '').toLowerCase().includes('blanco');
                    const dotBorder = isWhite ? 'border border-slate-300 dark:border-slate-600' : '';
                    
                    return `
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/90 dark:bg-slate-950/70 border border-slate-100 dark:border-slate-800/80 text-xs transition-all hover:bg-slate-100 dark:hover:bg-slate-800/60">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-3.5 h-3.5 rounded-full shrink-0 ${dotBorder}" style="background-color: ${color}; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                <span class="font-bold text-slate-800 dark:text-slate-200 truncate" title="${item.nombre}">${item.nombre}</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-2">
                                <span class="font-extrabold text-slate-900 dark:text-white px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg shadow-sm">${cant}</span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase min-w-[32px] text-right">${pct}%</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            window.addEventListener('themeChanged', () => {
                chart.data.datasets[0].borderColor = isDarkMode() ? '#0f172a' : '#ffffff';
                chart.update();
            });
        }
    });
</script>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>



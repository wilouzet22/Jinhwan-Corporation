<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">

    <!-- Top Executive Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950/80 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-400 text-xs font-semibold tracking-wider uppercase border border-blue-500/30">
                    <span class="pulse-dot"></span>
                    <span>Panel Administrador</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white">Centro de Control</h1>
                <p class="text-slate-300 text-sm max-w-xl">Supervisa actividades, aprueba registros y gestiona sedes y miembros en tiempo real.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                <form action="<?= base_url('/admin/miembros') ?>" method="GET" class="relative min-w-[260px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-lg">search</span>
                    </div>
                    <input type="text" name="search" placeholder="Buscar miembro..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/10 dark:bg-black/30 backdrop-blur-md border border-white/15 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-tkd-blue transition-all">
                </form>
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-200 flex items-center gap-2 border border-white/15 shrink-0 justify-center">
                    <span class="material-icons-outlined text-blue-400 text-lg">today</span>
                    <?= date('d M, Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Cards for Pending Actions -->
    <?php if ($stats['pendientes'] > 0 || $stats['pendientes_ascenso'] > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if ($stats['pendientes'] > 0): ?>
        <div class="bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-transparent dark:from-amber-500/20 dark:via-slate-900 dark:to-slate-900 border border-amber-500/30 p-5 rounded-2xl flex items-center justify-between shadow-md hover-lift transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-500 shrink-0">
                    <span class="material-icons-outlined text-2xl">person_add</span>
                </div>
                <div>
                    <h3 class="text-amber-900 dark:text-amber-300 font-bold text-base flex items-center gap-2">
                        Solicitudes de Ingreso
                        <span class="px-2 py-0.5 rounded-full text-xs bg-amber-500/20 text-amber-600 dark:text-amber-400 font-extrabold"><?= $stats['pendientes'] ?></span>
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-0.5">Nuevas solicitudes de registro pendientes de aprobación.</p>
                </div>
            </div>
            <a href="<?= base_url('/admin/registros') ?>" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs uppercase tracking-wider transition-all shadow-md hover:shadow-amber-500/20 shrink-0 ml-3">Revisar</a>
        </div>
        <?php endif; ?>


    </div>
    <?php endif; ?>

    <!-- Quick Action Cards Grid -->
    <div>
        <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <a href="<?= base_url('/admin/miembros') ?>" class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover-lift hover-glow-blue transition-all shadow-sm group">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-tkd-blue group-hover:scale-110 transition-transform mb-4 border border-blue-100 dark:border-blue-500/20 shadow-inner">
                    <span class="material-icons-outlined text-3xl">people</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-tkd-blue transition-colors">Gestionar Miembros</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium"><?= $stats['activos'] ?> activos registrados</p>
            </a>

            <a href="<?= base_url('/admin/sedes') ?>" class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover-lift hover-glow-purple transition-all shadow-sm group">
                <div class="w-16 h-16 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform mb-4 border border-purple-100 dark:border-purple-500/20 shadow-inner">
                    <span class="material-icons-outlined text-3xl">store</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Administrar Sedes</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium"><?= $stats['total_sedes'] ?> sedes activas</p>
            </a>

            <a href="<?= base_url('/admin/calendario') ?>" class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover-lift hover-glow-emerald transition-all shadow-sm group">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform mb-4 border border-emerald-100 dark:border-emerald-500/20 shadow-inner">
                    <span class="material-icons-outlined text-3xl">event</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Calendario</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Programar eventos</p>
            </a>

            <a href="<?= base_url('/admin/ascensos') ?>" class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover-lift hover:border-amber-500 transition-all shadow-sm group">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform mb-4 border border-amber-100 dark:border-amber-500/20 shadow-inner">
                    <span class="material-icons-outlined text-3xl">school</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Módulo Teoría</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Temarios de ascensos</p>
            </a>
        </div>
    </div>

    <!-- React Interactive Dashboard Charts -->
    <div 
        data-react-component="DashboardCharts"
        data-props='<?= json_encode([
            'distribucionGrados' => $distribucion_grados ?? [],
            'distribucionSedes' => $distribucion_sedes ?? []
        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'
    ></div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-blue text-xl">group_add</span>
                    Últimos Registros
                </h3>
                <a href="<?= base_url('/admin/miembros') ?>" class="text-tkd-blue hover:text-blue-700 text-xs font-bold uppercase tracking-wider transition-colors">Ver todos →</a>
            </div>
            <div class="overflow-x-auto flex-grow">
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
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-tkd-blue to-blue-700 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
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
                    <span class="material-icons-outlined text-emerald-500 text-xl">event</span>
                    Próximos Eventos
                </h3>
                <a href="<?= base_url('/admin/calendario') ?>" class="text-tkd-blue hover:text-blue-700 text-xs font-bold uppercase tracking-wider transition-colors">Ver calendario →</a>
            </div>
            <div class="p-6 divide-y divide-slate-100 dark:divide-slate-800/80 flex-grow">
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
                    <p class="text-sm text-slate-500 dark:text-slate-400 py-4 text-center">No hay eventos próximos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>


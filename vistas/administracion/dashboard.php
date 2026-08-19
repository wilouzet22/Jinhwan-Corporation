<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Panel de Control</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Centro de acción y métricas rápidas</p>
        </div>

        <div class="flex-grow max-w-md mx-auto md:mx-0 w-full">
            <form action="<?= base_url('/admin/miembros') ?>" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-slate-400">search</span>
                </div>
                <input type="text" name="search" placeholder="Buscar miembro por nombre o documento..." 
                       class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg leading-5 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-tkd-blue focus:border-tkd-blue sm:text-sm transition-colors duration-300">
            </form>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-slate-900 px-4 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 flex items-center gap-2 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-300">
                <span class="material-icons-outlined text-sm">calendar_today</span>
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>

    <?php if ($stats['pendientes'] > 0 || $stats['pendientes_ascenso'] > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if ($stats['pendientes'] > 0): ?>
        <div class="bg-amber-50 dark:bg-amber-500/10 border-l-4 border-amber-500 p-4 rounded-r-xl flex items-start justify-between shadow-sm">
            <div class="flex items-start gap-4">
                <span class="material-icons-outlined text-amber-600 dark:text-amber-500 text-3xl mt-1">person_add</span>
                <div>
                    <h3 class="text-amber-800 dark:text-amber-400 font-bold text-lg">Solicitudes de Ingreso</h3>
                    <p class="text-amber-700 dark:text-amber-500/80 text-sm mt-1">Tienes <strong><?= $stats['pendientes'] ?></strong> solicitudes de registro esperando revisión.</p>
                </div>
            </div>
            <a href="<?= base_url('/admin/registros') ?>" class="px-4 py-2 bg-amber-100 hover:bg-amber-200 dark:bg-amber-500/20 dark:hover:bg-amber-500/30 text-amber-700 dark:text-amber-400 font-semibold rounded-lg text-sm transition-colors shrink-0">Revisar</a>
        </div>
        <?php endif; ?>

        <?php if ($stats['pendientes_ascenso'] > 0): ?>
        <div class="bg-blue-50 dark:bg-blue-500/10 border-l-4 border-blue-500 p-4 rounded-r-xl flex items-start justify-between shadow-sm">
            <div class="flex items-start gap-4">
                <span class="material-icons-outlined text-blue-600 dark:text-blue-500 text-3xl mt-1">military_tech</span>
                <div>
                    <h3 class="text-blue-800 dark:text-blue-400 font-bold text-lg">Solicitudes de Ascenso</h3>
                    <p class="text-blue-700 dark:text-blue-500/80 text-sm mt-1">Tienes <strong><?= $stats['pendientes_ascenso'] ?></strong> propuestas de ascenso pendientes.</p>
                </div>
            </div>
            <a href="<?= base_url('/admin/registros') ?>" class="px-4 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-500/20 dark:hover:bg-blue-500/30 text-blue-700 dark:text-blue-400 font-semibold rounded-lg text-sm transition-colors shrink-0">Revisar</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div>
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <a href="<?= base_url('/admin/miembros') ?>" class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover:border-tkd-blue dark:hover:border-tkd-blue transition-colors shadow-sm duration-300 group">
                <div class="w-14 h-14 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-500/10 group-hover:text-tkd-blue transition-colors mb-3">
                    <span class="material-icons-outlined text-3xl">people</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-tkd-blue transition-colors">Gestionar Miembros</h3>
                <p class="text-xs text-slate-500 mt-1"><?= $stats['activos'] ?> activos registrados</p>
            </a>

            <a href="<?= base_url('/admin/sedes') ?>" class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover:border-purple-500 dark:hover:border-purple-500 transition-colors shadow-sm duration-300 group">
                <div class="w-14 h-14 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-purple-50 dark:group-hover:bg-purple-500/10 group-hover:text-purple-500 transition-colors mb-3">
                    <span class="material-icons-outlined text-3xl">store</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-purple-500 transition-colors">Administrar Sedes</h3>
                <p class="text-xs text-slate-500 mt-1"><?= $stats['total_sedes'] ?> sedes habilitadas</p>
            </a>

            <a href="<?= base_url('/admin/calendario') ?>" class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors shadow-sm duration-300 group">
                <div class="w-14 h-14 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-500/10 group-hover:text-emerald-500 transition-colors mb-3">
                    <span class="material-icons-outlined text-3xl">event</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-500 transition-colors">Calendario</h3>
                <p class="text-xs text-slate-500 mt-1">Crear o editar eventos</p>
            </a>

            <a href="<?= base_url('/admin/ascensos') ?>" class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center hover:border-amber-500 dark:hover:border-amber-500 transition-colors shadow-sm duration-300 group">
                <div class="w-14 h-14 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-amber-50 dark:group-hover:bg-amber-500/10 group-hover:text-amber-500 transition-colors mb-3">
                    <span class="material-icons-outlined text-3xl">school</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-amber-500 transition-colors">Módulo Teoría</h3>
                <p class="text-xs text-slate-500 mt-1">Contenido de ascensos</p>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300 flex flex-col">
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Últimos Registros</h3>
                <a href="<?= base_url('/admin/miembros') ?>" class="text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 text-xs font-semibold transition-colors">Ver todos</a>
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

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300 flex flex-col">
            <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Próximos Eventos</h3>
                <a href="<?= base_url('/admin/calendario') ?>" class="text-tkd-blue hover:text-blue-700 text-xs font-semibold transition-colors">Ver calendario</a>
            </div>
            <div class="p-5 divide-y divide-slate-100 dark:divide-slate-800/80 flex-grow">
                <?php if (!empty($proximos_eventos)): ?>
                    <?php foreach($proximos_eventos as $evento): ?>
                        <div class="py-3 first:pt-0 last:pb-0 flex items-center gap-4">
                            <div class="bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 w-12 h-12 rounded-lg flex flex-col items-center justify-center shrink-0 border border-blue-100 dark:border-blue-500/20">
                                <span class="text-[10px] font-bold uppercase tracking-wider"><?= date('M', strtotime($evento['start'])) ?></span>
                                <span class="text-lg font-black leading-none"><?= date('d', strtotime($evento['start'])) ?></span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($evento['title']) ?></h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1">
                                    <span class="material-icons-outlined text-[13px]">schedule</span>
                                    <?= date('h:i A', strtotime($evento['start'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-slate-500 dark:text-slate-400 py-2">No hay eventos próximos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

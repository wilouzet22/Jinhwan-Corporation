<?php 
$current_page = 'notificaciones';
include __DIR__ . '/../layout/administracion_cabecera.php'; 
?>

<main class="flex-grow container mx-auto p-6 lg:p-10 space-y-8 animate-fade-in-up">

    <!-- Header Principal -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 p-6 md:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold tracking-wider uppercase border border-amber-500/30">
                    <span class="material-icons-outlined text-sm">notifications_active</span>
                    <span>Centro de Notificaciones</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-white">Actividad del Software</h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Supervisa en tiempo real las caracterizaciones de estudiantes (nuevos y existentes), registros pendientes, solicitudes de ascenso y avisos del sistema.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <?php if ($noLeidasCount > 0): ?>
                <form method="POST" action="<?= base_url('/admin/notificaciones/marcar-todas') ?>">
                    <button type="submit" class="bg-white/10 hover:bg-white/20 backdrop-blur-md px-4 py-2.5 rounded-xl text-xs font-bold text-white flex items-center gap-2 border border-white/20 transition shadow cursor-pointer">
                        <span class="material-icons-outlined text-base">done_all</span>
                        Marcar todas leídas
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito si aplica -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'todas_leidas'): ?>
    <div id="alert-leidas" class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-300 dark:border-emerald-700/60 rounded-2xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3 text-emerald-800 dark:text-emerald-200 text-sm font-semibold">
            <span class="material-icons-outlined text-emerald-500">check_circle</span>
            Todas las notificaciones han sido marcadas como leídas.
        </div>
        <button onclick="document.getElementById('alert-leidas').remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-tkd-blue flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-xl">mark_email_unread</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">No Leídas</p>
                <p class="text-xl font-extrabold text-slate-900 dark:text-white"><?= (int)$noLeidasCount ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-xl">assignment_ind</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Caracterizaciones</p>
                <p class="text-xl font-extrabold text-slate-900 dark:text-white"><?= (int)$conteoCarac ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-xl">person_add</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Registros</p>
                <p class="text-xl font-extrabold text-slate-900 dark:text-white"><?= (int)$conteoReg ?></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-500 flex items-center justify-center shrink-0">
                <span class="material-icons-outlined text-xl">military_tech</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ascensos</p>
                <p class="text-xl font-extrabold text-slate-900 dark:text-white"><?= (int)$conteoAsc ?></p>
            </div>
        </div>
    </div>

    <!-- Pestañas de Filtro -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 dark:border-slate-800 text-sm">
        <a href="<?= base_url('/admin/notificaciones?tipo=todas') ?>" 
           class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap <?= $tipoActivo === 'todas' ? 'bg-tkd-blue text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            Todas (<?= (int)$conteoTotal ?>)
        </a>
        <a href="<?= base_url('/admin/notificaciones?tipo=caracterizacion_nueva') ?>" 
           class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap flex items-center gap-1.5 <?= $tipoActivo === 'caracterizacion_nueva' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <span>✨</span> Nuevos Alumnos
        </a>
        <a href="<?= base_url('/admin/notificaciones?tipo=caracterizacion_update') ?>" 
           class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap flex items-center gap-1.5 <?= $tipoActivo === 'caracterizacion_update' ? 'bg-cyan-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <span>📝</span> Datos Actualizados
        </a>
        <a href="<?= base_url('/admin/notificaciones?tipo=registro') ?>" 
           class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap flex items-center gap-1.5 <?= $tipoActivo === 'registro' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <span>👤</span> Cuentas Web
        </a>
        <a href="<?= base_url('/admin/notificaciones?tipo=ascenso') ?>" 
           class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap flex items-center gap-1.5 <?= $tipoActivo === 'ascenso' ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
            <span>🥋</span> Ascensos
        </a>
    </div>

    <!-- Lista de Notificaciones -->
    <div class="space-y-3">
        <?php if (!empty($notificaciones)): ?>
            <?php foreach ($notificaciones as $n): ?>
                <?php
                    // Configuración visual por tipo
                    $tipo = $n['tipo'];
                    $icon = 'notifications';
                    $badgeText = 'Sistema';
                    $badgeColor = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
                    $iconBg = 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400';

                    if ($tipo === 'caracterizacion_nueva') {
                        $icon = 'assignment_ind';
                        $badgeText = 'Nuevo Alumno (Caracterización)';
                        $badgeColor = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
                        $iconBg = 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600';
                    } elseif ($tipo === 'caracterizacion_update') {
                        $icon = 'edit_note';
                        $badgeText = 'Actualización de Caracterización';
                        $badgeColor = 'bg-cyan-100 text-cyan-800 dark:bg-cyan-950/60 dark:text-cyan-300 border-cyan-300 dark:border-cyan-800';
                        $iconBg = 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600';
                    } elseif ($tipo === 'registro') {
                        $icon = 'person_add';
                        $badgeText = 'Cuenta Web Registrada';
                        $badgeColor = 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border-amber-300 dark:border-amber-800';
                        $iconBg = 'bg-amber-50 dark:bg-amber-500/10 text-amber-500';
                    } elseif ($tipo === 'ascenso') {
                        $icon = 'military_tech';
                        $badgeText = 'Solicitud de Ascenso';
                        $badgeColor = 'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 border-purple-300 dark:border-purple-800';
                        $iconBg = 'bg-purple-50 dark:bg-purple-500/10 text-purple-500';
                    }

                    $esNueva = (int)$n['leida'] === 0;
                ?>
                <div class="bg-white dark:bg-slate-900 border rounded-2xl p-5 shadow-sm transition-all hover:shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 <?= $esNueva ? 'border-amber-300 dark:border-amber-700/60 bg-amber-50/20 dark:bg-amber-950/10' : 'border-slate-200 dark:border-slate-800' ?>">
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl <?= $iconBg ?> flex items-center justify-center shrink-0 shadow-inner">
                            <span class="material-icons-outlined text-2xl"><?= $icon ?></span>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border <?= $badgeColor ?>">
                                    <?= $badgeText ?>
                                </span>
                                <?php if ($esNueva): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500 text-white shadow-xs animate-pulse">
                                        Nueva
                                    </span>
                                <?php endif; ?>
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <span class="material-icons-outlined text-xs">schedule</span>
                                    <?= htmlspecialchars($n['created_at']) ?>
                                </span>
                            </div>

                            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">
                                <?= htmlspecialchars($n['titulo']) ?>
                            </h3>

                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed max-w-3xl">
                                <?= htmlspecialchars($n['mensaje']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2 shrink-0 md:self-center pt-2 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800">
                        <?php if (!empty($n['enlace'])): ?>
                            <a href="<?= base_url($n['enlace']) ?>" 
                               class="px-4 py-2 rounded-xl bg-tkd-blue hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider transition shadow flex items-center gap-1.5 whitespace-nowrap">
                                <span>Ver Detalle</span>
                                <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </a>
                        <?php endif; ?>

                        <?php if ($esNueva): ?>
                        <form method="POST" action="<?= base_url('/admin/notificaciones/marcar-leida') ?>">
                            <input type="hidden" name="id" value="<?= (int)$n['id_notificacion'] ?>">
                            <button type="submit" 
                                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xl transition" 
                                    title="Marcar como leída">
                                <span class="material-icons-outlined text-xl">done</span>
                            </button>
                        </form>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('/admin/notificaciones/eliminar') ?>" onsubmit="return confirm('¿Eliminar esta notificación?');">
                            <input type="hidden" name="id" value="<?= (int)$n['id_notificacion'] ?>">
                            <button type="submit" 
                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition" 
                                    title="Eliminar notificación">
                                <span class="material-icons-outlined text-xl">delete_outline</span>
                            </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-sm">
                <span class="material-icons-outlined text-5xl text-slate-300 dark:text-slate-700 mb-3">notifications_paused</span>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">No hay notificaciones en este filtro</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Cuando los alumnos realicen caracterizaciones, registros o ascensos aparecerán aquí.</p>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

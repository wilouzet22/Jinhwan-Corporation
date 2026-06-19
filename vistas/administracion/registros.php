<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 relative overflow-hidden transition-colors duration-300">

    <div class="flex justify-between items-center mb-10 relative z-10">
        <div>
            <h1 class="text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Bandeja de Solicitudes</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-light transition-colors">Gestiona los nuevos registros de deportistas y las propuestas de ascenso enviadas por los maestros.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-8 p-5 rounded-2xl border animate-fade-in flex items-center gap-3 relative z-10 transition-colors shadow-sm
            <?= ($_GET['msg'] == 'approved' || $_GET['msg'] == 'promo_approved') 
                ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50' 
                : 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50' ?>">
            <span class="material-icons-outlined">
                <?= ($_GET['msg'] == 'approved' || $_GET['msg'] == 'promo_approved') ? 'check_circle' : 'delete_sweep' ?>
            </span>
            <p class="font-semibold text-sm">
                <?php
                    if ($_GET['msg'] == 'approved') echo '¡Solicitud de registro aprobada correctamente! El usuario ya puede iniciar sesión.';
                    elseif ($_GET['msg'] == 'rejected') echo 'La solicitud de registro ha sido rechazada y eliminada.';
                    elseif ($_GET['msg'] == 'promo_approved') echo '¡Ascenso de grado aprobado con éxito! El alumno ha sido promovido.';
                    elseif ($_GET['msg'] == 'promo_rejected') echo 'La propuesta de ascenso ha sido rechazada.';
                ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- TABS OR MULTIPLE CARDS -->
    <div class="space-y-12">
        
        <!-- SECTION 1: REGISTRO DE CUENTAS PENDIENTES -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-tkd-blue">how_to_reg</span>
                    Solicitudes de Registro (Nuevas Cuentas)
                </h2>
                <span class="px-2.5 py-1 text-xs font-bold bg-blue-50 dark:bg-tkd-blue/10 text-tkd-blue rounded-full">
                    <?= count($solicitudes) ?> pendientes
                </span>
            </div>
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden relative z-10 shadow-sm transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 transition-colors">
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Nombre Completo</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Identificación</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Contacto</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] text-center transition-colors">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                            <?php if (empty($solicitudes)): ?>
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/85 rounded-full flex items-center justify-center mb-3 text-slate-400 dark:text-slate-500 transition-colors">
                                                <span class="material-icons-outlined text-3xl">inbox</span>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium transition-colors">No hay solicitudes de registro pendientes</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($solicitudes as $req): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800/80 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold text-base transition-colors shrink-0">
                                                    <?= strtoupper(substr($req['nombre'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-900 dark:text-white transition-colors"><?= htmlspecialchars($req['nombre'] . ' ' . $req['apellido']) ?></p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium transition-colors"><?= htmlspecialchars($req['correo']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-900/60 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800/80 transition-colors">
                                                <?= htmlspecialchars($req['num_doc']) ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-slate-600 dark:text-slate-300 transition-colors">
                                            <div class="flex flex-col gap-1">
                                                <p class="text-sm font-medium flex items-center gap-2">
                                                    <span class="material-icons-outlined text-xs text-slate-400 dark:text-slate-500">phone</span>
                                                    <?= htmlspecialchars($req['telefono']) ?>
                                                </p>
                                                <p class="text-xs text-slate-500 flex items-center gap-2">
                                                    <span class="material-icons-outlined text-xs">cake</span>
                                                    <?= htmlspecialchars($req['fecha_n']) ?>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center justify-center gap-3">
                                                <form action="<?= base_url('/admin/registros/aprobar') ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white p-2 rounded-xl shadow-md hover:shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none" title="Aceptar Registro">
                                                        <span class="material-icons-outlined block text-sm">check</span>
                                                    </button>
                                                </form>
                                                <form action="<?= base_url('/admin/registros/rechazar') ?>" method="POST" onsubmit="return confirm('¿Está seguro de rechazar y borrar esta solicitud?');">
                                                    <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-400 text-white p-2 rounded-xl shadow-md hover:shadow-red-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none" title="Rechazar y Borrar">
                                                        <span class="material-icons-outlined block text-sm">close</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION 2: SOLICITUDES DE ASCENSO DE GRADO -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-icons-outlined text-purple-600">arrow_upward</span>
                    Solicitudes de Ascenso (Propuestas de Maestros)
                </h2>
                <span class="px-2.5 py-1 text-xs font-bold bg-purple-50 dark:bg-purple-950/30 text-purple-600 rounded-full">
                    <?= count($solicitudes_ascenso) ?> pendientes
                </span>
            </div>
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden relative z-10 shadow-sm transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 transition-colors">
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Deportista</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Propuesto por</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] text-center transition-colors">Cambio de Grado</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] transition-colors">Observaciones</th>
                                <th class="px-8 py-5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] text-center transition-colors">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                            <?php if (empty($solicitudes_ascenso)): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/85 rounded-full flex items-center justify-center mb-3 text-slate-400 dark:text-slate-500 transition-colors">
                                                <span class="material-icons-outlined text-3xl">inbox</span>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium transition-colors">No hay propuestas de ascenso pendientes</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($solicitudes_ascenso as $s): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors group">
                                        <td class="px-8 py-6">
                                            <p class="font-bold text-slate-900 dark:text-white transition-colors"><?= htmlspecialchars($s['nombre_alumno'] . ' ' . $s['apellido_alumno']) ?></p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium transition-colors">Solicitado: <?= date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) ?></p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-purple-600">
                                                <span class="material-icons-outlined text-xs">school</span>
                                                <?= htmlspecialchars($s['nombre_maestro'] . ' ' . $s['apellido_maestro']) ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center justify-center gap-2 text-xs font-bold">
                                                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700"><?= htmlspecialchars($s['grado_actual']) ?></span>
                                                <span class="material-icons-outlined text-slate-450 text-sm">arrow_forward</span>
                                                <span class="px-2.5 py-1 rounded bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-450 border border-purple-200 dark:border-purple-800"><?= htmlspecialchars($s['grado_solicitado']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-slate-650 dark:text-slate-350 text-xs max-w-[200px] truncate" title="<?= htmlspecialchars($s['observaciones'] ?? '') ?>">
                                            <?= htmlspecialchars($s['observaciones'] ?? 'Sin justificación') ?>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center justify-center gap-3">
                                                <form action="<?= base_url('/admin/registros/aprobar-ascenso') ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                                    <input type="hidden" name="id_miembro" value="<?= $s['id_miembro'] ?>">
                                                    <input type="hidden" name="id_grado_solicitado" value="<?= $s['id_grado_solicitado'] ?>">
                                                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white p-2 rounded-xl shadow-md hover:shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none" title="Aprobar Ascenso">
                                                        <span class="material-icons-outlined block text-sm">check</span>
                                                    </button>
                                                </form>
                                                <form action="<?= base_url('/admin/registros/rechazar-ascenso') ?>" method="POST" onsubmit="return confirm('¿Está seguro de rechazar esta propuesta de ascenso?');">
                                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-400 text-white p-2 rounded-xl shadow-md hover:shadow-red-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 focus:outline-none" title="Rechazar Propuesta">
                                                        <span class="material-icons-outlined block text-sm">close</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out forwards;
    }
</style>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">Solicitudes de Registro</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Gestiona las nuevas peticiones de acceso al sistema</p>
        </div>
        <div class="bg-blue-100 dark:bg-blue-900/30 text-tkd-blue dark:text-blue-400 px-6 py-3 rounded-2xl border border-blue-200 dark:border-blue-800 shadow-sm">
            <span class="font-bold text-lg"><?= count($solicitudes) ?></span>
            <span class="text-xs uppercase tracking-widest ml-1 font-semibold">Pendientes</span>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-8 p-5 rounded-2xl <?= $_GET['msg'] == 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' ?> border animate-fade-in flex items-center gap-3 shadow-sm">
            <span class="material-icons-outlined"><?= $_GET['msg'] == 'approved' ? 'check_circle' : 'delete_sweep' ?></span>
            <p class="font-semibold text-sm">
                <?= $_GET['msg'] == 'approved' ? '¡Solicitud aprobada correctamente! El usuario ya puede iniciar sesión.' : 'La solicitud ha sido rechazada y eliminada del sistema.' ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Nombre Completo</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Identificación</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Contacto</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($solicitudes)): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                        <span class="material-icons-outlined text-4xl">inbox</span>
                                    </div>
                                    <p class="text-slate-400 font-medium">No hay solicitudes pendientes en este momento</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $req): ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center text-slate-500 font-bold text-lg shadow-sm">
                                            <?= strtoupper(substr($req['nombre'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($req['nombre'] . ' ' . $req['apellido']) ?></p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"><?= htmlspecialchars($req['correo']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        <?= htmlspecialchars($req['num_doc']) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                            <span class="material-icons-outlined text-xs text-slate-400">phone</span>
                                            <?= htmlspecialchars($req['telefono']) ?>
                                        </p>
                                        <p class="text-xs text-slate-400 flex items-center gap-2">
                                            <span class="material-icons-outlined text-xs">cake</span>
                                            <?= htmlspecialchars($req['fecha_n']) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <form action="<?= base_url('admin/registros/aprobar') ?>" method="POST">
                                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white p-2.5 rounded-xl shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1 active:scale-95" title="Aceptar Solicitud">
                                                <span class="material-icons-outlined block">check</span>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/registros/rechazar') ?>" method="POST" onsubmit="return confirm('¿Está seguro de rechazar y borrar esta solicitud?');">
                                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-xl shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-1 active:scale-95" title="Rechazar y Borrar">
                                                <span class="material-icons-outlined block">close</span>
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

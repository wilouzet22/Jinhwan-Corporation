<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-10 relative overflow-hidden">
    <!-- Abstract Ambient Glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex justify-between items-center mb-10 relative z-10">
        <div>
            <h1 class="text-4xl font-display font-bold text-white uppercase tracking-tight">Solicitudes de Registro</h1>
            <p class="text-slate-400 mt-2 font-light">Gestiona las nuevas peticiones de acceso al sistema</p>
        </div>
        <div class="bg-tkd-blue/15 text-tkd-blue px-6 py-3 rounded-2xl border border-tkd-blue/30 shadow-lg flex items-center">
            <span class="font-bold text-lg"><?= count($solicitudes) ?></span>
            <span class="text-xs uppercase tracking-widest ml-1 font-semibold">Pendientes</span>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-8 p-5 rounded-2xl <?= $_GET['msg'] == 'approved' ? 'bg-emerald-950/60 text-emerald-400 border-emerald-800/50 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'bg-amber-950/60 text-amber-400 border-amber-800/50 shadow-[0_0_15px_rgba(245,158,11,0.15)]' ?> border animate-fade-in flex items-center gap-3 relative z-10">
            <span class="material-icons-outlined"><?= $_GET['msg'] == 'approved' ? 'check_circle' : 'delete_sweep' ?></span>
            <p class="font-semibold text-sm">
                <?= $_GET['msg'] == 'approved' ? '¡Solicitud aprobada correctamente! El usuario ya puede iniciar sesión.' : 'La solicitud ha sido rechazada y eliminada del sistema.' ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="glass-card rounded-3xl border border-slate-800/80 overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/40 border-b border-slate-800">
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Nombre Completo</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Identificación</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Contacto</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-[0.2em] text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    <?php if (empty($solicitudes)): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-900/60 border border-slate-800/85 rounded-full flex items-center justify-center mb-4 text-slate-500">
                                        <span class="material-icons-outlined text-4xl">inbox</span>
                                    </div>
                                    <p class="text-slate-400 font-medium">No hay solicitudes pendientes en este momento</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitudes as $req): ?>
                            <tr class="hover:bg-slate-900/20 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-center text-slate-300 font-bold text-lg">
                                            <?= strtoupper(substr($req['nombre'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white"><?= htmlspecialchars($req['nombre'] . ' ' . $req['apellido']) ?></p>
                                            <p class="text-xs text-slate-400 mt-0.5 font-light"><?= htmlspecialchars($req['correo']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-slate-900/60 rounded-lg text-xs font-bold text-slate-300 border border-slate-800/80">
                                        <?= htmlspecialchars($req['num_doc']) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-slate-300">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-sm font-light flex items-center gap-2">
                                            <span class="material-icons-outlined text-xs text-slate-500">phone</span>
                                            <?= htmlspecialchars($req['telefono']) ?>
                                        </p>
                                        <p class="text-xs text-slate-500 flex items-center gap-2">
                                            <span class="material-icons-outlined text-xs">cake</span>
                                            <?= htmlspecialchars($req['fecha_n']) ?>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-3 opacity-30 group-hover:opacity-100 transition-opacity">
                                        <form action="<?= base_url('admin/registros/aprobar') ?>" method="POST">
                                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white p-2.5 rounded-xl shadow-lg shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95" title="Aceptar Solicitud">
                                                <span class="material-icons-outlined block">check</span>
                                            </button>
                                        </form>
                                        <form action="<?= base_url('admin/registros/rechazar') ?>" method="POST" onsubmit="return confirm('¿Está seguro de rechazar y borrar esta solicitud?');">
                                            <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white p-2.5 rounded-xl shadow-lg shadow-red-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95" title="Rechazar y Borrar">
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

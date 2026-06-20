<?php include __DIR__ . '/../../layout/administracion_cabecera.php'; ?>

<main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mb-6 p-4 rounded-lg <?= $_SESSION['tipo_mensaje'] === 'success' ? 'bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-400' ?> flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-icons-outlined"><?= $_SESSION['tipo_mensaje'] === 'success' ? 'check_circle' : 'error' ?></span>
                    <p class="font-medium"><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="p-1 hover:bg-black/5 dark:hover:bg-white/5 rounded-md transition-colors">
                    <span class="material-icons-outlined text-sm">close</span>
                </button>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-bold text-2xl md:text-3xl text-slate-900 dark:text-white">Galería (Instagram)</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Gestiona los enlaces de Instagram que se muestran en la galería principal.</p>
            </div>
            <a href="<?= base_url('/admin/galeria/crear') ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-tkd-blue hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                <span class="material-icons-outlined text-[20px]">add</span>
                Añadir Publicación
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 dark:bg-slate-950/50 text-slate-700 dark:text-slate-300 font-semibold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">URL Instagram</th>
                            <th class="px-6 py-4">Descripción</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        <?php if (empty($publicaciones)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                    No hay publicaciones en la galería. <a href="<?= base_url('/admin/galeria/crear') ?>" class="text-tkd-blue hover:underline">Añadir la primera</a>.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($publicaciones as $pub): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">#<?= $pub['id_multimedia'] ?></td>
                                    <td class="px-6 py-4">
                                        <a href="<?= htmlspecialchars($pub['url']) ?>" target="_blank" class="text-tkd-blue hover:underline break-all max-w-xs block truncate" title="<?= htmlspecialchars($pub['url']) ?>">
                                            <?= htmlspecialchars($pub['url']) ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate" title="<?= htmlspecialchars($pub['descripcion']) ?>">
                                        <?= htmlspecialchars($pub['descripcion']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="<?= base_url('/admin/galeria/delete') ?>" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta publicación de la galería?');">
                                            <input type="hidden" name="id_multimedia" value="<?= $pub['id_multimedia'] ?>">
                                            <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                                <span class="material-icons-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../layout/administracion_pie.php'; ?>

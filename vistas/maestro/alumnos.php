<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Mis Alumnos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Listado completo de deportistas de la academia. Puedes proponer ascensos de grado.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nombre</th>
                        <th scope="col" class="px-6 py-4">Documento</th>
                        <th scope="col" class="px-6 py-4">Cinturón Actual</th>
                        <th scope="col" class="px-6 py-4">Sede</th>
                        <th scope="col" class="px-6 py-4">Contacto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all">
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-tkd-purple flex items-center justify-center mr-3 font-bold transition-colors shrink-0">
                                        <?= strtoupper(substr($alumno['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-800 dark:text-white transition-colors"><?= htmlspecialchars($alumno['nombre']) . ' ' . htmlspecialchars($alumno['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-2 py-1 rounded text-slate-500 dark:text-slate-400 mr-1 transition-colors">CC</span>
                                <?= htmlspecialchars($alumno['numero_documento']) ?>
                            </td>
                            <?php
                                $nivel = strtolower($alumno['nombre_nivel'] ?? '');
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
                            ?>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors <?= $beltClass ?>">
                                    <?= htmlspecialchars($alumno['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <div class="flex items-center gap-1 text-sm font-medium">
                                    <span class="material-icons-outlined text-sm text-tkd-purple">place</span>
                                    <?= htmlspecialchars($alumno['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">
                                <div class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($alumno['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]"><?= htmlspecialchars($alumno['correo'] ?? '') ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

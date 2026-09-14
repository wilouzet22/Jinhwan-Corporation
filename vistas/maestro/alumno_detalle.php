<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<?php
    $a = $alumno;
    $nivel = strtolower($a['nombre_nivel'] ?? '');
    $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400';
    if (str_contains($nivel, 'blanco'))       $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-600';
    elseif (str_contains($nivel, 'amarillo')) $beltClass = 'bg-yellow-50 text-yellow-800 border border-yellow-300 dark:bg-yellow-950/50 dark:text-yellow-400';
    elseif (str_contains($nivel, 'verde'))    $beltClass = 'bg-emerald-50 text-emerald-800 border border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-400';
    elseif (str_contains($nivel, 'azul'))     $beltClass = 'bg-blue-50 text-blue-800 border border-blue-300 dark:bg-blue-950/50 dark:text-blue-400';
    elseif (str_contains($nivel, 'rojo'))     $beltClass = 'bg-red-50 text-red-800 border border-red-300 dark:bg-red-950/50 dark:text-red-400';
    elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) $beltClass = 'bg-slate-900 text-amber-400 border border-amber-500/40 dark:bg-slate-950 dark:border-amber-500/50';
?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6 max-w-4xl mx-auto">

        <!-- Encabezado -->
        <div class="flex items-center gap-3">
            <a href="<?= base_url('/maestro/alumnos') ?>" class="inline-flex items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 text-sm font-medium transition-colors">
                <span class="material-icons-outlined text-base">arrow_back</span>
                Volver a Alumnos
            </a>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            
            <!-- Banner + Avatar -->
            <div class="h-28 bg-gradient-to-r from-purple-600 via-indigo-600 to-violet-700 relative">
                <div class="absolute -bottom-10 left-6">
                    <?php if (!empty($a['foto_perfil'])): ?>
                        <img src="<?= base_url('/public/uploads/perfiles/' . $a['foto_perfil']) ?>" 
                             class="w-20 h-20 rounded-2xl object-cover border-4 border-white dark:border-slate-900 shadow-lg">
                    <?php else: ?>
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center font-bold text-2xl border-4 border-white dark:border-slate-900 shadow-lg">
                            <?= strtoupper(substr($a['nombre'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info nombre + cinturón -->
            <div class="pt-14 px-6 pb-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                            <?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?>
                        </h1>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="font-mono text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded text-slate-500 dark:text-slate-400">
                                <?= htmlspecialchars($a['tipo_documento'] ?? 'CC') ?>
                            </span>
                            <span class="text-sm text-slate-600 dark:text-slate-300 font-medium">
                                <?= htmlspecialchars($a['numero_documento'] ?? 'Sin documento') ?>
                            </span>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $beltClass ?>">
                        <?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin Asignar') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Grid de detalles -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Datos de Academia -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 transition-colors">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-2">
                    <span class="material-icons-outlined text-sm text-purple-500">school</span>
                    Datos de Academia
                </h2>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-slate-500 dark:text-slate-400">Sede</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                            <span class="material-icons-outlined text-sm text-purple-500">place</span>
                            <?= htmlspecialchars($a['nombre_sede'] ?? 'Sin Asignar') ?>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Grupo</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['nombre_grupo'] ?? 'Sin Asignar') ?>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Cinturón</dt>
                        <dd>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider <?= $beltClass ?>">
                                <?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin Asignar') ?>
                            </span>
                        </dd>
                    </div>
                    <?php if (!empty($a['nombre_categoria'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Categoría</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['nombre_categoria']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['division'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">División</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['division']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Datos Personales -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 transition-colors">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-2">
                    <span class="material-icons-outlined text-sm text-purple-500">person</span>
                    Datos Personales
                </h2>
                <dl class="space-y-3">
                    <?php if (!empty($a['correo'])): ?>
                    <div class="flex justify-between items-center text-sm">
                        <dt class="text-slate-500 dark:text-slate-400">Correo</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[180px]" title="<?= htmlspecialchars($a['correo']) ?>">
                            <?= htmlspecialchars($a['correo']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['telefono'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Teléfono</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['telefono']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['fecha_nacimiento'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Nacimiento</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= date('d/m/Y', strtotime($a['fecha_nacimiento'])) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['peso'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">Peso</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['peso']) ?> kg
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['eps'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">EPS</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['eps']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($a['rh'])): ?>
                    <div class="flex justify-between items-center text-sm border-t border-slate-100 dark:border-slate-800 pt-3">
                        <dt class="text-slate-500 dark:text-slate-400">RH</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200">
                            <?= htmlspecialchars($a['rh']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>

        </div>

        <!-- Acción: Solicitar Ascenso -->
        <div class="flex justify-end">
            <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-bold uppercase tracking-wider shadow-sm transition-all">
                <span class="material-icons-outlined text-base">trending_up</span>
                Solicitar Ascenso
            </a>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow w-full px-4 py-6 sm:px-6 lg:px-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-3">
                    <span class="material-icons-outlined text-3xl text-tkd-blue">workspace_premium</span>
                    Gestión de Ascensos y Diplomas
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Selecciona uno o varios alumnos, elige el nuevo cinturón y genera todos los diplomas de una sola vez.
                </p>
            </div>
            <button id="btn-abrir-ascenso-masivo" disabled
                    onclick="abrirModalMasivo()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all cursor-pointer">
                <span class="material-icons-outlined text-lg">military_tech</span>
                <span>Ascender Seleccionados (<span id="contador-seleccionados">0</span>)</span>
            </button>
        </div>

        <!-- Alertas de éxito / error -->
        <?php if (isset($_GET['success'])): ?>
        <div class="flex items-center justify-between gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-5 py-4 rounded-xl text-sm font-semibold shadow-xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-outlined text-xl text-emerald-600 dark:text-emerald-400">verified</span>
                <?php if (!empty($_GET['bulk'])): ?>
                    <span>¡<?= (int)$_GET['success'] ?> alumno(s) ascendidos exitosamente! Los certificados han sido generados.</span>
                <?php else: ?>
                    <span>¡Diploma generado exitosamente! El certificado y registro de grado han sido actualizados.</span>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif (isset($_GET['error'])): ?>
        <div class="flex items-center gap-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 px-5 py-4 rounded-xl text-sm font-medium shadow-xs">
            <span class="material-icons-outlined text-xl">error</span>
            <?php
            $err = $_GET['error'];
            if ($err === 'datos_invalidos') echo 'Error: Debes seleccionar al menos un alumno y un grado de destino válido.';
            else echo 'Ocurrió un error al procesar el ascenso. Inténtalo nuevamente.';
            ?>
        </div>
        <?php endif; ?>

        <!-- ========= SECCIÓN: TABLA DE ALUMNOS PARA ASCENDER ========= -->
        <div id="card-alumnos" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base text-blue-600 dark:text-blue-400">groups</span>
                    <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Alumnos Activos — Seleccionar para Ascender
                    </h2>
                    <span id="badge-total-alumnos" class="ml-2 px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-bold border border-blue-200 dark:border-blue-800">
                        <?= count($alumnos) ?>
                    </span>
                </div>
                <!-- Filtros rápidos -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative">
                        <span class="material-icons-outlined absolute left-2.5 top-2 text-slate-400 text-sm pointer-events-none">search</span>
                        <input type="text" id="buscar-alumno" placeholder="Buscar alumno..."
                               class="pl-8 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-blue-500 transition-colors w-44">
                    </div>
                    <select id="filtro-grupo-tabla" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-1.5 px-2.5 focus:outline-none focus:border-blue-500 transition-colors">
                        <option value="">Todos los grupos</option>
                        <?php foreach($grupos_list as $grp): ?>
                            <option value="<?= htmlspecialchars($grp['nombre']) ?>"><?= htmlspecialchars($grp['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button onclick="toggleSelectAllMatching()" id="btn-sel-all"
                            class="text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                        Seleccionar todos
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="tabla-alumnos">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="chk-all" onchange="toggleSelectCurrentPage(this.checked)"
                                       class="rounded border-slate-300 dark:border-slate-600 text-blue-600 cursor-pointer" title="Seleccionar visibles en esta página">
                            </th>
                            <th class="px-4 py-3">Alumno</th>
                            <th class="px-4 py-3">Grado Actual</th>
                            <th class="px-4 py-3">Grupo</th>
                            <th class="px-4 py-3">Sede</th>
                            <th class="px-4 py-3 text-center">Acción Rápida</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50" id="tbody-alumnos">
                        <?php if (empty($alumnos)): ?>
                        <tr id="fila-no-alumnos">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                <span class="material-icons-outlined text-3xl block mb-2 opacity-40">person_off</span>
                                No hay alumnos activos registrados.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($alumnos as $a): ?>
                        <tr class="fila-alumno hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition-colors"
                            data-nombre="<?= htmlspecialchars(strtolower($a['nombre'] . ' ' . $a['apellido'])) ?>"
                            data-grupo="<?= htmlspecialchars($a['nombre_grupo'] ?? '') ?>">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="check_alumno[]"
                                       value="<?= (int)$a['id'] ?>"
                                       data-nombre="<?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?>"
                                       data-grado="<?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin grado') ?>"
                                       class="chk-alumno rounded border-slate-300 dark:border-slate-600 text-blue-600 cursor-pointer"
                                       onchange="actualizarContador()">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <?php if (!empty($a['foto_perfil'])): ?>
                                        <img src="<?= asset('uploads/perfiles/' . $a['foto_perfil']) ?>" alt="Foto"
                                             class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-xs shrink-0 border border-blue-200 dark:border-blue-800">
                                            <?= strtoupper(substr($a['nombre'] ?? '?', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-semibold text-slate-900 dark:text-white text-sm"><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?></div>
                                        <div class="text-[11px] text-slate-400"><?= htmlspecialchars($a['num_doc'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold
                                    bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300
                                    border border-slate-200 dark:border-slate-700 uppercase whitespace-nowrap">
                                    <?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin grado') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-400">
                                <?= htmlspecialchars($a['nombre_grupo'] ?? '—') ?>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                                <?= htmlspecialchars($a['nombre_sede'] ?? '—') ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button"
                                        onclick="abrirModalIndividual(<?= (int)$a['id'] ?>, '<?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido'], ENT_QUOTES) ?>', '<?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin grado', ENT_QUOTES) ?>')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-600 hover:text-white border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-bold uppercase tracking-wider transition-all cursor-pointer">
                                    <span class="material-icons-outlined text-sm">workspace_premium</span>
                                    <span>Ascender</span>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <!-- Fila de sin resultados en filtro -->
                        <tr id="fila-sin-coincidencias-alumnos" style="display: none;">
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-sm">
                                No se encontraron alumnos con los filtros aplicados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginación Alumnos (10 por página) -->
            <div id="alumnos-pagination-controls" class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Mostrando <span id="alumnos-page-start" class="font-bold text-slate-800 dark:text-slate-200">1</span> a <span id="alumnos-page-end" class="font-bold text-slate-800 dark:text-slate-200">10</span> de <span id="alumnos-total-count" class="font-bold text-slate-800 dark:text-slate-200"><?= count($alumnos) ?></span> alumnos
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <span>Mostrar:</span>
                        <select id="alumnos-page-size" class="text-xs rounded-lg bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 py-1 px-2 focus:outline-none focus:border-blue-500">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="all">Todos</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-1.5" id="alumnos-pagination-buttons">
                    <button type="button" id="alumnos-btn-prev" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span class="material-icons-outlined text-sm">chevron_left</span>
                        <span>Anterior</span>
                    </button>
                    
                    <div id="alumnos-page-numbers" class="flex items-center gap-1">
                        <!-- Números generados dinámicamente -->
                    </div>

                    <button type="button" id="alumnos-btn-next" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span>Siguiente</span>
                        <span class="material-icons-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========= SECCIÓN: HISTORIAL ========= -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base text-blue-500">history</span>
                    <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Diplomas y Certificados Emitidos (<span id="historial-total-badge"><?= count($historial) ?></span>)
                    </h2>
                </div>
                <div class="relative w-full sm:w-72">
                    <span class="material-icons-outlined absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
                    <input type="text" id="filtro-historial" placeholder="Buscar por alumno, grado o folio..."
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 transition-colors">
                </div>
            </div>

            <?php if (!empty($historial)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="tabla-diplomas">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3.5">Alumno</th>
                            <th class="px-3 py-3.5 text-center">De</th>
                            <th class="px-3 py-3.5 text-center">→ A</th>
                            <th class="px-3 py-3.5">Fecha</th>
                            <th class="px-3 py-3.5">Folio</th>
                            <th class="px-3 py-3.5">Evaluador</th>
                            <th class="px-3 py-3.5 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        <?php foreach($historial as $h): ?>
                        <tr class="fila-diploma hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors"
                            data-search="<?= htmlspecialchars(strtolower($h['nombre_alumno'] . ' ' . $h['grado_nuevo'] . ' ' . $h['folio'])) ?>">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-xs shrink-0">
                                        <?= strtoupper(substr($h['nombre_alumno'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div><?= htmlspecialchars($h['nombre_alumno']) ?></div>
                                        <?php if (!empty($h['num_doc'])): ?>
                                            <div class="text-[11px] font-normal text-slate-400">
                                                <?= htmlspecialchars($h['tipo_documento'] ?? 'TI') ?> <?= htmlspecialchars($h['num_doc']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 uppercase">
                                    <?= htmlspecialchars($h['grado_anterior']) ?>
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 uppercase">
                                    <span class="material-icons-outlined text-xs">arrow_upward</span>
                                    <?= htmlspecialchars($h['grado_nuevo']) ?>
                                </span>
                            </td>
                            <td class="px-3 py-3 text-slate-600 dark:text-slate-400 text-xs whitespace-nowrap">
                                <?= date('d/m/Y', strtotime($h['fecha_examen'])) ?>
                            </td>
                            <td class="px-3 py-3">
                                <span class="font-mono text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($h['folio']) ?></span>
                            </td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                <?= htmlspecialchars($h['nombre_maestro']) ?>
                            </td>
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" onclick="openAdminCert(<?= (int)$h['id'] ?>)"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-600 hover:text-white border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-bold uppercase tracking-wider transition-all cursor-pointer">
                                        <span class="material-icons-outlined text-sm">visibility</span>
                                        <span>Ver</span>
                                    </button>
                                    <a href="<?= base_url('/admin/ascensos/descargar?id=' . (int)$h['id']) ?>"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                                        <span class="material-icons-outlined text-sm">download</span>
                                        <span>PDF</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr id="fila-sin-coincidencias-diplomas" style="display: none;">
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-sm">
                                No se encontraron diplomas que coincidan con la búsqueda.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Controles de Paginación Diplomas -->
            <div id="diplomas-pagination-controls" class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors">
                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Mostrando <span id="diplomas-page-start" class="font-bold text-slate-800 dark:text-slate-200">1</span> a <span id="diplomas-page-end" class="font-bold text-slate-800 dark:text-slate-200">10</span> de <span id="diplomas-total-count" class="font-bold text-slate-800 dark:text-slate-200"><?= count($historial) ?></span> diplomas
                </div>

                <div class="flex items-center gap-1.5" id="diplomas-pagination-buttons">
                    <button type="button" id="diplomas-btn-prev" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span class="material-icons-outlined text-sm">chevron_left</span>
                        <span>Anterior</span>
                    </button>
                    
                    <div id="diplomas-page-numbers" class="flex items-center gap-1">
                        <!-- Números generados dinámicamente -->
                    </div>

                    <button type="button" id="diplomas-btn-next" class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1">
                        <span>Siguiente</span>
                        <span class="material-icons-outlined text-sm">chevron_right</span>
                    </button>
                </div>
            </div>

            <?php else: ?>
            <div class="px-6 py-10 text-center text-slate-500 dark:text-slate-400 text-sm">
                No hay diplomas generados todavía.
            </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<!-- ======== MODAL ASCENSO MASIVO / INDIVIDUAL ======== -->
<div id="modal-ascenso" class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col my-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200 dark:border-blue-800">
                    <span class="material-icons-outlined text-xl">military_tech</span>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 block" id="modal-tipo-label">Ascenso Masivo</span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base leading-tight" id="modal-titulo">Registrar Ascenso de Grado</h3>
                </div>
            </div>
            <button type="button" onclick="cerrarModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>

        <form id="form-ascenso" method="POST" action="<?= base_url('/admin/ascensos/store-bulk') ?>" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            <input type="hidden" id="form-modo" name="_modo" value="bulk">
            <!-- IDs bulk: se llena por JS -->
            <div id="bulk-inputs-container"></div>
            <!-- ID individual (single) -->
            <input type="hidden" name="id_alumno" id="input-id-alumno" value="">

            <div class="p-6 space-y-5 overflow-y-auto flex-1">

                <!-- Resumen de alumnos seleccionados -->
                <div id="resumen-alumnos-bulk" class="space-y-2">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-icons-outlined text-sm text-blue-500">group</span>
                        Alumnos a Ascender
                    </label>
                    <div id="chips-alumnos" class="flex flex-wrap gap-1.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 min-h-[48px]">
                        <span class="text-xs text-slate-400 italic">Ningún alumno seleccionado</span>
                    </div>
                </div>

                <!-- Grado actual (solo individual) -->
                <div id="campo-grado-actual" class="hidden">
                    <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Grado Actual</label>
                    <div id="display-grado-actual" class="w-full rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 p-3 text-sm">—</div>
                    <input type="hidden" name="grado_anterior" id="input-grado-anterior">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nuevo Grado -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                            Nuevo Cinturón / Grado *
                        </label>
                        <select name="id_grado_nuevo" required
                                class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                            <option value="">— Selecciona el nuevo grado —</option>
                            <?php foreach($grados_list as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fecha Diploma -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                            Fecha del Diploma / Examen *
                        </label>
                        <input type="date" name="fecha_examen" value="<?= date('Y-m-d') ?>" required
                               class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                    </div>
                </div>

                <!-- Maestro Evaluador -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Maestro Evaluador (opcional)
                    </label>
                    <select name="id_maestro"
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <option value="">— Directo por Administración —</option>
                        <?php foreach($maestros_list as $m): ?>
                            <option value="<?= $m['id_maestro'] ?>"><?= htmlspecialchars($m['nombre_completo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Observaciones
                    </label>
                    <textarea name="observaciones" rows="2" placeholder="Ej. Alumnos evaluados en examen general de marzo 2026..."
                              class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none resize-none"></textarea>
                </div>

            </div>

            <!-- Footer del modal -->
            <div class="px-6 py-4 bg-slate-50/80 dark:bg-slate-950/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3 shrink-0">
                <button type="button" onclick="cerrarModal()"
                        class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold uppercase text-xs rounded-xl transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" id="btn-confirmar-ascenso"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer">
                    <span class="material-icons-outlined text-sm">workspace_premium</span>
                    <span id="texto-btn-confirmar">Generar Diplomas</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Diploma Viewer -->
<div id="modal-cert-admin" class="fixed inset-0 z-50 hidden items-center justify-center p-0">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeAdminCert()"></div>
    <div class="relative bg-white dark:bg-slate-900 w-screen h-screen max-w-none max-h-screen overflow-hidden">
        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-icons-outlined text-blue-600">workspace_premium</span>
                Certificado / Diploma de Ascenso de Grado
            </h2>
            <div class="flex items-center gap-2">
                <button onclick="closeAdminCert()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm cursor-pointer">
                    <span class="material-icons-outlined text-sm">arrow_back</span>
                    Volver
                </button>
                <button id="btn-download-admin-pdf" onclick="downloadAdminPDF()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm cursor-pointer">
                    <span class="material-icons-outlined text-sm">picture_as_pdf</span>
                    Descargar PDF
                </button>
                <button onclick="printAdminCert()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm cursor-pointer">
                    <span class="material-icons-outlined text-sm">print</span>
                    Imprimir
                </button>
                <button onclick="closeAdminCert()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors cursor-pointer">
                    <span class="material-icons-outlined text-sm">close</span>
                </button>
            </div>
        </div>
        <div id="admin-cert-wrapper" class="flex items-start justify-center h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden bg-slate-100 dark:bg-slate-950/60 p-4 md:p-8" style="scrollbar-width: thin; overscroll-behavior: contain;">
            <div class="flex items-center justify-center py-12 text-slate-400">
                <span class="material-icons-outlined text-4xl">hourglass_top</span>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body > *:not(#modal-cert-admin) { display: none !important; }
    #modal-cert-admin { position: static !important; display: block !important; background: transparent !important; padding: 0 !important; width: 100% !important; height: auto !important; }
    #modal-cert-admin > div:first-child,
    #modal-cert-admin .sticky { display: none !important; }
    #admin-cert-wrapper { overflow: visible !important; height: auto !important; padding: 0 !important; margin: 0 !important; background: transparent !important; }
    #certificado-contenido { box-shadow: none !important; border-radius: 0 !important; margin: 0 auto !important; page-break-inside: avoid !important; }
    @page { size: portrait; margin: 0; }
}
</style>

<script>
// ==========================================
// PAGINACIÓN Y FILTRADO DE ALUMNOS ACTIVOS
// ==========================================
const todasFilasAlumnos = Array.from(document.querySelectorAll('.fila-alumno'));
let matchingAlumnos = [...todasFilasAlumnos];
let alumnosCurrentPage = 1;
let alumnosPageSize = 10;

function renderAlumnosPagination() {
    const totalMatching = matchingAlumnos.length;
    const effectivePageSize = alumnosPageSize === 'all' ? (totalMatching || 1) : parseInt(alumnosPageSize);
    const totalPages = Math.max(1, Math.ceil(totalMatching / effectivePageSize));

    if (alumnosCurrentPage > totalPages) alumnosCurrentPage = totalPages;
    if (alumnosCurrentPage < 1) alumnosCurrentPage = 1;

    const startIdx = totalMatching === 0 ? 0 : (alumnosCurrentPage - 1) * effectivePageSize + 1;
    const endIdx = Math.min(alumnosCurrentPage * effectivePageSize, totalMatching);

    const startEl = document.getElementById('alumnos-page-start');
    const endEl = document.getElementById('alumnos-page-end');
    const totalEl = document.getElementById('alumnos-total-count');
    const badgeEl = document.getElementById('badge-total-alumnos');

    if (startEl) startEl.textContent = startIdx;
    if (endEl) endEl.textContent = endIdx;
    if (totalEl) totalEl.textContent = totalMatching;
    if (badgeEl) badgeEl.textContent = totalMatching;

    // Ocultar todas las filas de alumnos
    todasFilasAlumnos.forEach(f => f.style.display = 'none');

    // Mostrar fila de "sin coincidencias" si aplica
    const noMatchRow = document.getElementById('fila-sin-coincidencias-alumnos');
    if (noMatchRow) {
        noMatchRow.style.display = totalMatching === 0 && todasFilasAlumnos.length > 0 ? '' : 'none';
    }

    // Mostrar solo el segmento de la página activa
    const pageSlice = alumnosPageSize === 'all' 
        ? matchingAlumnos 
        : matchingAlumnos.slice((alumnosCurrentPage - 1) * effectivePageSize, alumnosCurrentPage * effectivePageSize);
    pageSlice.forEach(f => f.style.display = '');

    // Botones Prev / Next
    const btnPrev = document.getElementById('alumnos-btn-prev');
    const btnNext = document.getElementById('alumnos-btn-next');
    if (btnPrev) btnPrev.disabled = (alumnosCurrentPage === 1 || totalMatching === 0);
    if (btnNext) btnNext.disabled = (alumnosCurrentPage === totalPages || totalMatching === 0);

    // Renderizar botones numéricos
    const numbersContainer = document.getElementById('alumnos-page-numbers');
    if (numbersContainer) {
        numbersContainer.innerHTML = '';

        if (alumnosPageSize !== 'all' && totalPages > 1) {
            let startPage = Math.max(1, alumnosCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            if (startPage > 1) {
                numbersContainer.appendChild(crearBotonPaginaAlumno(1));
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    numbersContainer.appendChild(dots);
                }
            }

            for (let p = startPage; p <= endPage; p++) {
                numbersContainer.appendChild(crearBotonPaginaAlumno(p));
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    numbersContainer.appendChild(dots);
                }
                numbersContainer.appendChild(crearBotonPaginaAlumno(totalPages));
            }
        }
    }

    sincronizarCheckHeader();
}

function crearBotonPaginaAlumno(num) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = num;
    const isActive = num === alumnosCurrentPage;
    btn.className = `w-8 h-8 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center ${
        isActive 
            ? 'bg-blue-600 text-white shadow-md scale-105' 
            : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
    }`;
    btn.addEventListener('click', () => {
        alumnosCurrentPage = num;
        renderAlumnosPagination();
        document.getElementById('card-alumnos')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
    return btn;
}

// Eventos de botones Prev / Next de alumnos
document.getElementById('alumnos-btn-prev')?.addEventListener('click', () => {
    if (alumnosCurrentPage > 1) {
        alumnosCurrentPage--;
        renderAlumnosPagination();
        document.getElementById('card-alumnos')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});

document.getElementById('alumnos-btn-next')?.addEventListener('click', () => {
    const effectivePageSize = alumnosPageSize === 'all' ? matchingAlumnos.length : parseInt(alumnosPageSize);
    const totalPages = Math.ceil(matchingAlumnos.length / effectivePageSize) || 1;
    if (alumnosCurrentPage < totalPages) {
        alumnosCurrentPage++;
        renderAlumnosPagination();
        document.getElementById('card-alumnos')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});

// Selector de tamaño de página
document.getElementById('alumnos-page-size')?.addEventListener('change', function() {
    alumnosPageSize = this.value;
    alumnosCurrentPage = 1;
    renderAlumnosPagination();
});

// Filtros de búsqueda y grupo para alumnos
function filtrarTablaAlumnos() {
    const q = document.getElementById('buscar-alumno')?.value.toLowerCase().trim() || '';
    const g = document.getElementById('filtro-grupo-tabla')?.value || '';

    matchingAlumnos = todasFilasAlumnos.filter(fila => {
        const nombre = fila.getAttribute('data-nombre') || '';
        const grupo  = fila.getAttribute('data-grupo') || '';
        return (!q || nombre.includes(q)) && (!g || grupo === g);
    });

    alumnosCurrentPage = 1;
    renderAlumnosPagination();
}

document.getElementById('buscar-alumno')?.addEventListener('input', filtrarTablaAlumnos);
document.getElementById('filtro-grupo-tabla')?.addEventListener('change', filtrarTablaAlumnos);

// ==========================================
// SELECCIÓN Y CHECKBOXES DE ALUMNOS
// ==========================================
function actualizarContador() {
    const checks = document.querySelectorAll('.chk-alumno:checked');
    const n = checks.length;
    const contadorEl = document.getElementById('contador-seleccionados');
    if (contadorEl) contadorEl.textContent = n;

    const btn = document.getElementById('btn-abrir-ascenso-masivo');
    if (btn) btn.disabled = n === 0;

    sincronizarCheckHeader();
}

function sincronizarCheckHeader() {
    const chkAll = document.getElementById('chk-all');
    if (!chkAll) return;

    // Verificar si los visibles en la página actual están seleccionados
    const visibleChecks = Array.from(document.querySelectorAll('.fila-alumno'))
        .filter(f => f.style.display !== 'none')
        .map(f => f.querySelector('.chk-alumno'))
        .filter(c => c !== null);

    if (visibleChecks.length === 0) {
        chkAll.checked = false;
        chkAll.indeterminate = false;
        return;
    }

    const checkedVisible = visibleChecks.filter(c => c.checked).length;
    chkAll.checked = checkedVisible === visibleChecks.length;
    chkAll.indeterminate = checkedVisible > 0 && checkedVisible < visibleChecks.length;
}

// Checkbox de cabecera: selecciona/deselecciona los visibles en la página actual
function toggleSelectCurrentPage(val) {
    const visibleChecks = Array.from(document.querySelectorAll('.fila-alumno'))
        .filter(f => f.style.display !== 'none')
        .map(f => f.querySelector('.chk-alumno'))
        .filter(c => c !== null);

    visibleChecks.forEach(c => { c.checked = val; });
    actualizarContador();
}

// Botón "Seleccionar todos": selecciona/deselecciona todos los que coincidan con el filtro actual
function toggleSelectAllMatching() {
    const matchingChecks = matchingAlumnos
        .map(f => f.querySelector('.chk-alumno'))
        .filter(c => c !== null);

    const allChecked = matchingChecks.length > 0 && matchingChecks.every(c => c.checked);
    matchingChecks.forEach(c => { c.checked = !allChecked; });
    actualizarContador();
}

// ==========================================
// MODAL DE ASCENSO MASIVO / INDIVIDUAL
// ==========================================
function abrirModalMasivo() {
    const checks = document.querySelectorAll('.chk-alumno:checked');
    if (checks.length === 0) return;

    const form = document.getElementById('form-ascenso');
    form.action = '<?= base_url('/admin/ascensos/store-bulk') ?>';
    document.getElementById('input-id-alumno').value = '';

    // Limpiar inputs bulk previos
    document.getElementById('bulk-inputs-container').innerHTML = '';
    const chips = document.getElementById('chips-alumnos');
    chips.innerHTML = '';

    checks.forEach(chk => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids_alumnos[]';
        input.value = chk.value;
        document.getElementById('bulk-inputs-container').appendChild(input);

        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800';
        chip.innerHTML = `<span class="material-icons-outlined text-xs">person</span>${chk.getAttribute('data-nombre')} <span class="text-blue-400 text-[10px]">(${chk.getAttribute('data-grado')})</span>`;
        chips.appendChild(chip);
    });

    document.getElementById('modal-tipo-label').textContent = 'Ascenso Masivo — ' + checks.length + ' alumno(s)';
    document.getElementById('modal-titulo').textContent = 'Ascender ' + checks.length + ' alumno(s) al mismo grado';
    document.getElementById('texto-btn-confirmar').textContent = 'Generar ' + checks.length + ' Diploma(s)';
    document.getElementById('campo-grado-actual').classList.add('hidden');
    document.getElementById('resumen-alumnos-bulk').classList.remove('hidden');

    const modal = document.getElementById('modal-ascenso');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function abrirModalIndividual(idAlumno, nombre, gradoActual) {
    const form = document.getElementById('form-ascenso');
    form.action = '<?= base_url('/admin/ascensos/store') ?>';
    document.getElementById('input-id-alumno').value = idAlumno;
    document.getElementById('bulk-inputs-container').innerHTML = '';

    document.getElementById('modal-tipo-label').textContent = 'Ascenso Individual';
    document.getElementById('modal-titulo').textContent = 'Ascender: ' + nombre;
    document.getElementById('texto-btn-confirmar').textContent = 'Generar Diploma';

    document.getElementById('campo-grado-actual').classList.remove('hidden');
    document.getElementById('display-grado-actual').textContent = gradoActual;
    document.getElementById('input-grado-anterior').value = gradoActual;

    const chips = document.getElementById('chips-alumnos');
    chips.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
        <span class="material-icons-outlined text-xs">person</span>${nombre} <span class="text-blue-400 text-[10px]">(${gradoActual})</span></span>`;

    const modal = document.getElementById('modal-ascenso');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModal() {
    const modal = document.getElementById('modal-ascenso');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// ==========================================
// PAGINACIÓN Y FILTRADO DE DIPLOMAS / HISTORIAL
// ==========================================
const todasFilasDiplomas = Array.from(document.querySelectorAll('.fila-diploma'));
let matchingDiplomas = [...todasFilasDiplomas];
let diplomasCurrentPage = 1;
const diplomasPageSize = 10;

function renderDiplomasPagination() {
    const totalMatching = matchingDiplomas.length;
    const totalPages = Math.max(1, Math.ceil(totalMatching / diplomasPageSize));

    if (diplomasCurrentPage > totalPages) diplomasCurrentPage = totalPages;
    if (diplomasCurrentPage < 1) diplomasCurrentPage = 1;

    const startIdx = totalMatching === 0 ? 0 : (diplomasCurrentPage - 1) * diplomasPageSize + 1;
    const endIdx = Math.min(diplomasCurrentPage * diplomasPageSize, totalMatching);

    const startEl = document.getElementById('diplomas-page-start');
    const endEl = document.getElementById('diplomas-page-end');
    const totalEl = document.getElementById('diplomas-total-count');
    const badgeEl = document.getElementById('historial-total-badge');

    if (startEl) startEl.textContent = startIdx;
    if (endEl) endEl.textContent = endIdx;
    if (totalEl) totalEl.textContent = totalMatching;
    if (badgeEl) badgeEl.textContent = totalMatching;

    todasFilasDiplomas.forEach(f => f.style.display = 'none');

    const noMatchRow = document.getElementById('fila-sin-coincidencias-diplomas');
    if (noMatchRow) {
        noMatchRow.style.display = totalMatching === 0 && todasFilasDiplomas.length > 0 ? '' : 'none';
    }

    const pageSlice = matchingDiplomas.slice((diplomasCurrentPage - 1) * diplomasPageSize, diplomasCurrentPage * diplomasPageSize);
    pageSlice.forEach(f => f.style.display = '');

    const btnPrev = document.getElementById('diplomas-btn-prev');
    const btnNext = document.getElementById('diplomas-btn-next');
    if (btnPrev) btnPrev.disabled = (diplomasCurrentPage === 1 || totalMatching === 0);
    if (btnNext) btnNext.disabled = (diplomasCurrentPage === totalPages || totalMatching === 0);

    const numbersContainer = document.getElementById('diplomas-page-numbers');
    if (numbersContainer) {
        numbersContainer.innerHTML = '';
        if (totalPages > 1) {
            let startPage = Math.max(1, diplomasCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            if (startPage > 1) {
                numbersContainer.appendChild(crearBotonPaginaDiploma(1));
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    numbersContainer.appendChild(dots);
                }
            }

            for (let p = startPage; p <= endPage; p++) {
                numbersContainer.appendChild(crearBotonPaginaDiploma(p));
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'px-1 text-slate-400 text-xs font-bold';
                    dots.textContent = '...';
                    numbersContainer.appendChild(dots);
                }
                numbersContainer.appendChild(crearBotonPaginaDiploma(totalPages));
            }
        }
    }
}

function crearBotonPaginaDiploma(num) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = num;
    const isActive = num === diplomasCurrentPage;
    btn.className = `w-8 h-8 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center ${
        isActive 
            ? 'bg-blue-600 text-white shadow-md scale-105' 
            : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'
    }`;
    btn.addEventListener('click', () => {
        diplomasCurrentPage = num;
        renderDiplomasPagination();
    });
    return btn;
}

document.getElementById('diplomas-btn-prev')?.addEventListener('click', () => {
    if (diplomasCurrentPage > 1) {
        diplomasCurrentPage--;
        renderDiplomasPagination();
    }
});

document.getElementById('diplomas-btn-next')?.addEventListener('click', () => {
    const totalPages = Math.ceil(matchingDiplomas.length / diplomasPageSize) || 1;
    if (diplomasCurrentPage < totalPages) {
        diplomasCurrentPage++;
        renderDiplomasPagination();
    }
});

document.getElementById('filtro-historial')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    matchingDiplomas = todasFilasDiplomas.filter(f => {
        const text = f.getAttribute('data-search') || '';
        return text.includes(q);
    });
    diplomasCurrentPage = 1;
    renderDiplomasPagination();
});

// ==========================================
// MODAL DIPLOMA / CERTIFICADO VIEWER
// ==========================================
let currentAdminCertId = null;

function openAdminCert(idCert) {
    currentAdminCertId = idCert;
    const modal = document.getElementById('modal-cert-admin');
    const wrapper = document.getElementById('admin-cert-wrapper');
    wrapper.innerHTML = '<div class="flex items-center justify-center py-12 text-slate-400"><span class="material-icons-outlined text-4xl">hourglass_top</span></div>';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    fetch('<?= base_url('/admin/ascensos/certificado') ?>?id=' + idCert)
        .then(r => r.text())
        .then(html => { wrapper.innerHTML = html; })
        .catch(() => { wrapper.innerHTML = '<p class="text-center text-rose-500 py-8">Error al cargar el diploma.</p>'; });
}

function closeAdminCert() {
    const modal = document.getElementById('modal-cert-admin');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function downloadAdminPDF() {
    if (!currentAdminCertId) return;
    window.location.href = '<?= base_url('/admin/ascensos/descargar') ?>?id=' + currentAdminCertId;
}

function printAdminCert() {
    const element = document.getElementById('certificado-contenido');
    if (!element) { alert('El certificado aún se está cargando.'); return; }
    window.print();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') { cerrarModal(); closeAdminCert(); } });

// ==========================================
// INICIALIZACIÓN AL CARGAR LA PÁGINA
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    renderAlumnosPagination();
    if (todasFilasDiplomas.length > 0) {
        renderDiplomasPagination();
    }
});
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

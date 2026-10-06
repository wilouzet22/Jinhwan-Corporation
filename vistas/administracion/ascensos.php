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
                    Emite diplomas oficiales seleccionando la persona, el nuevo grado y la fecha de expedición.
                </p>
            </div>
            <button onclick="document.getElementById('modal-ascenso').classList.remove('hidden'); document.getElementById('modal-ascenso').classList.add('flex');"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all cursor-pointer">
                <span class="material-icons-outlined text-lg">add_circle</span>
                <span>Generar Nuevo Diploma</span>
            </button>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="flex items-center justify-between gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-5 py-4 rounded-xl text-sm font-semibold shadow-xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-outlined text-xl text-emerald-600 dark:text-emerald-400">verified</span>
                <span>¡Diploma generado exitosamente! El certificado y registro de grado han sido actualizados.</span>
            </div>
        </div>
        <?php elseif (isset($_GET['error'])): ?>
        <div class="flex items-center gap-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 px-5 py-4 rounded-xl text-sm font-medium shadow-xs">
            <span class="material-icons-outlined text-xl">error</span>
            Ocurrió un error: <?= htmlspecialchars($_GET['error']) ?>
        </div>
        <?php endif; ?>

        <!-- Historial de Diplomas Generados -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="material-icons-outlined text-base text-blue-500">history</span>
                    <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Diplomas y Certificados Emitidos (<?= count($historial) ?>)
                    </h2>
                </div>
                
                <!-- Buscador de diplomas en tiempo real -->
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
                            <th class="px-3 py-3.5 text-center">A</th>
                            <th class="px-3 py-3.5">Fecha Diploma</th>
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
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-600 hover:text-white border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-bold uppercase tracking-wider transition-all cursor-pointer" title="Ver Diploma">
                                        <span class="material-icons-outlined text-sm">visibility</span>
                                        <span>Ver</span>
                                    </button>
                                    <a href="<?= base_url('/admin/ascensos/descargar?id=' . (int)$h['id']) ?>"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-bold uppercase tracking-wider transition-all" title="Descargar PDF">
                                        <span class="material-icons-outlined text-sm">download</span>
                                        <span>PDF</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="px-6 py-10 text-center text-slate-500 dark:text-slate-400 text-sm">
                No hay diplomas generados todavía. Haz clic en "Generar Nuevo Diploma" para emitir el primero.
            </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<!-- Modal Registrar Ascenso -->
<div id="modal-ascenso" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] flex flex-col my-auto">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center shrink-0">
            <h3 class="text-xl font-display font-bold text-slate-900 dark:text-white">Registrar Ascenso</h3>
            <button onclick="document.getElementById('modal-ascenso').classList.add('hidden'); document.getElementById('modal-ascenso').classList.remove('flex');"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="<?= base_url('/admin/ascensos/store') ?>" class="flex flex-col flex-1 min-h-0 overflow-hidden">
            <div class="p-6 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <!-- Filtros para encontrar alumno -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Filtrar por Sede</label>
                        <select id="modal-filter-sede" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                            <option value="">Todas</option>
                            <?php foreach($sedes_list as $sede): ?>
                                <option value="<?= htmlspecialchars($sede['nombre']) ?>"><?= htmlspecialchars($sede['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Filtrar por Grupo</label>
                        <select id="modal-filter-grupo" class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-300 p-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                            <option value="">Todos</option>
                            <?php foreach($grupos_list as $grupo): ?>
                                <option value="<?= htmlspecialchars($grupo['nombre']) ?>"><?= htmlspecialchars($grupo['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Select Alumno -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Alumno *</label>
                    <select name="id_alumno" id="select-alumno" required
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <option value="">— Selecciona un alumno —</option>
                        <?php foreach($alumnos as $a): ?>
                            <option value="<?= $a['id'] ?>"
                                    data-grado="<?= htmlspecialchars($a['nombre_nivel'] ?? '') ?>"
                                    data-sede="<?= htmlspecialchars($a['nombre_sede'] ?? '') ?>"
                                    data-grupo="<?= htmlspecialchars($a['nombre_grupo'] ?? '') ?>">
                                <?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?> — <?= htmlspecialchars($a['nombre_nivel'] ?? 'Sin grado') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Grado anterior (auto) -->
                <input type="hidden" name="grado_anterior" id="input-grado-anterior">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Grado Actual</label>
                    <div id="display-grado-actual" class="w-full rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 p-3 text-sm">
                        Selecciona un alumno...
                    </div>
                </div>

                <!-- Nuevo Grado -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Nuevo Grado *</label>
                    <select name="id_grado_nuevo" required
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <option value="">— Selecciona el nuevo grado —</option>
                        <?php foreach($grados_list as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha del Diploma -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Fecha del Diploma / Examen *</label>
                    <input type="date" name="fecha_examen" value="<?= date('Y-m-d') ?>" required
                           class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                    <span class="text-[11px] text-slate-400 mt-1 block">Esta es la fecha que se imprimirá en el diploma oficial.</span>
                </div>

                <!-- Maestro Evaluador (Opcional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Maestro Evaluador</label>
                    <select name="id_maestro"
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none">
                        <option value="">— Directo por Administración —</option>
                        <?php foreach($maestros_list as $m): ?>
                            <option value="<?= $m['id_maestro'] ?>"><?= htmlspecialchars($m['nombre_completo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1.5">Observaciones</label>
                    <textarea name="observaciones" rows="2" placeholder="Notas sobre el examen o méritos..."
                              class="w-full rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white p-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors focus:outline-none resize-none"></textarea>
                </div>
            </div>

            <!-- Barra fija inferior de botones de acción -->
            <div class="p-4 bg-slate-50 dark:bg-slate-950/80 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                <button type="button"
                        onclick="document.getElementById('modal-ascenso').classList.add('hidden'); document.getElementById('modal-ascenso').classList.remove('flex');"
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold uppercase text-xs rounded-xl transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase text-xs rounded-xl transition-all shadow-md hover:shadow-lg cursor-pointer">
                    <span class="material-icons-outlined text-sm">workspace_premium</span>
                    <span>Generar y Emitir Diploma</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Al seleccionar alumno, muestra su grado actual
document.getElementById('select-alumno')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const grado = opt.getAttribute('data-grado') || 'Sin grado';
    document.getElementById('display-grado-actual').textContent = grado;
    document.getElementById('input-grado-anterior').value = grado;
});

// Filtros de sede y grupo en el select de alumnos
function applyModalFilters() {
    const sede  = document.getElementById('modal-filter-sede')?.value || '';
    const grupo = document.getElementById('modal-filter-grupo')?.value || '';
    const select = document.getElementById('select-alumno');
    if (!select) return;

    Array.from(select.options).forEach(opt => {
        if (!opt.value) return; // saltar la opción vacía
        const oSede  = opt.getAttribute('data-sede') || '';
        const oGrupo = opt.getAttribute('data-grupo') || '';
        const show = (!sede || oSede === sede) && (!grupo || oGrupo === grupo);
        opt.style.display = show ? '' : 'none';
    });

    // Reset si la opción activa quedó oculta
    const selected = select.options[select.selectedIndex];
    if (selected && selected.style.display === 'none') {
        select.value = '';
        document.getElementById('display-grado-actual').textContent = 'Selecciona un alumno...';
        document.getElementById('input-grado-anterior').value = '';
    }
}

document.getElementById('modal-filter-sede')?.addEventListener('change', applyModalFilters);
document.getElementById('modal-filter-grupo')?.addEventListener('change', applyModalFilters);

// Buscador dinámico en la tabla de historial
document.getElementById('filtro-historial')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const filas = document.querySelectorAll('.fila-diploma');
    filas.forEach(f => {
        const text = f.getAttribute('data-search') || '';
        f.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>

<!-- Modal Diploma / Certificado Admin -->
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
                <button onclick="printAdminCert()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm cursor-pointer" title="Imprimir o Guardar como PDF con la impresora del sistema">
                    <span class="material-icons-outlined text-sm">print</span>
                    Imprimir
                </button>
                <button onclick="closeAdminCert()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors cursor-pointer" aria-label="Cerrar diploma">
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

<script src="<?= asset('js/vendor/html2pdf.bundle.min.js') ?>" charset="utf-8"></script>
<script>
if (typeof html2pdf === 'undefined') {
    const s = document.createElement('script');
    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
    document.head.appendChild(s);
}

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
    if (!currentAdminCertId) {
        alert('Por favor seleccione un certificado válido.');
        return;
    }
    // Descarga directa normal del PDF generado en el servidor
    window.location.href = '<?= base_url('/admin/ascensos/descargar') ?>?id=' + currentAdminCertId;
}

function printAdminCert() {
    const element = document.getElementById('certificado-contenido');
    if (!element) {
        alert('El certificado aún se está cargando.');
        return;
    }
    window.print();
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAdminCert(); });
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

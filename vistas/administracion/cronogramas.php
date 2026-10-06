<?php require_once __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Cronogramas de Clase
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Consulta los planes de sesión registrados por los maestros. Vista de solo lectura.
                </p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800/60 rounded-xl text-blue-700 dark:text-blue-300 text-xs font-semibold">
                <span class="material-icons-outlined text-base">admin_panel_settings</span>
                <span>Modo supervisión</span>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-icons-outlined text-sm">search</span>
                </div>
                <input type="text" id="filter-search" placeholder="Buscar por objetivo, maestro, grupo..."
                       class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Grupo:</span>
                <select id="filter-grupo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors w-full md:w-64">
                    <option value="">Todos los grupos</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?php echo htmlspecialchars($g['id_grupo']); ?>">
                            <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['nombre_sede'] ?? '') . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Tabla Cronogramas -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3">Fecha &amp; Día</th>
                            <th scope="col" class="px-4 py-3">Grupo / Sede</th>
                            <th scope="col" class="px-4 py-3">Maestro Responsable</th>
                            <th scope="col" class="px-4 py-3">Objetivo de la Clase</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
<?php
function fechaEspanolCronAdmin($fechaStr) {
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $ts = strtotime($fechaStr);
    $diaSemana = $dias[date('w', $ts)];
    $dia = date('j', $ts);
    $mes = $meses[(int)date('n', $ts)];
    $anio = date('Y', $ts);
    return ['dia_semana' => $diaSemana, 'fecha_completa' => "$diaSemana, $dia de $mes de $anio"];
}
?>
                        <?php if (empty($cronogramas)): ?>
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-outlined text-4xl block mb-2 opacity-40">event_note</span>
                                    No hay cronogramas registrados aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cronogramas as $c):
                                $fInfo = fechaEspanolCronAdmin($c['fecha']);
                            ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all item-cronograma"
                                    data-grupo="<?php echo htmlspecialchars($c['id_grupo']); ?>"
                                    data-text="<?php echo htmlspecialchars(strtolower(($c['objetivo'] ?? '') . ' ' . ($c['maestro_nombre'] ?? '') . ' ' . ($c['grupo_nombre'] ?? '') . ' ' . $fInfo['fecha_completa'])); ?>">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40">
                                                <span class="material-icons-outlined text-base block">calendar_today</span>
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-900 dark:text-white block text-sm"><?php echo $fInfo['dia_semana']; ?></span>
                                                <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo $fInfo['fecha_completa']; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-slate-800 dark:text-slate-200"><?php echo htmlspecialchars($c['grupo_nombre'] ?? 'Sin grupo'); ?></span>
                                        <?php if (!empty($c['grupo_sede'])): ?>
                                            <span class="text-xs text-slate-400 dark:text-slate-500 block"><?php echo htmlspecialchars($c['grupo_sede']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="p-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                <span class="material-icons-outlined text-sm">person</span>
                                            </span>
                                            <span class="text-slate-700 dark:text-slate-300 font-medium text-sm"><?php echo htmlspecialchars($c['maestro_nombre'] ?? 'No asignado'); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300 max-w-sm">
                                        <p class="line-clamp-2 text-sm"><?php echo htmlspecialchars($c['objetivo'] ?? 'Sin objetivo específico'); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="<?php echo base_url('admin/cronogramas/' . $c['id_cronograma']); ?>"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-xl text-xs font-semibold transition border border-blue-200 dark:border-blue-800/60" title="Ver detalle">
                                            <span class="material-icons-outlined text-sm">visibility</span>
                                            Ver Plan
                                        </a>
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

<script>
const filterSearch = document.getElementById('filter-search');
const filterGrupo  = document.getElementById('filter-grupo');
const itemsCronograma = document.querySelectorAll('.item-cronograma');

function filtrarCronogramas() {
    const q = filterSearch.value.toLowerCase().trim();
    const g = filterGrupo.value;
    itemsCronograma.forEach(el => {
        const matchSearch = !q || el.getAttribute('data-text').includes(q);
        const matchGrupo  = !g  || el.getAttribute('data-grupo') === g;
        el.style.display = (matchSearch && matchGrupo) ? '' : 'none';
    });
}

if (filterSearch) filterSearch.addEventListener('input', filtrarCronogramas);
if (filterGrupo)  filterGrupo.addEventListener('change', filtrarCronogramas);
</script>

<?php require_once __DIR__ . '/../layout/administracion_pie.php'; ?>

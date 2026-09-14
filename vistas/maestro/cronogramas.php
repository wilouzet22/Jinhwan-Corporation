<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Cronogramas de Clase
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Planificación metodológica de sesiones por grupo, fechas y fases de entrenamiento.
                </p>
            </div>
            <div>
                <button onclick="openModal('modal-nuevo-cronograma')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                    <span class="material-icons-outlined text-base">add</span>
                    <span>Crear Cronograma</span>
                </button>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-icons-outlined text-sm">search</span>
                </div>
                <input type="text" id="filter-search" placeholder="Buscar por objetivo, grupo o día..." 
                       class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Grupo:</span>
                <select id="filter-grupo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors w-full md:w-64">
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
                            <th scope="col" class="px-4 py-3">Fecha & Día</th>
                            <th scope="col" class="px-4 py-3">Grupo / Sede</th>
                            <th scope="col" class="px-4 py-3">Maestro Responsable</th>
                            <th scope="col" class="px-4 py-3">Objetivo de la Clase</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
<?php
function fechaEspanolCompleta($fechaStr) {
    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $ts = strtotime($fechaStr);
    $diaSemana = $dias[date('w', $ts)];
    $dia = date('j', $ts);
    $mes = $meses[(int)date('n', $ts)];
    $anio = date('Y', $ts);
    return ['dia_semana' => $diaSemana, 'fecha_completa' => "$diaSemana, $dia de $mes de $anio", 'corta' => "$dia/$mes/$anio"];
}
?>

                        <?php if (empty($cronogramas)): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-outlined text-4xl block mb-2 opacity-50">event_note</span>
                                    No hay cronogramas creados aún.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cronogramas as $c): 
                                $fInfo = fechaEspanolCompleta($c['fecha']);
                            ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all item-cronograma"
                                    data-grupo="<?php echo htmlspecialchars($c['id_grupo']); ?>"
                                    data-text="<?php echo htmlspecialchars(strtolower(($c['objetivo'] ?? '') . ' ' . ($c['observaciones'] ?? '') . ' ' . ($c['maestro_nombre'] ?? '') . ' ' . $fInfo['fecha_completa'])); ?>">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="p-2 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-900/40">
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
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                        <?php echo htmlspecialchars($c['maestro_nombre'] ?? 'No asignado'); ?>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300 max-w-md">
                                        <p class="line-clamp-2"><?php echo htmlspecialchars($c['objetivo'] ?? 'Sin objetivo específico'); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="<?php echo base_url('maestro/cronogramas/' . $c['id_cronograma']); ?>" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-xl text-xs font-semibold transition border border-purple-200 dark:border-purple-800/60" title="Ver plan y fases">
                                                <span class="material-icons-outlined text-sm">visibility</span>
                                                Ver Plan
                                            </a>
                                            <form method="POST" action="<?php echo base_url('maestro/cronogramas/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este cronograma?')" class="inline">
                                                <input type="hidden" name="id_cronograma" value="<?php echo $c['id_cronograma']; ?>">
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition" title="Eliminar">
                                                    <span class="material-icons-outlined text-lg">delete</span>
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
</main>

<!-- Modal Nuevo Cronograma -->
<div id="modal-nuevo-cronograma" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-purple-600 dark:text-purple-400">event_note</span>
                Crear Nuevo Cronograma de Clase
            </h3>
            <button onclick="closeModal('modal-nuevo-cronograma')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/cronogramas/create'); ?>" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Grupo *</label>
                <select name="id_grupo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione grupo...</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?php echo $g['id_grupo']; ?>">
                            <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['nombre_sede'] ?? '') . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Selector de Día de la Semana con Fecha Exacta -->
            <div class="bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Día de la Clase (En la semana)</label>
                    <div class="flex items-center gap-1 text-xs">
                        <button type="button" onclick="cambiarSemana(-1)" class="p-1 text-slate-500 hover:text-purple-500 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition" title="Semana anterior">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <span id="semana-rango-label" class="font-medium text-slate-600 dark:text-slate-400 px-1">Semana actual</span>
                        <button type="button" onclick="cambiarSemana(1)" class="p-1 text-slate-500 hover:text-purple-500 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition" title="Semana siguiente">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Botones para cada día de la semana -->
                <div class="grid grid-cols-7 gap-1.5 text-center mb-3" id="contenedor-dias-semana">
                    <!-- Rellenado por JS: Lunes a Domingo con su fecha calculada -->
                </div>

                <!-- Input sincronizado con la fecha exacta YYYY-MM-DD -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-3 border-t border-slate-200 dark:border-slate-800 text-xs gap-2">
                    <div class="flex items-center gap-1.5 text-purple-600 dark:text-purple-400 font-semibold">
                        <span class="material-icons-outlined text-sm">event</span>
                        <span id="label-fecha-seleccionada">Selecciona un día</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-slate-400 text-[11px]">O fecha manual:</span>
                        <input type="date" id="input-fecha-modal" name="fecha" required value="<?php echo date('Y-m-d'); ?>"
                               class="px-2 py-1 border border-slate-200 dark:border-slate-700 rounded-lg text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Objetivo de la Sesión</label>
                <input type="text" name="objetivo" placeholder="Ej: Potencia de pateo y resistencia aeróbica..." 
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Observaciones Generales</label>
                <textarea name="observaciones" rows="3" placeholder="Requerimientos de material, notas previas..."
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-nuevo-cronograma')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    Crear y Configurar Fases
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    if (id === 'modal-nuevo-cronograma') {
        renderizarDiasSemana();
    }
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// --- Selector Interactivo de Días de la Semana ---
let offsetSemanas = 0;
let fechaSeleccionada = document.getElementById('input-fecha-modal').value || new Date().toISOString().split('T')[0];

const nombresDias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
const nombresDiasCompletos = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function obtenerLunesDeSemana(offset) {
    const hoy = new Date();
    const diaSemana = hoy.getDay(); // 0 es Domingo, 1 es Lunes
    const diff = (diaSemana === 0 ? -6 : 1) - diaSemana;
    const lunes = new Date(hoy);
    lunes.setDate(hoy.getDate() + diff + (offset * 7));
    lunes.setHours(0, 0, 0, 0);
    return lunes;
}

function formatearFechaISO(d) {
    const anio = d.getFullYear();
    const mes = String(d.getMonth() + 1).padStart(2, '0');
    const dia = String(d.getDate()).padStart(2, '0');
    return `${anio}-${mes}-${dia}`;
}

function cambiarSemana(delta) {
    offsetSemanas += delta;
    renderizarDiasSemana();
}

function seleccionarDia(fechaISO) {
    fechaSeleccionada = fechaISO;
    document.getElementById('input-fecha-modal').value = fechaISO;
    actualizarLabelFecha();
    renderizarDiasSemana();
}

function actualizarLabelFecha() {
    if (!fechaSeleccionada) return;
    const partes = fechaSeleccionada.split('-');
    const fechaObj = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
    const diaSemIndex = (fechaObj.getDay() + 6) % 7;
    const nombreDia = nombresDiasCompletos[diaSemIndex];
    const numDia = partes[2];
    const mes = nombresMeses[parseInt(partes[1]) - 1];
    const anio = partes[0];

    const label = document.getElementById('label-fecha-seleccionada');
    if (label) {
        label.innerText = `${nombreDia}, ${numDia} de ${mes} de ${anio}`;
    }
}

function renderizarDiasSemana() {
    const lunes = obtenerLunesDeSemana(offsetSemanas);
    const contenedor = document.getElementById('contenedor-dias-semana');
    if (!contenedor) return;

    contenedor.innerHTML = '';

    const domingo = new Date(lunes);
    domingo.setDate(lunes.getDate() + 6);

    const rangoLabel = document.getElementById('semana-rango-label');
    if (rangoLabel) {
        if (offsetSemanas === 0) {
            rangoLabel.innerText = 'Semana actual';
        } else if (offsetSemanas === 1) {
            rangoLabel.innerText = 'Próxima semana';
        } else if (offsetSemanas === -1) {
            rangoLabel.innerText = 'Semana pasada';
        } else {
            rangoLabel.innerText = `${lunes.getDate()} ${nombresMeses[lunes.getMonth()].slice(0,3)} - ${domingo.getDate()} ${nombresMeses[domingo.getMonth()].slice(0,3)}`;
        }
    }

    const hoyISO = formatearFechaISO(new Date());

    for (let i = 0; i < 7; i++) {
        const diaActual = new Date(lunes);
        diaActual.setDate(lunes.getDate() + i);
        const iso = formatearFechaISO(diaActual);
        const esSeleccionado = (iso === fechaSeleccionada);
        const esHoy = (iso === hoyISO);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.onclick = () => seleccionarDia(iso);

        let clases = 'p-2 rounded-xl text-xs flex flex-col items-center justify-center transition border ';
        if (esSeleccionado) {
            clases += 'bg-purple-600 text-white font-bold border-purple-500 shadow-sm scale-105';
        } else if (esHoy) {
            clases += 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800/50 hover:bg-purple-100 font-semibold';
        } else {
            clases += 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800';
        }

        btn.className = clases;
        btn.innerHTML = `
            <span class="text-[10px] uppercase font-bold tracking-tight opacity-90">${nombresDias[i]}</span>
            <span class="text-sm font-extrabold mt-0.5">${diaActual.getDate()}</span>
        `;
        contenedor.appendChild(btn);
    }

    actualizarLabelFecha();
}

const inputFechaModal = document.getElementById('input-fecha-modal');
if (inputFechaModal) {
    inputFechaModal.addEventListener('change', function() {
        if (this.value) {
            fechaSeleccionada = this.value;
            const fechaObj = new Date(this.value + 'T00:00:00');
            const hoyLunes = obtenerLunesDeSemana(0);
            const diffDias = Math.floor((fechaObj - hoyLunes) / (1000 * 60 * 60 * 24));
            offsetSemanas = Math.floor(diffDias / 7);
            renderizarDiasSemana();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    renderizarDiasSemana();
});

const filterSearch = document.getElementById('filter-search');
const filterGrupo = document.getElementById('filter-grupo');
const itemsCronograma = document.querySelectorAll('.item-cronograma');

function filtrarCronogramas() {
    const q = filterSearch.value.toLowerCase().trim();
    const g = filterGrupo.value;

    itemsCronograma.forEach(el => {
        const text = el.getAttribute('data-text');
        const grupo = el.getAttribute('data-grupo');

        const matchSearch = !q || text.includes(q);
        const matchGrupo = !g || grupo === g;

        el.style.display = (matchSearch && matchGrupo) ? '' : 'none';
    });
}

if (filterSearch) filterSearch.addEventListener('input', filtrarCronogramas);
if (filterGrupo) filterGrupo.addEventListener('change', filtrarCronogramas);
</script>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

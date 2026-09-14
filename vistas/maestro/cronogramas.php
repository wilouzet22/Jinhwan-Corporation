<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Cronogramas de Clase</h1>
            <p class="text-sm text-gray-500">Planificación metodológica de sesiones por grupo y fechas.</p>
        </div>
        <button onclick="openModal('modal-nuevo-cronograma')" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition">
            <span class="material-icons-outlined text-sm">add</span>
            Crear Cronograma
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 mb-6 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex-1 w-full md:w-auto relative">
            <span class="material-icons-outlined absolute left-3 top-2.5 text-gray-400">search</span>
            <input type="text" id="filter-search" placeholder="Buscar por objetivo u observaciones..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none text-sm">
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Grupo:</span>
            <select id="filter-grupo" class="w-full md:w-56 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
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
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4">Grupo / Sede</th>
                        <th class="py-3 px-4">Maestro Responsable</th>
                        <th class="py-3 px-4">Objetivo de la Clase</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
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
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <span class="material-icons-outlined text-4xl block mb-2 text-gray-300">event_note</span>
                                No hay cronogramas creados aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cronogramas as $c): 
                            $fInfo = fechaEspanolCompleta($c['fecha']);
                        ?>
                            <tr class="hover:bg-gray-50 transition item-cronograma"
                                data-grupo="<?php echo htmlspecialchars($c['id_grupo']); ?>"
                                data-text="<?php echo htmlspecialchars(strtolower(($c['objetivo'] ?? '') . ' ' . ($c['observaciones'] ?? '') . ' ' . ($c['maestro_nombre'] ?? '') . ' ' . $fInfo['fecha_completa'])); ?>">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-purple-50 text-purple-700 rounded-lg material-icons-outlined text-sm">calendar_today</span>
                                        <div>
                                            <span class="font-bold text-gray-900 block text-sm"><?php echo $fInfo['dia_semana']; ?></span>
                                            <span class="text-xs text-gray-500 font-medium"><?php echo $fInfo['fecha_completa']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($c['grupo_nombre'] ?? 'Sin grupo'); ?></span>
                                    <?php if (!empty($c['grupo_sede'])): ?>
                                        <span class="text-xs text-gray-400 block"><?php echo htmlspecialchars($c['grupo_sede']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    <?php echo htmlspecialchars($c['maestro_nombre'] ?? 'No asignado'); ?>
                                </td>
                                <td class="py-3 px-4 text-gray-700 max-w-md">
                                    <p class="line-clamp-2"><?php echo htmlspecialchars($c['objetivo'] ?? 'Sin objetivo específico'); ?></p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo base_url('maestro/cronogramas/' . $c['id_cronograma']); ?>" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg text-xs font-semibold transition" title="Ver fases y ejercicios">
                                            <span class="material-icons-outlined text-sm">visibility</span>
                                            Ver Plan
                                        </a>
                                        <form method="POST" action="<?php echo base_url('maestro/cronogramas/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este cronograma completo?')" class="inline">
                                            <input type="hidden" name="id_cronograma" value="<?php echo $c['id_cronograma']; ?>">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition" title="Eliminar">
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

<!-- Modal Nuevo Cronograma -->
<div id="modal-nuevo-cronograma" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons-outlined text-purple-600">event_note</span>
                Crear Nuevo Cronograma de Clase
            </h3>
            <button onclick="closeModal('modal-nuevo-cronograma')" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/cronogramas/create'); ?>" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grupo *</label>
                <select name="id_grupo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione grupo...</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?php echo $g['id_grupo']; ?>">
                            <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['nombre_sede'] ?? '') . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Selector de Día de la Semana con Fecha Exacta -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Día de la Clase (Seleccionar en la semana)</label>
                    <div class="flex items-center gap-1 text-xs">
                        <button type="button" onclick="cambiarSemana(-1)" class="p-1 text-gray-500 hover:text-purple-600 hover:bg-white rounded transition" title="Semana anterior">
                            <span class="material-icons-outlined text-sm">chevron_left</span>
                        </button>
                        <span id="semana-rango-label" class="font-medium text-gray-600 px-1">Semana actual</span>
                        <button type="button" onclick="cambiarSemana(1)" class="p-1 text-gray-500 hover:text-purple-600 hover:bg-white rounded transition" title="Semana siguiente">
                            <span class="material-icons-outlined text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Botones para cada día de la semana -->
                <div class="grid grid-cols-7 gap-1.5 text-center mb-3" id="contenedor-dias-semana">
                    <!-- Rellenado por JS: Lunes a Domingo con su fecha calculada -->
                </div>

                <!-- Input oculto o sincronizado con la fecha exacta YYYY-MM-DD -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-200 text-xs">
                    <div class="flex items-center gap-1.5 text-purple-700 font-semibold">
                        <span class="material-icons-outlined text-sm">event</span>
                        <span id="label-fecha-seleccionada">Selecciona un día</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-gray-400">O ingresa fecha:</span>
                        <input type="date" id="input-fecha-modal" name="fecha" required value="<?php echo date('Y-m-d'); ?>"
                               class="px-2 py-1 border border-gray-300 rounded text-xs bg-white focus:outline-none focus:ring-1 focus:ring-purple-500">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo de la Sesión</label>
                <input type="text" name="objetivo" placeholder="Ej: Mejorar potencia de pateo y resistencia aeróbica..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones Generales</label>
                <textarea name="observaciones" rows="3" placeholder="Requerimientos de material, notas previas..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-nuevo-cronograma')" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
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
    const diff = (diaSemana === 0 ? -6 : 1) - diaSemana; // Llevar a Lunes
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
    const diaSemIndex = (fechaObj.getDay() + 6) % 7; // Lunes = 0, Domingo = 6
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

        let clases = 'p-2 rounded-lg text-xs flex flex-col items-center justify-center transition border ';
        if (esSeleccionado) {
            clases += 'bg-purple-600 text-white font-bold border-purple-700 shadow-sm scale-105';
        } else if (esHoy) {
            clases += 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100 font-semibold';
        } else {
            clases += 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100';
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

// Sincronización cuando se cambia el input type="date" manualmente
const inputFechaModal = document.getElementById('input-fecha-modal');
if (inputFechaModal) {
    inputFechaModal.addEventListener('change', function() {
        if (this.value) {
            fechaSeleccionada = this.value;
            // Ajustar offset si la fecha seleccionada no está en la semana mostrada
            const fechaObj = new Date(this.value + 'T00:00:00');
            const hoyLunes = obtenerLunesDeSemana(0);
            const diffDias = Math.floor((fechaObj - hoyLunes) / (1000 * 60 * 60 * 24));
            offsetSemanas = Math.floor(diffDias / 7);
            renderizarDiasSemana();
        }
    });
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', () => {
    renderizarDiasSemana();
});

// --- Filtros de búsqueda en la tabla ---
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

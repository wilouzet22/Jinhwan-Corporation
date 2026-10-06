<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-1 flex-grow w-full container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
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

<!-- Modal Nuevo Cronograma (Ventana Unificada Dividida al 50/50: Izquierda = Biblioteca, Derecha = Formulario) -->
<div id="modal-nuevo-cronograma" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-xs p-2 sm:p-4 md:p-6 flex items-center justify-center overflow-y-auto">
    <div class="w-full max-w-7xl h-[92vh] max-h-[92vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row overflow-hidden my-auto relative">

        <!-- MITAD IZQUIERDA (50%): BIBLIOTECA DE EJERCICIOS CON BOTONES +INICIAL / +CENTRAL / +FINAL -->
        <div class="w-full md:w-1/2 flex flex-col h-full border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-50/50 dark:bg-slate-950/30">
            <!-- Header Izquierdo (Biblioteca) -->
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50 dark:bg-slate-950">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                        <span class="material-icons-outlined text-xl block">fitness_center</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base">Biblioteca de Ejercicios</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            <span id="label-total-biblioteca"><?php echo count($biblioteca ?? []); ?></span> ejercicios para incorporar a las fases
                        </p>
                    </div>
                </div>
                <a href="<?php echo base_url('maestro/ejercicios'); ?>" target="_blank" class="text-xs font-semibold text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1" title="Gestionar biblioteca en otra pestaña">
                    <span>Gestionar</span>
                    <span class="material-icons-outlined text-sm">open_in_new</span>
                </a>
            </div>

            <!-- Buscador y Filtro por Categoría -->
            <div class="p-3.5 border-b border-slate-200/80 dark:border-slate-800/80 space-y-2 shrink-0 bg-white dark:bg-slate-900/60">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-sm">search</span>
                    </div>
                    <input type="text" id="busqueda-biblioteca-modal" placeholder="Buscar por nombre, técnica o músculo..."
                           class="block w-full pl-9 pr-3 py-2 text-xs border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                </div>

                <div class="flex items-center gap-2">
                    <select id="filtro-categoria-modal" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-1.5 px-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors w-full">
                        <option value="">Todas las categorías</option>
                        <option value="Fuerza general">Fuerza general</option>
                        <option value="Fuerza Especifica">Fuerza Específica</option>
                        <option value="Pliometria">Pliometría</option>
                        <option value="Coordinación">Coordinación</option>
                        <option value="Resistencia Aerobica">Resistencia Aeróbica</option>
                        <option value="Resistencia anaerobica">Resistencia Anaeróbica</option>
                        <option value="Combate">Combate</option>
                        <option value="Flexibilidad">Flexibilidad</option>
                        <option value="Velocidad">Velocidad</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <!-- Listado con Scroll de la Biblioteca -->
            <div id="contenedor-biblioteca-ejercicios" class="overflow-y-auto flex-1 p-3.5 space-y-2.5">
                <?php if (empty($biblioteca)): ?>
                    <div class="text-center py-12 text-slate-400 dark:text-slate-500 text-xs">
                        <span class="material-icons-outlined text-4xl block mb-2 opacity-40">fitness_center</span>
                        No hay ejercicios registrados en la biblioteca aún.<br>
                        <a href="<?php echo base_url('maestro/ejercicios'); ?>" target="_blank" class="text-purple-500 underline mt-1 inline-block">Registrar ejercicios</a>
                    </div>
                <?php else: ?>
                    <?php 
                    function getBadgeTipoEstilo($tipo) {
                        $t = mb_strtolower($tipo ?? '', 'UTF-8');
                        if (strpos($t, 'fuerza general') !== false) return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40';
                        if (strpos($t, 'fuerza') !== false) return 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/40';
                        if (strpos($t, 'pliometr') !== false) return 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800/40';
                        if (strpos($t, 'coordinac') !== false) return 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/40';
                        if (strpos($t, 'resistencia aer') !== false) return 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-950/40 dark:text-teal-300 dark:border-teal-800/40';
                        if (strpos($t, 'resistencia anaer') !== false) return 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/40';
                        if (strpos($t, 'combate') !== false) return 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800/40';
                        if (strpos($t, 'flexibilidad') !== false) return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40';
                        if (strpos($t, 'velocidad') !== false) return 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40';
                        return 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                    }

                    foreach ($biblioteca as $b): 
                        $tipoClase = getBadgeTipoEstilo($b['tipo']);
                    ?>
                        <div class="card-ejercicio-biblioteca p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-300 dark:hover:border-purple-700/60 shadow-2xs transition-all"
                             data-nombre="<?php echo htmlspecialchars(mb_strtolower($b['nombre'] . ' ' . ($b['explicacion'] ?? ''), 'UTF-8')); ?>"
                             data-tipo="<?php echo htmlspecialchars($b['tipo']); ?>">
                            
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h6 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">
                                    <?php echo htmlspecialchars($b['nombre']); ?>
                                </h6>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 <?php echo $tipoClase; ?>">
                                    <?php echo htmlspecialchars($b['tipo']); ?>
                                </span>
                            </div>

                            <?php if (!empty($b['explicacion'])): ?>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-2 line-clamp-2">
                                    <?php echo htmlspecialchars($b['explicacion']); ?>
                                </p>
                            <?php endif; ?>

                            <!-- Botones de Acción para Añadir a las 3 Fases -->
                            <div class="flex items-center gap-1.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight mr-auto">Agregar a:</span>
                                
                                <!-- Botón + Inicial -->
                                <button type="button" 
                                        onclick="agregarEjercicioAFase(<?php echo $b['id_ejercicio']; ?>, <?php echo htmlspecialchars(json_encode($b['nombre'])); ?>, <?php echo htmlspecialchars(json_encode($b['tipo'])); ?>, 'inicial')"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 hover:bg-amber-200 text-amber-800 dark:bg-amber-950/60 dark:hover:bg-amber-900/80 dark:text-amber-300 border border-amber-300/80 dark:border-amber-800/60 transition-all flex items-center gap-1"
                                        title="Agregar a Parte Inicial (Calentamiento)">
                                    <span class="material-icons-outlined text-xs">add</span>
                                    <span>Inicial</span>
                                </button>

                                <!-- Botón + Central -->
                                <button type="button" 
                                        onclick="agregarEjercicioAFase(<?php echo $b['id_ejercicio']; ?>, <?php echo htmlspecialchars(json_encode($b['nombre'])); ?>, <?php echo htmlspecialchars(json_encode($b['tipo'])); ?>, 'central')"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-purple-100 hover:bg-purple-200 text-purple-800 dark:bg-purple-950/60 dark:hover:bg-purple-900/80 dark:text-purple-300 border border-purple-300/80 dark:border-purple-800/60 transition-all flex items-center gap-1"
                                        title="Agregar a Parte Central (Objetivo)">
                                    <span class="material-icons-outlined text-xs">add</span>
                                    <span>Central</span>
                                </button>

                                <!-- Botón + Final -->
                                <button type="button" 
                                        onclick="agregarEjercicioAFase(<?php echo $b['id_ejercicio']; ?>, <?php echo htmlspecialchars(json_encode($b['nombre'])); ?>, <?php echo htmlspecialchars(json_encode($b['tipo'])); ?>, 'final')"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-100 hover:bg-emerald-200 text-emerald-800 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/80 dark:text-emerald-300 border border-emerald-300/80 dark:border-emerald-800/60 transition-all flex items-center gap-1"
                                        title="Agregar a Parte Final (Vuelta a la calma)">
                                    <span class="material-icons-outlined text-xs">add</span>
                                    <span>Final</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Toast / Feedback Visual Flotante -->
            <div id="toast-biblioteca-feedback" class="hidden mx-3 mb-3 p-2.5 rounded-xl bg-purple-600 text-white text-xs font-semibold flex items-center justify-between shadow-lg transition-all animate-bounce">
                <span id="toast-biblioteca-msg" class="flex items-center gap-1.5">
                    <span class="material-icons-outlined text-sm">check_circle</span>
                    Ejercicio añadido a la fase
                </span>
            </div>
        </div>

        <!-- MITAD DERECHA (50%): FORMULARIO DEL CRONOGRAMA DE CLASE Y SUS 3 FASES -->
        <div class="w-full md:w-1/2 flex flex-col h-full overflow-hidden bg-white dark:bg-slate-900">
            <!-- Header Derecho (Formulario) -->
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0 bg-slate-50 dark:bg-slate-950">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                        <span class="material-icons-outlined text-xl block">calendar_today</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base">Crear Cronograma de Clase</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Los ejercicios elegidos a la izquierda se incorporan aquí.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-nuevo-cronograma')" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <span class="material-icons-outlined text-xl">close</span>
                </button>
            </div>

            <!-- Formulario con Scroll -->
            <form id="form-nuevo-cronograma" method="POST" action="<?php echo base_url('maestro/cronogramas/create'); ?>" class="overflow-y-auto flex-1 p-5 space-y-4">
                
                <!-- Selector de Grupo -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Grupo Asignado <span class="text-red-500">*</span>
                    </label>
                    <select name="id_grupo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="">Seleccione grupo...</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id_grupo']; ?>">
                                <?php echo htmlspecialchars($g['nombre'] . ' (' . ($g['nombre_sede'] ?? '') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selector Interactivo de Días de la Semana -->
                <div class="bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-2.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Día de la Clase <span class="text-red-500">*</span>
                        </label>
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

                    <!-- Botones para cada día de la semana (Lunes a Domingo) -->
                    <div id="contenedor-dias-semana" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 6px;" class="text-center mb-2.5">
                        <!-- Rellenado por JS -->
                    </div>

                    <!-- Input sincronizado con fecha exacta -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-2.5 border-t border-slate-200 dark:border-slate-800 text-xs gap-2">
                        <div class="flex items-center gap-1.5 text-purple-600 dark:text-purple-400 font-semibold">
                            <span class="material-icons-outlined text-sm">event</span>
                            <span id="label-fecha-seleccionada">Selecciona un día</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-slate-400 text-[11px]">O fecha:</span>
                            <input type="date" id="input-fecha-modal" name="fecha" required value="<?php echo date('Y-m-d'); ?>"
                                   class="px-2 py-1 border border-slate-200 dark:border-slate-700 rounded-lg text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-purple-500">
                        </div>
                    </div>
                </div>

                <!-- Objetivo General de la Sesión -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Objetivo General de la Sesión <span class="text-red-500">*</span>
                    </label>
                    <textarea name="objetivo" rows="2" required placeholder="Ej: Potencia en pateo ofensivo y velocidad de reacción en combate..." 
                              class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                </div>

                <!-- SECCIONES DE LAS 3 FASES -->
                <div class="space-y-3.5 pt-2 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-icons-outlined text-purple-600 dark:text-purple-400 text-sm">view_timeline</span>
                            Fases del Entrenamiento (3 Secciones)
                        </h4>
                        <span class="text-[11px] text-slate-400">Usa los botones de la izquierda</span>
                    </div>

                    <!-- 1. PARTE INICIAL (CALENTAMIENTO) -->
                    <div class="rounded-2xl border border-amber-200 dark:border-amber-900/40 bg-amber-50/20 dark:bg-amber-950/10 p-3.5 transition-colors">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="p-1 bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-400 rounded-lg material-icons-outlined text-base">wb_sunny</span>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Parte Inicial — Calentamiento</h5>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Movilidad articular, activación cardiovascular y acondicionamiento previo.</p>
                                </div>
                            </div>
                            <span id="badge-count-inicial" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">0 ejercicios</span>
                        </div>

                        <!-- Lista Dinámica de Ejercicios Inicial -->
                        <div id="lista-fase-inicial" class="space-y-2 mt-2.5">
                            <div class="p-3 border border-dashed border-amber-300 dark:border-amber-900/60 rounded-xl text-center text-xs text-amber-700/70 dark:text-amber-400/70 bg-white/60 dark:bg-slate-900/40 empty-state">
                                <span class="material-icons-outlined text-lg block mb-0.5 opacity-60">add_circle_outline</span>
                                Pulsa <strong class="text-amber-800 dark:text-amber-300">+ Inicial</strong> en la biblioteca de la izquierda para agregar aquí.
                            </div>
                        </div>
                    </div>

                    <!-- 2. PARTE CENTRAL (OBJETIVO DE LA SESIÓN) -->
                    <div class="rounded-2xl border border-purple-200 dark:border-purple-900/40 bg-purple-50/20 dark:bg-purple-950/10 p-3.5 transition-colors">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="p-1 bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-400 rounded-lg material-icons-outlined text-base">track_changes</span>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Parte Central — Objetivo Principal</h5>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Desarrollo técnico, combate, potencia y cumplimiento del objetivo.</p>
                                </div>
                            </div>
                            <span id="badge-count-central" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">0 ejercicios</span>
                        </div>

                        <!-- Lista Dinámica de Ejercicios Central -->
                        <div id="lista-fase-central" class="space-y-2 mt-2.5">
                            <div class="p-3 border border-dashed border-purple-300 dark:border-purple-900/60 rounded-xl text-center text-xs text-purple-700/70 dark:text-purple-400/70 bg-white/60 dark:bg-slate-900/40 empty-state">
                                <span class="material-icons-outlined text-lg block mb-0.5 opacity-60">add_circle_outline</span>
                                Pulsa <strong class="text-purple-800 dark:text-purple-300">+ Central</strong> en la biblioteca de la izquierda para agregar aquí.
                            </div>
                        </div>
                    </div>

                    <!-- 3. PARTE FINAL (VUELTA A LA CALMA) -->
                    <div class="rounded-2xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/20 dark:bg-emerald-950/10 p-3.5 transition-colors">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="p-1 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 rounded-lg material-icons-outlined text-base">self_improvement</span>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Parte Final — Vuelta a la Calma</h5>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Estiramientos, relajación, meditación y recuperación física.</p>
                                </div>
                            </div>
                            <span id="badge-count-final" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">0 ejercicios</span>
                        </div>

                        <!-- Lista Dinámica de Ejercicios Final -->
                        <div id="lista-fase-final" class="space-y-2 mt-2.5">
                            <div class="p-3 border border-dashed border-emerald-300 dark:border-emerald-900/60 rounded-xl text-center text-xs text-emerald-700/70 dark:text-emerald-400/70 bg-white/60 dark:bg-slate-900/40 empty-state">
                                <span class="material-icons-outlined text-lg block mb-0.5 opacity-60">add_circle_outline</span>
                                Pulsa <strong class="text-emerald-800 dark:text-emerald-300">+ Final</strong> en la biblioteca de la izquierda para agregar aquí.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra de Acciones Fija del Formulario -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-200 dark:border-slate-800 sticky bottom-0 bg-white dark:bg-slate-900 py-2">
                    <button type="button" onclick="closeModal('modal-nuevo-cronograma')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        <span class="material-icons-outlined text-base">check_circle</span>
                        Crear Cronograma
                    </button>
                </div>
            </form>
        </div>

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

// --- Manejo Dinámico de Ejercicios por Fase en el Formulario ---
let indexEjercicioGlobal = 0;
const fasesState = {
    inicial: [],
    central: [],
    final: []
};

function getBadgeEstiloJS(tipo) {
    const t = (tipo || '').toLowerCase();
    if (t.includes('fuerza general')) return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40';
    if (t.includes('fuerza')) return 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/40';
    if (t.includes('pliometr')) return 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-800/40';
    if (t.includes('coordinac')) return 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/40';
    if (t.includes('resistencia aer')) return 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-950/40 dark:text-teal-300 dark:border-teal-800/40';
    if (t.includes('resistencia anaer')) return 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/40';
    if (t.includes('combate')) return 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800/40';
    if (t.includes('flexibilidad')) return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40';
    if (t.includes('velocidad')) return 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40';
    return 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
}

function agregarEjercicioAFase(id, nombre, tipo, fase) {
    const idx = indexEjercicioGlobal++;
    fasesState[fase].push({
        idx: idx,
        id_ejercicio: id,
        nombre: nombre,
        tipo: tipo,
        series_o_tiempo: '',
        observaciones_especificas: ''
    });

    renderizarFase(fase);
    mostrarToastFeedback(nombre, fase);
}

function quitarEjercicioDeFase(fase, idx) {
    fasesState[fase] = fasesState[fase].filter(item => item.idx !== idx);
    renderizarFase(fase);
}

function renderizarFase(fase) {
    const contenedor = document.getElementById(`lista-fase-${fase}`);
    const badge = document.getElementById(`badge-count-${fase}`);
    const items = fasesState[fase];

    if (badge) {
        badge.innerText = `${items.length} ejercicio${items.length === 1 ? '' : 's'}`;
    }

    if (!contenedor) return;

    if (items.length === 0) {
        const placeHolders = {
            'inicial': 'Pulsa <strong class="text-amber-800 dark:text-amber-300">+ Inicial</strong> en la biblioteca para agregar ejercicios de calentamiento.',
            'central': 'Pulsa <strong class="text-purple-800 dark:text-purple-300">+ Central</strong> en la biblioteca para agregar ejercicios del objetivo.',
            'final': 'Pulsa <strong class="text-emerald-800 dark:text-emerald-300">+ Final</strong> en la biblioteca para agregar estiramientos o relajación.'
        };
        contenedor.innerHTML = `
            <div class="p-4 border border-dashed border-slate-300 dark:border-slate-800 rounded-xl text-center text-xs text-slate-500 dark:text-slate-400 bg-white/60 dark:bg-slate-900/40">
                <span class="material-icons-outlined text-xl block mb-1 opacity-60">add_circle_outline</span>
                ${placeHolders[fase] || 'Sin ejercicios seleccionados'}
            </div>
        `;
        return;
    }

    let html = '';
    items.forEach((item, i) => {
        const badgeClass = getBadgeEstiloJS(item.tipo);
        html += `
            <div class="p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-2 transition-all">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-xs font-bold text-slate-400">#${i + 1}</span>
                        <span class="text-xs font-bold text-slate-900 dark:text-white truncate">${item.nombre}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 ${badgeClass}">${item.tipo}</span>
                    </div>
                    <button type="button" onclick="quitarEjercicioDeFase('${fase}', ${item.idx})" class="p-1 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition" title="Quitar de esta fase">
                        <span class="material-icons-outlined text-base">close</span>
                    </button>
                </div>

                <!-- Inputs Ocultos y Editables -->
                <input type="hidden" name="ejercicios[${item.idx}][id_ejercicio]" value="${item.id_ejercicio}">
                <input type="hidden" name="ejercicios[${item.idx}][fase]" value="${fase}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                    <input type="text" name="ejercicios[${item.idx}][series_o_tiempo]" 
                           value="${item.series_o_tiempo}" 
                           oninput="actualizarSeriesState('${fase}', ${item.idx}, this.value)"
                           placeholder="Series / Tiempo (ej: 3 series x 15 reps o 4 min)"
                           class="w-full text-xs px-2.5 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-purple-500">
                    <input type="text" name="ejercicios[${item.idx}][observaciones_especificas]" 
                           value="${item.observaciones_especificas}" 
                           oninput="actualizarObsState('${fase}', ${item.idx}, this.value)"
                           placeholder="Detalle o instrucción técnica opcional..."
                           class="w-full text-xs px-2.5 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-purple-500">
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = html;
}

function actualizarSeriesState(fase, idx, val) {
    const it = fasesState[fase].find(item => item.idx === idx);
    if (it) it.series_o_tiempo = val;
}

function actualizarObsState(fase, idx, val) {
    const it = fasesState[fase].find(item => item.idx === idx);
    if (it) it.observaciones_especificas = val;
}

let toastTimer = null;
function mostrarToastFeedback(nombre, fase) {
    const toast = document.getElementById('toast-biblioteca-feedback');
    const msg = document.getElementById('toast-biblioteca-msg');
    if (!toast || !msg) return;

    const nombresFase = { 'inicial': 'Parte Inicial (Calentamiento)', 'central': 'Parte Central (Objetivo)', 'final': 'Parte Final (Vuelta a la calma)' };
    msg.innerHTML = `<span class="material-icons-outlined text-sm">check_circle</span> <span><strong>${nombre}</strong> añadido a ${nombresFase[fase] || fase}</span>`;
    
    toast.classList.remove('hidden');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toast.classList.add('hidden');
    }, 2400);
}

// --- Buscador y Filtro de la Biblioteca de Ejercicios en el Modal ---
const inputBusquedaBib = document.getElementById('busqueda-biblioteca-modal');
const selectCategoriaBib = document.getElementById('filtro-categoria-modal');
const cardsBib = document.querySelectorAll('.card-ejercicio-biblioteca');

function filtrarBibliotecaModal() {
    const q = (inputBusquedaBib ? inputBusquedaBib.value : '').toLowerCase().trim();
    const cat = (selectCategoriaBib ? selectCategoriaBib.value : '').toLowerCase().trim();
    let visibles = 0;

    cardsBib.forEach(card => {
        const nombre = (card.getAttribute('data-nombre') || '').toLowerCase();
        const tipo = (card.getAttribute('data-tipo') || '').toLowerCase();

        const matchQ = !q || nombre.includes(q);
        const matchCat = !cat || tipo.includes(cat);

        if (matchQ && matchCat) {
            card.style.display = '';
            visibles++;
        } else {
            card.style.display = 'none';
        }
    });

    const labelTotal = document.getElementById('label-total-biblioteca');
    if (labelTotal) labelTotal.innerText = visibles;
}

if (inputBusquedaBib) inputBusquedaBib.addEventListener('input', filtrarBibliotecaModal);
if (selectCategoriaBib) selectCategoriaBib.addEventListener('change', filtrarBibliotecaModal);

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

// --- Filtros de la Tabla Principal de Cronogramas ---
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

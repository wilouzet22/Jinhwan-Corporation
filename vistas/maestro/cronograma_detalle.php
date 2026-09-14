<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<div class="p-6">
    <!-- Navegación y Encabezado -->
    <div class="mb-6">
        <a href="<?php echo base_url('maestro/cronogramas'); ?>" class="inline-flex items-center gap-1 text-sm font-medium text-purple-600 hover:text-purple-800 transition mb-3">
            <span class="material-icons-outlined text-sm">arrow_back</span>
            Volver a Cronogramas
        </a>
        
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                            <?php echo htmlspecialchars($cronograma['grupo_nombre'] ?? 'Grupo'); ?>
                        </span>
                        <?php if (!empty($cronograma['grupo_sede'])): ?>
                            <span class="text-xs text-gray-500">• <?php echo htmlspecialchars($cronograma['grupo_sede']); ?></span>
                        <?php endif; ?>
                        <span class="text-xs text-gray-400">• Instructor: <?php echo htmlspecialchars($cronograma['maestro_nombre'] ?? 'N/A'); ?></span>
                    </div>
<?php
$diasEspanol = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$mesesEspanol = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$tsCronograma = strtotime($cronograma['fecha']);
$diaSemanaStr = $diasEspanol[date('w', $tsCronograma)];
$diaNumStr = date('j', $tsCronograma);
$mesStr = $mesesEspanol[(int)date('n', $tsCronograma)];
$anioStr = date('Y', $tsCronograma);
$fechaTextoCompleto = "$diaSemanaStr, $diaNumStr de $mesStr de $anioStr";
?>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Sesión del <?php echo $fechaTextoCompleto; ?>
                    </h1>
                </div>
            </div>

            <?php if (!empty($cronograma['objetivo']) || !empty($cronograma['observaciones'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100 text-sm">
                    <?php if (!empty($cronograma['objetivo'])): ?>
                        <div>
                            <span class="font-semibold text-gray-700 block">Objetivo Principal:</span>
                            <p class="text-gray-600 mt-0.5"><?php echo nl2br(htmlspecialchars($cronograma['objetivo'])); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($cronograma['observaciones'])): ?>
                        <div>
                            <span class="font-semibold text-gray-700 block">Observaciones:</span>
                            <p class="text-gray-600 mt-0.5"><?php echo nl2br(htmlspecialchars($cronograma['observaciones'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Fases de la Sesión de Entrenamiento -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FASE INICIAL -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full">
            <div class="p-4 border-b border-gray-100 bg-amber-50 rounded-t-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-amber-100 text-amber-700 rounded-lg material-icons-outlined text-sm">wb_sunny</span>
                    <div>
                        <h2 class="font-bold text-gray-800 text-base">Fase Inicial</h2>
                        <span class="text-xs text-amber-700">Calentamiento y movilidad</span>
                    </div>
                </div>
                <button onclick="abrirModalAgregar('inicial')" class="p-1 text-amber-700 hover:bg-amber-100 rounded-lg transition" title="Agregar ejercicio">
                    <span class="material-icons-outlined text-xl">add_circle</span>
                </button>
            </div>
            
            <div class="p-4 flex-1 space-y-3">
                <?php if (empty($fases['inicial'])): ?>
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <span class="material-icons-outlined text-3xl block mb-1 text-gray-300">fitness_center</span>
                        Sin ejercicios asignados
                    </div>
                <?php else: ?>
                    <?php foreach ($fases['inicial'] as $item): ?>
                        <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="p-3 border-t border-gray-50">
                <button onclick="abrirModalAgregar('inicial')" class="w-full py-2 border border-dashed border-amber-300 text-amber-700 hover:bg-amber-50 rounded-lg text-xs font-medium flex items-center justify-center gap-1 transition">
                    <span class="material-icons-outlined text-sm">add</span>
                    Agregar Ejercicio
                </button>
            </div>
        </div>

        <!-- FASE CENTRAL -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full">
            <div class="p-4 border-b border-gray-100 bg-purple-50 rounded-t-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-purple-100 text-purple-700 rounded-lg material-icons-outlined text-sm">sports_martial_arts</span>
                    <div>
                        <h2 class="font-bold text-gray-800 text-base">Fase Central</h2>
                        <span class="text-xs text-purple-700">Técnica, combate y carga</span>
                    </div>
                </div>
                <button onclick="abrirModalAgregar('central')" class="p-1 text-purple-700 hover:bg-purple-100 rounded-lg transition" title="Agregar ejercicio">
                    <span class="material-icons-outlined text-xl">add_circle</span>
                </button>
            </div>
            
            <div class="p-4 flex-1 space-y-3">
                <?php if (empty($fases['central'])): ?>
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <span class="material-icons-outlined text-3xl block mb-1 text-gray-300">fitness_center</span>
                        Sin ejercicios asignados
                    </div>
                <?php else: ?>
                    <?php foreach ($fases['central'] as $item): ?>
                        <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="p-3 border-t border-gray-50">
                <button onclick="abrirModalAgregar('central')" class="w-full py-2 border border-dashed border-purple-300 text-purple-700 hover:bg-purple-50 rounded-lg text-xs font-medium flex items-center justify-center gap-1 transition">
                    <span class="material-icons-outlined text-sm">add</span>
                    Agregar Ejercicio
                </button>
            </div>
        </div>

        <!-- FASE FINAL -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full">
            <div class="p-4 border-b border-gray-100 bg-emerald-50 rounded-t-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg material-icons-outlined text-sm">self_improvement</span>
                    <div>
                        <h2 class="font-bold text-gray-800 text-base">Fase Final</h2>
                        <span class="text-xs text-emerald-700">Vuelta a la calma y estiramiento</span>
                    </div>
                </div>
                <button onclick="abrirModalAgregar('final')" class="p-1 text-emerald-700 hover:bg-emerald-100 rounded-lg transition" title="Agregar ejercicio">
                    <span class="material-icons-outlined text-xl">add_circle</span>
                </button>
            </div>
            
            <div class="p-4 flex-1 space-y-3">
                <?php if (empty($fases['final'])): ?>
                    <div class="text-center py-8 text-gray-400 text-sm">
                        <span class="material-icons-outlined text-3xl block mb-1 text-gray-300">fitness_center</span>
                        Sin ejercicios asignados
                    </div>
                <?php else: ?>
                    <?php foreach ($fases['final'] as $item): ?>
                        <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="p-3 border-t border-gray-50">
                <button onclick="abrirModalAgregar('final')" class="w-full py-2 border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 rounded-lg text-xs font-medium flex items-center justify-center gap-1 transition">
                    <span class="material-icons-outlined text-sm">add</span>
                    Agregar Ejercicio
                </button>
            </div>
        </div>

    </div>
</div>

<?php
function renderEjercicioCard($item, $id_cronograma) {
?>
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 relative group hover:border-purple-300 transition">
        <div class="flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="inline-block text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-white text-gray-600 border border-gray-200 mb-1">
                    <?php echo htmlspecialchars($item['ejercicio_tipo']); ?>
                </span>
                <h4 class="font-semibold text-gray-800 text-sm leading-snug">
                    <?php echo htmlspecialchars($item['ejercicio_nombre']); ?>
                </h4>
            </div>
            <form method="POST" action="<?php echo base_url('maestro/cronogramas/ejercicio/remove'); ?>" onsubmit="return confirm('¿Quitar este ejercicio de la sesión?')" class="opacity-80 group-hover:opacity-100">
                <input type="hidden" name="id_cronograma" value="<?php echo $id_cronograma; ?>">
                <input type="hidden" name="id_clase_ejercicio" value="<?php echo $item['id_clase_ejercicio']; ?>">
                <button type="submit" class="text-gray-400 hover:text-red-600 transition p-1" title="Quitar">
                    <span class="material-icons-outlined text-base">close</span>
                </button>
            </form>
        </div>

        <?php if (!empty($item['series_o_tiempo'])): ?>
            <div class="mt-2 flex items-center gap-1 text-xs text-purple-700 font-medium">
                <span class="material-icons-outlined text-xs">timer</span>
                <span><?php echo htmlspecialchars($item['series_o_tiempo']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($item['observaciones_especificas'])): ?>
            <p class="mt-1.5 text-xs text-gray-500 italic bg-white p-2 rounded border border-gray-100">
                "<?php echo htmlspecialchars($item['observaciones_especificas']); ?>"
            </p>
        <?php endif; ?>

        <?php if (!empty($item['ejercicio_explicacion'])): ?>
            <details class="mt-2 text-xs text-gray-500">
                <summary class="cursor-pointer text-gray-400 hover:text-gray-600 font-medium select-none">Ver técnica de biblioteca</summary>
                <div class="p-2 mt-1 bg-white rounded border border-gray-100 text-gray-600">
                    <?php echo nl2br(htmlspecialchars($item['ejercicio_explicacion'])); ?>
                </div>
            </details>
        <?php endif; ?>
    </div>
<?php
}
?>

<!-- Modal Agregar Ejercicio a Fase -->
<div id="modal-agregar-ejercicio" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="material-icons-outlined text-purple-600">fitness_center</span>
                Agregar Ejercicio a <span id="modal-fase-titulo" class="capitalize"></span>
            </h3>
            <button onclick="closeModal('modal-agregar-ejercicio')" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/cronogramas/ejercicio/add'); ?>" class="p-6 space-y-4">
            <input type="hidden" name="id_cronograma" value="<?php echo $cronograma['id_cronograma']; ?>">
            <input type="hidden" name="fase" id="input-fase" value="inicial">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar de Biblioteca *</label>
                <select name="id_ejercicio" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione un ejercicio...</option>
                    <?php 
                    // Agrupar biblioteca por tipo
                    $agrupados = [];
                    foreach ($biblioteca as $b) {
                        $agrupados[$b['tipo']][] = $b;
                    }
                    foreach ($agrupados as $tipoCat => $itemsCat): ?>
                        <optgroup label="<?php echo htmlspecialchars($tipoCat); ?>">
                            <?php foreach ($itemsCat as $b): ?>
                                <option value="<?php echo $b['id_ejercicio']; ?>">
                                    <?php echo htmlspecialchars($b['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-400 mt-1">¿No encuentras el ejercicio? Créalo en la <a href="<?php echo base_url('maestro/ejercicios'); ?>" target="_blank" class="text-purple-600 underline">Biblioteca</a>.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Series / Tiempo / Repeticiones</label>
                <input type="text" name="series_o_tiempo" placeholder="Ej: 3 series de 15 reps, o 4 minutos continuos"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones Específicas para esta Clase</label>
                <textarea name="observaciones_especificas" rows="2" placeholder="Ej: Énfasis en la elevación de rodilla, trabajo en parejas..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-agregar-ejercicio')" class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                    Asignar a la Fase
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function abrirModalAgregar(fase) {
    document.getElementById('input-fase').value = fase;
    const titulos = {
        'inicial': 'Fase Inicial (Calentamiento)',
        'central': 'Fase Central (Carga Técnica/Combate)',
        'final': 'Fase Final (Vuelta a la calma)'
    };
    document.getElementById('modal-fase-titulo').innerText = titulos[fase] || fase;
    openModal('modal-agregar-ejercicio');
}
</script>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

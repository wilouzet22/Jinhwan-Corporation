<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Navegación y Encabezado -->
        <div>
            <a href="<?php echo base_url('maestro/cronogramas'); ?>" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 hover:text-purple-700 transition mb-3">
                <span class="material-icons-outlined text-sm">arrow_back</span>
                Volver a Cronogramas
            </a>
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm transition-colors">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                                <?php echo htmlspecialchars($cronograma['grupo_nombre'] ?? 'Grupo'); ?>
                            </span>
                            <?php if (!empty($cronograma['grupo_sede'])): ?>
                                <span class="text-xs text-slate-400 dark:text-slate-500">• <?php echo htmlspecialchars($cronograma['grupo_sede']); ?></span>
                            <?php endif; ?>
                            <span class="text-xs text-slate-400 dark:text-slate-500">• Instructor: <?php echo htmlspecialchars($cronograma['maestro_nombre'] ?? 'N/A'); ?></span>
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
                        <h1 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                            Sesión del <?php echo $fechaTextoCompleto; ?>
                        </h1>
                    </div>
                </div>

                <?php if (!empty($cronograma['objetivo']) || !empty($cronograma['observaciones'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-sm">
                        <?php if (!empty($cronograma['objetivo'])): ?>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Objetivo Principal:</span>
                                <p class="text-slate-800 dark:text-slate-200 font-medium"><?php echo nl2br(htmlspecialchars($cronograma['objetivo'])); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($cronograma['observaciones'])): ?>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-1">Observaciones:</span>
                                <p class="text-slate-700 dark:text-slate-300"><?php echo nl2br(htmlspecialchars($cronograma['observaciones'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fases de la Sesión de Entrenamiento -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- FASE INICIAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden transition-colors">
                <div class="p-4 border-b border-amber-200/40 dark:border-amber-900/30 bg-amber-500/10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="p-1.5 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 rounded-xl material-icons-outlined text-base">wb_sunny</span>
                        <div>
                            <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Inicial</h2>
                            <span class="text-[11px] text-amber-600 dark:text-amber-400">Calentamiento y movilidad</span>
                        </div>
                    </div>
                    <button onclick="abrirModalAgregar('inicial')" class="p-1 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950/50 rounded-lg transition" title="Agregar ejercicio">
                        <span class="material-icons-outlined text-xl">add_circle</span>
                    </button>
                </div>
                
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['inicial'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['inicial'] as $item): ?>
                            <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="p-3 border-t border-slate-100 dark:border-slate-800">
                    <button onclick="abrirModalAgregar('inicial')" class="w-full py-2 border border-dashed border-amber-300 dark:border-amber-800/60 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1 transition">
                        <span class="material-icons-outlined text-sm">add</span>
                        Agregar Ejercicio
                    </button>
                </div>
            </div>

            <!-- FASE CENTRAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden transition-colors">
                <div class="p-4 border-b border-purple-200/40 dark:border-purple-900/30 bg-purple-500/10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="p-1.5 bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 rounded-xl material-icons-outlined text-base">sports_martial_arts</span>
                        <div>
                            <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Central</h2>
                            <span class="text-[11px] text-purple-600 dark:text-purple-400">Técnica, combate y carga</span>
                        </div>
                    </div>
                    <button onclick="abrirModalAgregar('central')" class="p-1 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-950/50 rounded-lg transition" title="Agregar ejercicio">
                        <span class="material-icons-outlined text-xl">add_circle</span>
                    </button>
                </div>
                
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['central'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['central'] as $item): ?>
                            <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="p-3 border-t border-slate-100 dark:border-slate-800">
                    <button onclick="abrirModalAgregar('central')" class="w-full py-2 border border-dashed border-purple-300 dark:border-purple-800/60 text-purple-700 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/30 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1 transition">
                        <span class="material-icons-outlined text-sm">add</span>
                        Agregar Ejercicio
                    </button>
                </div>
            </div>

            <!-- FASE FINAL -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden transition-colors">
                <div class="p-4 border-b border-emerald-200/40 dark:border-emerald-900/30 bg-emerald-500/10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="p-1.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 rounded-xl material-icons-outlined text-base">self_improvement</span>
                        <div>
                            <h2 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wide">Fase Final</h2>
                            <span class="text-[11px] text-emerald-600 dark:text-emerald-400">Vuelta a la calma y estiramiento</span>
                        </div>
                    </div>
                    <button onclick="abrirModalAgregar('final')" class="p-1 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-950/50 rounded-lg transition" title="Agregar ejercicio">
                        <span class="material-icons-outlined text-xl">add_circle</span>
                    </button>
                </div>
                
                <div class="p-4 flex-1 space-y-3">
                    <?php if (empty($fases['final'])): ?>
                        <div class="text-center py-10 text-slate-400 dark:text-slate-600 text-xs">
                            <span class="material-icons-outlined text-3xl block mb-1 opacity-40">fitness_center</span>
                            Sin ejercicios asignados
                        </div>
                    <?php else: ?>
                        <?php foreach ($fases['final'] as $item): ?>
                            <?php renderEjercicioCard($item, $cronograma['id_cronograma']); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="p-3 border-t border-slate-100 dark:border-slate-800">
                    <button onclick="abrirModalAgregar('final')" class="w-full py-2 border border-dashed border-emerald-300 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1 transition">
                        <span class="material-icons-outlined text-sm">add</span>
                        Agregar Ejercicio
                    </button>
                </div>
            </div>

        </div>

    </div>
</main>

<?php
function renderEjercicioCard($item, $id_cronograma) {
?>
    <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl p-3 relative group hover:border-purple-400 dark:hover:border-purple-600 transition">
        <div class="flex items-start justify-between gap-2">
            <div class="flex-1">
                <span class="inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-lg bg-white dark:bg-slate-900 text-purple-700 dark:text-purple-300 border border-slate-200 dark:border-slate-800 mb-1">
                    <?php echo htmlspecialchars($item['ejercicio_tipo']); ?>
                </span>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">
                    <?php echo htmlspecialchars($item['ejercicio_nombre']); ?>
                </h4>
            </div>
            <form method="POST" action="<?php echo base_url('maestro/cronogramas/ejercicio/remove'); ?>" onsubmit="return confirm('¿Quitar este ejercicio de la sesión?')" class="opacity-80 group-hover:opacity-100">
                <input type="hidden" name="id_cronograma" value="<?php echo $id_cronograma; ?>">
                <input type="hidden" name="id_clase_ejercicio" value="<?php echo $item['id_clase_ejercicio']; ?>">
                <button type="submit" class="text-slate-400 hover:text-red-500 transition p-1" title="Quitar">
                    <span class="material-icons-outlined text-base">close</span>
                </button>
            </form>
        </div>

        <?php if (!empty($item['series_o_tiempo'])): ?>
            <div class="mt-2 flex items-center gap-1 text-xs text-purple-600 dark:text-purple-400 font-semibold">
                <span class="material-icons-outlined text-xs">timer</span>
                <span><?php echo htmlspecialchars($item['series_o_tiempo']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($item['observaciones_especificas'])): ?>
            <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 italic bg-white dark:bg-slate-900 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800">
                "<?php echo htmlspecialchars($item['observaciones_especificas']); ?>"
            </p>
        <?php endif; ?>

        <?php if (!empty($item['ejercicio_explicacion'])): ?>
            <details class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                <summary class="cursor-pointer text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-semibold select-none">Ver técnica de biblioteca</summary>
                <div class="p-2.5 mt-1 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300">
                    <?php echo nl2br(htmlspecialchars($item['ejercicio_explicacion'])); ?>
                </div>
            </details>
        <?php endif; ?>
    </div>
<?php
}
?>

<!-- Modal Agregar Ejercicio a Fase -->
<div id="modal-agregar-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-purple-600 dark:text-purple-400">fitness_center</span>
                Agregar Ejercicio a <span id="modal-fase-titulo" class="capitalize"></span>
            </h3>
            <button onclick="closeModal('modal-agregar-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/cronogramas/ejercicio/add'); ?>" class="p-6 space-y-4">
            <input type="hidden" name="id_cronograma" value="<?php echo $cronograma['id_cronograma']; ?>">
            <input type="hidden" name="fase" id="input-fase" value="inicial">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Seleccionar de Biblioteca *</label>
                <select name="id_ejercicio" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione un ejercicio...</option>
                    <?php 
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
                <p class="text-xs text-slate-400 mt-1">¿No encuentras el ejercicio? Créalo en la <a href="<?php echo base_url('maestro/ejercicios'); ?>" target="_blank" class="text-purple-500 underline">Biblioteca</a>.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Series / Tiempo / Repeticiones</label>
                <input type="text" name="series_o_tiempo" placeholder="Ej: 3 series de 15 reps, o 4 minutos continuos"
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Observaciones Específicas para esta Clase</label>
                <textarea name="observaciones_especificas" rows="2" placeholder="Ej: Énfasis en la elevación de rodilla, trabajo en parejas..."
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-agregar-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
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

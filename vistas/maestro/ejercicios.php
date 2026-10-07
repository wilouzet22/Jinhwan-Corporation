<?php require_once __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative transition-colors duration-300">
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">
                    Biblioteca de Ejercicios
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Catálogo central compartido de ejercicios clasificados para sesiones de entrenamiento.
                </p>
            </div>
            <div>
                <button onclick="openModal('modal-nuevo-ejercicio')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                    <span class="material-icons-outlined text-base">add</span>
                    <span>Nuevo Ejercicio</span>
                </button>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3 transition-colors">
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-icons-outlined text-sm">search</span>
                </div>
                <input type="text" id="filter-search" placeholder="Buscar por nombre o explicación..." 
                       class="block w-full pl-9 pr-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">Capacidad / Tipo:</span>
                <select id="filter-tipo" class="text-xs rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors w-full md:w-56">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Tabla de Ejercicios -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse" id="tabla-ejercicios">
                    <thead class="text-[11px] uppercase bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 sticky top-0 z-10 transition-colors">
                        <tr>
                            <th scope="col" class="px-4 py-3">Tipo / Capacidad</th>
                            <th scope="col" class="px-4 py-3">Nombre</th>
                            <th scope="col" class="px-4 py-3">Explicación / Metodología</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors text-slate-700 dark:text-slate-300">
                        <?php if (empty($ejercicios)): ?>
                            <tr id="row-empty">
                                <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    <span class="material-icons-outlined text-4xl block mb-2 opacity-50">fitness_center</span>
                                    No hay ejercicios registrados aún en la biblioteca.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            if (!function_exists('getBadgeTipoEstilo')) {
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
                                    return 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/40';
                                }
                            }
                            ?>
                            <?php foreach ($ejercicios as $ej): 
                                $badgeClase = getBadgeTipoEstilo($ej['tipo']);
                            ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-all item-ejercicio" 
                                    data-tipo="<?php echo htmlspecialchars($ej['tipo']); ?>"
                                    data-text="<?php echo htmlspecialchars(strtolower($ej['nombre'] . ' ' . $ej['tipo'] . ' ' . $ej['explicacion'])); ?>">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 rounded-xl text-xs font-semibold border <?php echo $badgeClase; ?>">
                                            <?php echo htmlspecialchars($ej['tipo']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                        <button type="button" 
                                                onclick='verEjercicio(<?php echo json_encode($ej, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' 
                                                class="text-left font-semibold text-slate-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-1.5 group cursor-pointer"
                                                title="Ver detalle del ejercicio">
                                            <span><?php echo htmlspecialchars($ej['nombre']); ?></span>
                                            <span class="material-icons-outlined text-xs text-slate-400 group-hover:text-purple-500 opacity-0 group-hover:opacity-100 transition-all">open_in_new</span>
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-400 max-w-md">
                                        <div class="line-clamp-2"><?php echo nl2br(htmlspecialchars($ej['explicacion'] ?? '')); ?></div>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- Botón Ver Detalle -->
                                            <button type="button" onclick='verEjercicio(<?php echo json_encode($ej, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' 
                                                    class="p-1.5 text-purple-600 dark:text-purple-400 hover:text-purple-700 hover:bg-purple-50 dark:hover:bg-purple-950/40 rounded-xl transition cursor-pointer" 
                                                    title="Ver detalle">
                                                <span class="material-icons-outlined text-lg">visibility</span>
                                            </button>
                                            <!-- Botón Editar -->
                                            <button type="button" onclick='editarEjercicio(<?php echo json_encode($ej, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)' 
                                                    class="p-1.5 text-blue-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition cursor-pointer" 
                                                    title="Editar">
                                                <span class="material-icons-outlined text-lg">edit</span>
                                            </button>
                                            <!-- Botón Eliminar -->
                                            <form method="POST" action="<?php echo base_url('maestro/ejercicios/delete'); ?>" onsubmit="return confirm('¿Seguro de eliminar este ejercicio? Se desvinculará de las clases.')" class="inline">
                                                <input type="hidden" name="id_ejercicio" value="<?php echo $ej['id_ejercicio']; ?>">
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition cursor-pointer" title="Eliminar">
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

<!-- Modal Visualizar Ejercicio (Detalle Completo) -->
<div id="modal-ver-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg my-auto max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-800 transition-colors animate-in fade-in zoom-in-95 duration-150">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <span class="material-icons-outlined text-xl">fitness_center</span>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base leading-tight">
                        Ficha del Ejercicio
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        Información técnica y metodología de ejecución
                    </p>
                </div>
            </div>
            <button onclick="closeModal('modal-ver-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <span class="material-icons-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5 overflow-y-auto flex-1">
            <!-- Título y Categoría -->
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span id="ver-tipo-badge" class="px-2.5 py-1 rounded-xl text-xs font-semibold border"></span>
                    <span id="ver-fase-badge" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700"></span>
                </div>
                <h2 id="ver-nombre" class="text-2xl font-bold font-display text-slate-900 dark:text-white leading-tight"></h2>
            </div>

            <!-- Explicación Técnica -->
            <div class="space-y-2">
                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    <span class="material-icons-outlined text-sm text-purple-600 dark:text-purple-400">menu_book</span>
                    <span>Explicación / Metodología de Ejecución</span>
                </div>
                <div id="ver-explicacion" 
                     class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950/70 border border-slate-200/80 dark:border-slate-800/80 text-sm leading-relaxed text-slate-700 dark:text-slate-200 whitespace-pre-line min-h-[100px] select-text">
                </div>
            </div>

            <!-- Nota pedagógica / Tips para el Maestro -->
            <div class="p-3.5 rounded-xl bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-800/40 flex items-start gap-3 text-xs text-amber-800 dark:text-amber-300/90">
                <span class="material-icons-outlined text-amber-500 text-base shrink-0 mt-0.5">lightbulb</span>
                <p class="leading-relaxed">
                    <strong>Recomendación para clase:</strong> Supervisa siempre la postura, la respiración y los rangos articulares seguros. Adapta la cadencia y repeticiones según el nivel técnico del grupo.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-2 shrink-0 bg-slate-50/50 dark:bg-slate-950/30">
            <button type="button" onclick="closeModal('modal-ver-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                Cerrar
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="editarDesdeVer()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    <span class="material-icons-outlined text-sm">edit</span>
                    <span>Editar Ejercicio</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Ejercicio -->
<div id="modal-nuevo-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md my-auto max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-purple-600 dark:text-purple-400">fitness_center</span>
                Nuevo Ejercicio
            </h3>
            <button onclick="closeModal('modal-nuevo-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/create'); ?>" class="p-6 space-y-4 overflow-y-auto flex-1">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tipo / Capacidad *</label>
                <select name="tipo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <option value="">Seleccione un tipo...</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" required placeholder="Ej. Sentadillas con salto, Bandal Chagui..." 
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Explicación / Metodología</label>
                <textarea name="explicacion" rows="4" placeholder="Detalla la ejecución técnica y precauciones..."
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-nuevo-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    Guardar Ejercicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Ejercicio -->
<div id="modal-editar-ejercicio" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-xs overflow-y-auto p-4 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md my-auto max-h-[90vh] flex flex-col border border-slate-200 dark:border-slate-800 transition-colors">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-base">
                <span class="material-icons-outlined text-blue-500">edit</span>
                Editar Ejercicio
            </h3>
            <button onclick="closeModal('modal-editar-ejercicio')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo base_url('maestro/ejercicios/update'); ?>" class="p-6 space-y-4 overflow-y-auto flex-1">
            <input type="hidden" name="id_ejercicio" id="edit-id">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tipo / Capacidad *</label>
                <select name="tipo" id="edit-tipo" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>"><?php echo htmlspecialchars($t); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Nombre del Ejercicio *</label>
                <input type="text" name="nombre" id="edit-nombre" required 
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Explicación / Metodología</label>
                <textarea name="explicacion" id="edit-explicacion" rows="4"
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                <button type="button" onclick="closeModal('modal-editar-ejercicio')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let ejercicioActual = null;

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function verEjercicio(ej) {
    ejercicioActual = ej;
    document.getElementById('ver-nombre').textContent = ej.nombre || 'Sin nombre';
    
    // Categoría badge
    const badge = document.getElementById('ver-tipo-badge');
    badge.textContent = ej.tipo || 'Sin clasificar';
    badge.className = 'px-2.5 py-1 rounded-xl text-xs font-semibold border ' + obtenerEstiloBadge(ej.tipo);
    
    // Sugerencia de fase
    const faseBadge = document.getElementById('ver-fase-badge');
    const faseInfo = sugerirFase(ej.tipo);
    faseBadge.innerHTML = `<span class="material-icons-outlined text-xs">${faseInfo.icono}</span> Sugerido: ${faseInfo.nombre}`;
    
    // Explicación
    const expl = document.getElementById('ver-explicacion');
    if (ej.explicacion && ej.explicacion.trim() !== '') {
        expl.textContent = ej.explicacion;
    } else {
        expl.innerHTML = '<span class="italic text-slate-400 dark:text-slate-500">Sin explicación o metodología detallada registrada aún. Haz clic en "Editar" para añadir una descripción técnica.</span>';
    }
    
    openModal('modal-ver-ejercicio');
}

function sugerirFase(tipo) {
    const t = (tipo || '').toLowerCase();
    if (t.includes('flexibilidad')) return { nombre: 'Fase Final / Vuelta a la Calma', icono: 'self_improvement' };
    if (t.includes('coordinac') || t.includes('aerobica')) return { nombre: 'Fase Inicial / Calentamiento', icono: 'play_arrow' };
    if (t.includes('pliometr') || t.includes('fuerza') || t.includes('combate') || t.includes('velocidad') || t.includes('anaerobica')) {
        return { nombre: 'Fase Central / Principal', icono: 'bolt' };
    }
    return { nombre: 'Fase Central / General', icono: 'fitness_center' };
}

function obtenerEstiloBadge(tipo) {
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
    return 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/40';
}

function editarDesdeVer() {
    if (ejercicioActual) {
        closeModal('modal-ver-ejercicio');
        editarEjercicio(ejercicioActual);
    }
}

function editarEjercicio(ej) {
    document.getElementById('edit-id').value = ej.id_ejercicio;
    document.getElementById('edit-tipo').value = ej.tipo;
    document.getElementById('edit-nombre').value = ej.nombre;
    document.getElementById('edit-explicacion').value = ej.explicacion || '';
    openModal('modal-editar-ejercicio');
}

const filterSearch = document.getElementById('filter-search');
const filterTipo = document.getElementById('filter-tipo');
const items = document.querySelectorAll('.item-ejercicio');

function filtrar() {
    const q = filterSearch.value.toLowerCase().trim();
    const tipo = filterTipo.value;

    items.forEach(el => {
        const text = el.getAttribute('data-text');
        const t = el.getAttribute('data-tipo');

        const matchSearch = !q || text.includes(q);
        const matchTipo = !tipo || t === tipo;

        el.style.display = (matchSearch && matchTipo) ? '' : 'none';
    });
}

if (filterSearch) filterSearch.addEventListener('input', filtrar);
if (filterTipo) filterTipo.addEventListener('change', filtrar);
</script>

<?php 
$contextoIA = 'ejercicios';
require_once __DIR__ . '/../layout/ia_asistente_widget.php'; 
?>

<?php require_once __DIR__ . '/../layout/maestro_pie.php'; ?>

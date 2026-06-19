<?php include __DIR__ . '/../layout/maestro_cabecera.php'; ?>

<main class="flex-grow container mx-auto p-6 lg:p-8 relative overflow-hidden transition-colors duration-300">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 relative z-10">
        <div>
            <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-tight transition-colors">Mis Alumnos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Listado completo de deportistas de la academia. Puedes proponer ascensos de grado.</p>
        </div>
    </div>

    <!-- Alumnos Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm relative z-10 transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nombre</th>
                        <th scope="col" class="px-6 py-4">Documento</th>
                        <th scope="col" class="px-6 py-4">Cinturón Actual</th>
                        <th scope="col" class="px-6 py-4">Sede</th>
                        <th scope="col" class="px-6 py-4">Contacto</th>
                        <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all">
                            <td class="px-6 py-4 font-semibold whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-tkd-purple flex items-center justify-center mr-3 font-bold transition-colors shrink-0">
                                        <?= strtoupper(substr($alumno['nombre'], 0, 1)) ?>
                                    </div>
                                    <span class="text-slate-800 dark:text-white transition-colors"><?= htmlspecialchars($alumno['nombre']) . ' ' . htmlspecialchars($alumno['apellido']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <span class="font-mono text-[10px] bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-2 py-1 rounded text-slate-500 dark:text-slate-400 mr-1 transition-colors">CC</span>
                                <?= htmlspecialchars($alumno['numero_documento']) ?>
                            </td>
                            <?php
                                $nivel = strtolower($alumno['nombre_nivel'] ?? '');
                                $beltClass = 'bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-900/60 dark:text-slate-400 dark:border-slate-800/80'; // Default
                                
                                if (str_contains($nivel, 'blanco')) {
                                    $beltClass = 'bg-white text-slate-900 border border-slate-300 dark:border-slate-200 shadow-sm';
                                } elseif (str_contains($nivel, 'amarillo')) {
                                    $beltClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-950/60 dark:text-yellow-400 dark:border-yellow-800/30';
                                } elseif (str_contains($nivel, 'verde')) {
                                    $beltClass = 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/60 dark:text-green-400 dark:border-green-800/30';
                                } elseif (str_contains($nivel, 'azul')) {
                                    $beltClass = 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-800/30';
                                } elseif (str_contains($nivel, 'rojo')) {
                                    $beltClass = 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/60 dark:text-red-400 dark:border-red-800/30';
                                } elseif (str_contains($nivel, 'negro') || str_contains($nivel, 'dan')) {
                                    $beltClass = 'bg-slate-900 text-white border border-slate-900 dark:bg-slate-950 dark:border-slate-800 shadow-md';
                                }
                            ?>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors <?= $beltClass ?>">
                                    <?= htmlspecialchars($alumno['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 transition-colors">
                                <div class="flex items-center gap-1 text-sm font-medium">
                                    <span class="material-icons-outlined text-sm text-tkd-purple">place</span>
                                    <?= htmlspecialchars($alumno['nombre_sede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">
                                <div class="text-slate-700 dark:text-slate-300"><?= htmlspecialchars($alumno['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]"><?= htmlspecialchars($alumno['correo'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openPromotionModal(<?= json_encode($alumno) ?>)' class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase tracking-wider py-2 px-3 rounded-lg shadow-sm transition-all focus:outline-none" title="Proponer Ascenso">
                                    <span class="material-icons-outlined text-sm">arrow_upward</span>
                                    Proponer Ascenso
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Promotion Proposal Modal -->
<div id="promoModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closePromoModal()"></div>
        
        <!-- Modal placement hack -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
            <form action="<?= base_url('/maestro/solicitudes-ascenso/create') ?>" method="POST">
                <input type="hidden" name="id_miembro" id="promo_id_miembro">
                <input type="hidden" name="id_grado_actual" id="promo_id_grado_actual">

                <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-850">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-icons-outlined text-tkd-purple">arrow_upward</span>
                        Proponer Ascenso de Grado
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Alumno Info -->
                    <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-100 dark:border-slate-800/80">
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Deportista</p>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 mt-0.5" id="promo_alumno_nombre"></h4>
                        <p class="text-xs text-slate-500 mt-1">Cinturón actual: <span class="font-bold text-purple-600 dark:text-purple-400" id="promo_cinturon_actual_nombre"></span></p>
                    </div>

                    <!-- Grado Solicitado -->
                    <div>
                        <label for="id_grado_solicitado" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Grado/Cinturón Propuesto</label>
                        <select id="id_grado_solicitado" name="id_grado_solicitado" required class="w-full rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:border-tkd-purple focus:ring-tkd-purple text-sm p-3">
                            <option value="">Seleccione grado...</option>
                            <?php foreach ($grados_list as $grado): ?>
                                <option value="<?= $grado['id'] ?>"><?= htmlspecialchars($grado['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label for="observaciones" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Observaciones / Justificación</label>
                        <textarea id="observaciones" name="observaciones" rows="4" class="w-full rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:border-tkd-purple focus:ring-tkd-purple text-sm p-3" placeholder="Indica los motivos del ascenso, comportamiento, asistencia o desempeño en entrenamientos..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-850">
                    <button type="button" onclick="closePromoModal()" class="text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 font-bold text-xs uppercase tracking-wider px-4 py-3 rounded-xl transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-1.5">
                        <span class="material-icons-outlined text-sm">send</span>
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPromotionModal(alumno) {
        document.getElementById('promo_id_miembro').value = alumno.id;
        document.getElementById('promo_id_grado_actual').value = alumno.nivel_id;
        document.getElementById('promo_alumno_nombre').innerText = alumno.nombre + ' ' + alumno.apellido;
        document.getElementById('promo_cinturon_actual_nombre').innerText = alumno.nombre_nivel || 'Sin Asignar';
        
        // Hide option representing the current grade
        const select = document.getElementById('id_grado_solicitado');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value == alumno.nivel_id) {
                select.options[i].disabled = true;
            } else {
                select.options[i].disabled = false;
            }
        }
        
        document.getElementById('promoModal').classList.remove('hidden');
    }

    function closePromoModal() {
        document.getElementById('promoModal').classList.add('hidden');
    }
</script>

<?php include __DIR__ . '/../layout/maestro_pie.php'; ?>

<?php include __DIR__ . '/../layout/administracion_cabecera.php'; ?>

<main class="flex-1 p-4 md:p-8 overflow-y-auto transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 transition-colors duration-300 shadow-sm">
            <div>
                <h2 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-2 transition-colors">Perfiles Públicos</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm transition-colors">Gestiona la información pública de los miembros y decide quién aparece en la web.</p>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-300 shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800 transition-colors">
                            <th class="p-4 font-semibold w-12 text-center">ID</th>
                            <th class="p-4 font-semibold">Miembro</th>
                            <th class="p-4 font-semibold text-center">Rol en Web</th>
                            <th class="p-4 font-semibold text-center">Visible</th>
                            <th class="p-4 font-semibold text-center">Multimedia</th>
                            <th class="p-4 font-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 dark:divide-slate-800/50 transition-colors">
                        <?php if(!empty($miembros)): ?>
                            <?php foreach($miembros as $m): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="p-4 text-center text-slate-500 font-mono text-xs"><?= $m['id'] ?></td>
                                    <td class="p-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 transition-colors"><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></div>
                                        <?php if(!empty($m['descripcion_perfil'])): ?>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-xs transition-colors" title="<?= htmlspecialchars($m['descripcion_perfil']) ?>">
                                                <?= htmlspecialchars($m['descripcion_perfil']) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-xs text-slate-400 dark:text-slate-500 italic transition-colors">Sin descripción</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if($m['rol_id']): ?>
                                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded text-xs transition-colors"><?= htmlspecialchars($m['rol_id']) ?></span>
                                        <?php else: ?>
                                            <span class="text-slate-400 dark:text-slate-500 text-xs italic transition-colors">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if($m['mostrar_en_web']): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Visible
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span> Oculto
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if(!empty($m['instagram_url'])): ?>
                                            <a href="<?= htmlspecialchars($m['instagram_url']) ?>" target="_blank" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 transition-colors" title="Ver Video">
                                                <span class="material-icons-outlined text-lg">play_circle</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 dark:text-slate-500 transition-colors">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button onclick='openEditModal(<?= json_encode($m) ?>)' class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10 transition-colors focus:outline-none" title="Editar Perfil Web">
                                            <span class="material-icons-outlined text-lg">edit</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400 transition-colors">No hay miembros registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Modal Editar Perfil Público -->
<div id="modalEditarPerfil" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-slate-200 dark:border-slate-800 duration-300">
            <form action="<?= base_url('/admin/perfiles-publicos/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950/50 transition-colors">
                    <h3 class="text-xl font-display font-bold text-slate-900 dark:text-white transition-colors">Editar Perfil Público</h3>
                    <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <!-- Toggle Mostrar en Web -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-200 transition-colors">Mostrar en Web</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Hace que el miembro sea visible en la página pública.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="mostrar_en_web" id="edit_mostrar" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Rol en Web -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 transition-colors">Categoría / Rol en Web</label>
                        <select name="rol" id="edit_rol" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2.5 text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:border-tkd-blue transition-colors focus:outline-none">
                            <option value="">Seleccionar...</option>
                            <option value="Administracion">Administración</option>
                            <option value="Maestros">Maestros</option>
                            <option value="Profesores">Profesores</option>
                            <option value="Monitores">Monitores</option>
                            <option value="Deportistas">Deportistas</option>
                        </select>
                    </div>

                    <!-- URL YouTube / Vimeo -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 transition-colors">Enlace de YouTube o Vimeo</label>
                        <input type="url" name="url_instagram" id="edit_instagram" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2.5 text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:border-tkd-blue transition-colors focus:outline-none" placeholder="https://www.youtube.com/shorts/...">
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 transition-colors">Pega el enlace de un YouTube Short, YouTube Video o un video de Vimeo para mostrarlo sin distracciones.</p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 transition-colors">Descripción del Perfil</label>
                        <textarea name="descripcion_perfil" id="edit_descripcion" rows="4" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2.5 text-slate-800 dark:text-slate-300 focus:ring-2 focus:ring-tkd-blue focus:border-tkd-blue transition-colors resize-none focus:outline-none" placeholder="Breve biografía, logros, experiencia..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 transition-colors">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-white transition-colors focus:outline-none">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-tkd-blue hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(miembro) {
        document.getElementById('edit_id').value = miembro.id;
        document.getElementById('edit_mostrar').checked = miembro.mostrar_en_web == 1;
        document.getElementById('edit_rol').value = miembro.rol_id || '';
        document.getElementById('edit_instagram').value = miembro.instagram_url || '';
        document.getElementById('edit_descripcion').value = miembro.descripcion_perfil || '';
        
        document.getElementById('modalEditarPerfil').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalEditarPerfil').classList.add('hidden');
    }
</script>

<?php include __DIR__ . '/../layout/administracion_pie.php'; ?>

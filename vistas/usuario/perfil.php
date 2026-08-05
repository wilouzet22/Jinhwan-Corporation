<?php 
$current_page = 'perfil';

$rol = $_SESSION['rol_id'] ?? '';
$cabecera = __DIR__ . '/../layout/estudiante_cabecera.php';
$pie = __DIR__ . '/../layout/estudiante_pie.php';

if ($rol === 'Administracion') {
    $cabecera = __DIR__ . '/../layout/administracion_cabecera.php';
    $pie = __DIR__ . '/../layout/administracion_pie.php';
} elseif (in_array($rol, ['Maestros', 'Profesores', 'Monitores'])) {
    $cabecera = __DIR__ . '/../layout/maestro_cabecera.php';
    $pie = __DIR__ . '/../layout/maestro_pie.php';
}

include $cabecera; 
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Mi Perfil</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Actualiza tus datos de contacto, foto de perfil y contraseña</p>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-icons-outlined">check_circle</span>
            <p class="text-sm font-medium">Perfil actualizado correctamente.</p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 flex flex-col items-center text-center transition-colors duration-300">
                <div class="relative group mb-4">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?= base_url('/public/uploads/perfiles/' . $usuario['foto_perfil']) ?>" alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-2 border-tkd-blue dark:border-tkd-blue/55 shadow-lg">
                    <?php else: ?>
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-4xl font-bold text-tkd-blue border border-slate-200 dark:border-slate-700 transition-colors">
                            <?= strtoupper(substr($usuario['nombre'] ?? 'A', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-1 transition-colors"><?= htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '')) ?></h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 transition-colors">Doc: <?= htmlspecialchars($usuario['numero_documento'] ?? '') ?></p>
                
                <div class="w-full space-y-2 mt-2">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Rol</span>
                        <span class="text-tkd-blue font-bold text-sm"><?= htmlspecialchars($usuario['rol_id'] ?? 'Deportista') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Grado</span>
                        <span class="text-tkd-blue font-bold text-sm"><?= htmlspecialchars($usuario['nombre_nivel'] ?? 'Ninguno') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Sede</span>
                        <span class="text-slate-900 dark:text-slate-100 font-semibold text-sm"><?= htmlspecialchars($usuario['nombre_sede'] ?? 'Sin Asignar') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Correo</span>
                        <span class="text-slate-900 dark:text-slate-100 font-semibold text-xs"><?= htmlspecialchars($usuario['correo'] ?? 'Sin correo') ?></span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">
                <div class="flex items-start gap-3 text-tkd-blue dark:text-blue-400">
                    <span class="material-icons-outlined mt-0.5">info</span>
                    <p class="text-sm">Algunos datos institucionales como tu **Rol**, **Sede** y **Cinturón (Nivel)** solo pueden ser modificados por un administrador del sistema.</p>
                </div>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors duration-300">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Información</h2>
                </div>
                
                <form action="<?= base_url('/usuario/perfil/update') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    
                    <!-- Foto de perfil upload -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Foto de Perfil</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <input type="file" name="foto_perfil" accept="image/png, image/jpeg, image/jpg, image/webp" class="block w-full text-sm text-slate-500 dark:text-slate-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-tkd-blue
                                dark:file:bg-slate-800 dark:file:text-blue-400
                                hover:file:bg-blue-100 dark:hover:file:bg-slate-700
                                transition-colors">
                            
                            <?php if (!empty($usuario['foto_perfil'])): ?>
                                <label class="inline-flex items-center text-xs font-semibold text-red-600 dark:text-red-400 cursor-pointer select-none">
                                    <input type="checkbox" name="eliminar_foto" value="1" class="rounded border-slate-300 dark:border-slate-700 text-red-600 focus:ring-red-500 mr-2">
                                    Eliminar foto actual
                                </label>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Formatos admitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB.</p>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <!-- Información Personal -->
                    <div>
                        <h3 class="text-sm font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-4">Información Personal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nombre(s) *</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Apellido(s) *</label>
                                <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tipo de Documento *</label>
                                <select name="tipo_documento" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                                    <option value="TI" <?= ($usuario['tipo_documento'] ?? '') === 'TI' ? 'selected' : '' ?>>T.I.</option>
                                    <option value="CC" <?= ($usuario['tipo_documento'] ?? '') === 'CC' ? 'selected' : '' ?>>C.C.</option>
                                    <option value="CE" <?= ($usuario['tipo_documento'] ?? '') === 'CE' ? 'selected' : '' ?>>C.E.</option>
                                    <option value="PAS" <?= ($usuario['tipo_documento'] ?? '') === 'PAS' ? 'selected' : '' ?>>Pasaporte</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Número de Documento *</label>
                                <input type="text" name="numero_documento" value="<?= htmlspecialchars($usuario['numero_documento'] ?? '') ?>" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <!-- Información de Contacto -->
                    <div>
                        <h3 class="text-sm font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-4">Contacto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Teléfono de Contacto</label>
                                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: 3001234567">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Correo Electrónico *</label>
                                <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="ejemplo@correo.com">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <!-- Información Deportiva y de Salud -->
                    <div>
                        <h3 class="text-sm font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-4">Salud y Deporte</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">EPS</label>
                                <input type="text" name="eps" value="<?= htmlspecialchars($usuario['eps'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: Sura, Sanitas">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Grupo Sanguíneo (RH)</label>
                                <select name="rh" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors">
                                    <option value="">Seleccionar RH</option>
                                    <?php 
                                    $rh_options = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
                                    foreach ($rh_options as $option):
                                    ?>
                                        <option value="<?= $option ?>" <?= ($usuario['rh'] ?? '') === $option ? 'selected' : '' ?>><?= $option ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if (($usuario['rol_id'] ?? '') === 'Deportistas'): ?>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Peso (kg)</label>
                                    <input type="number" step="0.01" name="peso" value="<?= htmlspecialchars($usuario['peso'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: 65.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">División de Peso</label>
                                    <input type="text" name="division" value="<?= htmlspecialchars($usuario['division'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: Minimosca -54kg">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Código CTGC</label>
                                    <input type="text" name="ctgc" value="<?= htmlspecialchars($usuario['ctgc'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: CTGC-12345">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">

                    <!-- Perfil Público Web -->
                    <div>
                        <h3 class="text-sm font-bold text-tkd-blue dark:text-blue-400 uppercase tracking-wider mb-4">Perfil Público Web</h3>
                        <div class="space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="mostrar_en_web" value="1" <?= ($usuario['mostrar_en_web'] ?? 0) == 1 ? 'checked' : '' ?> class="form-checkbox h-5 w-5 text-tkd-blue rounded border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-850 focus:ring-tkd-blue">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Mostrar mi perfil en la página web pública del club</span>
                            </label>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Descripción Corta / Biografía</label>
                                <textarea name="descripcion_perfil" rows="3" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Cuéntanos un poco sobre ti..."><?= htmlspecialchars($usuario['descripcion_perfil'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Logros y Reconocimientos</label>
                                <textarea name="logros" rows="3" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Medallas, títulos, participaciones destacadas..."><?= htmlspecialchars($usuario['logros'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800">
                    
                    <div>
                        <h3 class="text-md font-bold text-slate-900 dark:text-slate-100 mb-4">Cambiar Contraseña</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Deja estos campos en blanco si no deseas cambiar tu contraseña actual.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nueva Contraseña</label>
                                <input type="password" name="clave_nueva" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirmar Contraseña</label>
                                <input type="password" name="clave_confirmar" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-tkd-blue hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors flex items-center gap-2">
                            <span class="material-icons-outlined text-sm">save</span>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

</main>

<?php 
$pie_incl = __DIR__ . '/../layout/estudiante_pie.php';
if ($rol === 'Administracion') {
    $pie_incl = __DIR__ . '/../layout/administracion_pie.php';
} elseif (in_array($rol, ['Maestros', 'Profesores', 'Monitores'])) {
    $pie_incl = __DIR__ . '/../layout/maestro_pie.php';
}
include $pie_incl; 
?>

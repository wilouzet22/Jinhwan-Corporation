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

// Obtener el tema marcial oficial (Dorado para admin, Negro para maestro, Grado exacto para estudiante)
$tema = function_exists('obtener_tema_marcial') 
    ? obtener_tema_marcial($usuario['rol_id'] ?? $rol, $usuario['nombre_nivel'] ?? null) 
    : [
        'nombre_visual' => 'Taekwondo Jinhwan',
        'hangul'        => '태권도',
        'filosofia'     => 'Disciplina, honor y superación deportiva',
        'color_hex'     => '#2563EB',
        'color_accent'  => '#DC2626',
        'belt_gradient' => 'linear-gradient(135deg, #2563EB, #1D4ED8)',
        'badge_classes' => 'bg-blue-500/10 text-blue-600 border border-blue-500/30'
    ];
?>

<main class="flex-grow p-4 sm:p-6 lg:p-8 space-y-6 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">

    <!-- Banner Superior Marcial con Ideograma Coreano y Filosofía del Grado -->
    <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all"
         style="background: <?= $tema['banner_gradient'] ?? 'linear-gradient(135deg, rgba(37,99,235,0.08), transparent)' ?>; border-left: 6px solid <?= $tema['color_hex'] ?>;">
        
        <!-- Marca de agua caligráfica oriental 태권도 -->
        <div class="absolute right-3 -bottom-8 sm:-bottom-12 text-7xl sm:text-9xl font-serif font-black select-none pointer-events-none opacity-5 dark:opacity-10 text-slate-900 dark:text-white tracking-tighter">
            <?= $tema['hangul'] ?? '태권도' ?>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider mb-2.5 <?= $tema['badge_classes'] ?>">
                    <span class="w-2 h-2 rounded-full animate-ping" style="background-color: <?= $tema['color_hex'] ?>;"></span>
                    <?= htmlspecialchars($tema['nombre_visual']) ?>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight font-display flex items-center gap-3">
                    Expediente Marcial & Perfil
                </h1>
                <p class="text-slate-600 dark:text-slate-300 text-sm mt-1 max-w-2xl italic font-serif">
                    "<?= htmlspecialchars($tema['filosofia']) ?>"
                </p>
            </div>

            <!-- Sello Tradicional de Dojang (Cera Oriental) -->
            <div class="hidden sm:flex items-center gap-3 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm px-4 py-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 shrink-0 shadow-sm">
                <div class="tkd-seal-stamp">
                    <span>진환</span>
                    <small>DOJANG</small>
                </div>
                <div class="text-left text-xs leading-tight">
                    <span class="font-extrabold text-slate-900 dark:text-white block uppercase tracking-wider">Jinhwan Club</span>
                    <span class="text-slate-500 dark:text-slate-400 text-[10px]">Registro Oficial WT</span>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
            <span class="material-icons-outlined text-emerald-500">verified</span>
            <p class="text-sm font-semibold">Tus datos marciales y de usuario han sido actualizados con éxito en el sistema.</p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        <!-- ====================================================================
             COLUMNA IZQUIERDA: CREDENCIAL MARCIAL OFICIAL (LICENCIA DE COMBATE)
             ==================================================================== -->
        <div class="lg:col-span-4 space-y-6">

            <div class="tkd-martial-card border" style="--tkd-border: <?= $tema['color_hex'] ?>; --tkd-gradient: <?= $tema['belt_gradient'] ?>;">
                
                <!-- Encabezado de la Credencial: Cinturón y Escudo -->
                <div class="p-5 pb-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="<?= asset('img/visual/logo.svg') ?>" alt="Logo" class="w-8 h-8 object-contain">
                        <div>
                            <span class="text-xs font-black tracking-widest text-slate-900 dark:text-white uppercase font-display block leading-none">JINHWAN TKD</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Licencia Digital</span>
                        </div>
                    </div>
                    <span class="text-xs font-black font-mono px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                        <?= htmlspecialchars($usuario['tipo_documento'] ?? 'TI') ?>-<?= htmlspecialchars($usuario['numero_documento'] ?? '000000') ?>
                    </span>
                </div>

                <!-- Cuerpo de la Credencial -->
                <div class="p-4 flex flex-col items-center text-center">
                    
                    <!-- Foto de Perfil con Halo del Rango -->
                    <div class="relative group mb-2">
                        <div class="w-24 h-24 rounded-full p-1 shadow-lg transition-transform duration-300 group-hover:scale-105"
                             style="background: <?= $tema['belt_gradient'] ?>; box-shadow: 0 0 20px <?= $tema['glow_rgba'] ?>;">
                            <?php if (!empty($usuario['foto_perfil'])): ?>
                                <img src="<?= base_url('/public/uploads/perfiles/' . $usuario['foto_perfil']) ?>" 
                                     alt="Foto" 
                                     class="w-full h-full rounded-full object-cover bg-white dark:bg-slate-900">
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-3xl font-black text-slate-800 dark:text-white">
                                    <?= strtoupper(substr($usuario['nombre'] ?? 'J', 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Insignia flotante con ideograma coreano del grado -->
                        <span class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold text-xs flex items-center justify-center border-2 border-white dark:border-slate-900 shadow-md"
                              title="Ideograma de Grado">
                            <?= $tema['hangul'] ?? '武' ?>
                        </span>
                    </div>

                    <!-- Nombre y Apellidos -->
                    <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight uppercase font-display">
                        <?= htmlspecialchars(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '')) ?>
                    </h2>
                    
                    <!-- Rango / Grado con Cinta Representativa -->
                    <div class="mt-2 w-full py-1.5 px-3 rounded-lg text-xs font-black tracking-wider uppercase flex items-center justify-center gap-2 shadow-sm text-white"
                         style="background: <?= $tema['belt_gradient'] ?>;">
                        <span class="material-icons-outlined text-sm">sports_martial_arts</span>
                        <span><?= htmlspecialchars($usuario['nombre_nivel'] ?? $tema['jerarquia']) ?></span>
                    </div>

                    <!-- Ficha Marcial Compacta -->
                    <div class="w-full space-y-2 mt-3 text-left text-xs">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/80">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Rol</span>
                            <span class="font-extrabold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['rol_id'] ?? 'Deportista') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/80">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Sede Dojang</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['nombre_sede'] ?? 'Sin Asignar') ?></span>
                        </div>
                        <?php if (!empty($usuario['nombre_grupo'])): ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/80">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Grupo</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['nombre_grupo']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($usuario['nombre_categoria']) && $usuario['nombre_categoria'] !== 'Ninguno'): ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800/80">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Categoría</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200"><?= htmlspecialchars($usuario['nombre_categoria']) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Estado</span>
                            <span class="inline-flex items-center gap-1 font-bold <?= ($usuario['activo'] ?? 1) ? 'text-emerald-500' : 'text-red-500' ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?= ($usuario['activo'] ?? 1) ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                                <?= ($usuario['activo'] ?? 1) ? 'Activo en Dojang' : 'Inactivo' ?>
                            </span>
                        </div>
                    </div>


                </div>

                <!-- Los 5 Principios del Taekwondo grabado al pie -->
                <div class="bg-slate-50 dark:bg-slate-950 p-3.5 border-t border-slate-100 dark:border-slate-800/80 text-center">
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Principios del Taekwondo</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium leading-tight">
                        Cortesía • Integridad • Perseverancia • Autocontrol • Espíritu Indomable
                    </p>
                </div>
            </div>

            <!-- Aviso Institucional de Jerarquía -->
            <div class="bg-slate-100/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-start gap-3 text-slate-600 dark:text-slate-400 text-xs">
                <span class="material-icons-outlined text-base mt-0.5" style="color: <?= $tema['color_hex'] ?>;">verified_user</span>
                <p>
                    Tu <strong class="text-slate-800 dark:text-slate-200">Grado</strong>, <strong class="text-slate-800 dark:text-slate-200">Rol</strong> y <strong class="text-slate-800 dark:text-slate-200">Sede</strong> forman parte de tu registro federado oficial y solo pueden ser modificados por un directivo o maestro acreditado.
                </p>
            </div>

        </div>

        <!-- ====================================================================
             COLUMNA DERECHA: FORMULARIO DE GESTIÓN Y EXPEDIENTE TÉCNICO
             ==================================================================== -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-300">
                
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-wider font-display">
                            Actualizar Datos del Expediente
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mantén al día tu información personal, deportiva y de seguridad</p>
                    </div>
                    <span class="material-icons-outlined text-2xl text-slate-400">badge</span>
                </div>
                
                <form action="<?= base_url('/usuario/perfil/update') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">

                    <!-- SECCIÓN 1: FOTOGRAFÍA DE PERFIL -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Fotografía de Carnet Marcial
                        </label>
                        <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800">
                            <input type="file" name="foto_perfil" accept="image/png, image/jpeg, image/jpg, image/webp" 
                                   class="block w-full text-xs text-slate-500 dark:text-slate-400
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-xl file:border-0
                                          file:text-xs file:font-bold
                                          file:bg-slate-900 file:text-white
                                          dark:file:bg-white dark:file:text-slate-900
                                          hover:file:opacity-90 cursor-pointer
                                          transition-all">
                            
                            <?php if (!empty($usuario['foto_perfil'])): ?>
                                <label class="inline-flex items-center text-xs font-semibold text-rose-600 dark:text-rose-400 cursor-pointer select-none shrink-0 hover:underline">
                                    <input type="checkbox" name="eliminar_foto" value="1" class="rounded border-slate-300 dark:border-slate-700 text-rose-600 focus:ring-rose-500 mr-2">
                                    Remover foto actual
                                </label>
                            <?php endif; ?>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">Recomendado: Fondo neutro, con Dobok reglamentario. Formatos: JPG, PNG, WEBP (Máx. 2MB).</p>
                    </div>

                    <!-- SECCIÓN 2: INFORMACIÓN PERSONAL -->
                    <div class="pt-2">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="material-icons-outlined text-sm" style="color: <?= $tema['color_hex'] ?>;">person</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                                Información de Identidad
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Nombre(s) *</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Apellido(s) *</label>
                                <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido'] ?? '') ?>" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Tipo de Documento *</label>
                                <select name="tipo_documento" required 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                        style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                                    <option value="TI" <?= ($usuario['tipo_documento'] ?? '') === 'TI' ? 'selected' : '' ?>>T.I. - Tarjeta de Identidad</option>
                                    <option value="CC" <?= ($usuario['tipo_documento'] ?? '') === 'CC' ? 'selected' : '' ?>>C.C. - Cédula de Ciudadanía</option>
                                    <option value="CE" <?= ($usuario['tipo_documento'] ?? '') === 'CE' ? 'selected' : '' ?>>C.E. - Cédula de Extranjería</option>
                                    <option value="PAS" <?= ($usuario['tipo_documento'] ?? '') === 'PAS' ? 'selected' : '' ?>>PAS - Pasaporte</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Número de Documento *</label>
                                <input type="text" name="numero_documento" value="<?= htmlspecialchars($usuario['numero_documento'] ?? '') ?>" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?>" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 3: CONTACTO -->
                    <div class="pt-2">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="material-icons-outlined text-sm" style="color: <?= $tema['color_hex'] ?>;">contact_phone</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                                Canales de Contacto
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Teléfono Celular / WhatsApp</label>
                                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                       placeholder="Ej: 3001234567"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Correo Electrónico *</label>
                                <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                       placeholder="alumno@jinhwan.com"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4: SALUD Y CONDICIÓN DEPORTIVA -->
                    <div class="pt-2">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="material-icons-outlined text-sm" style="color: <?= $tema['color_hex'] ?>;">fitness_center</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                                Ficha Médica y Rendimiento Deportivo
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Entidad Promotora de Salud (EPS)</label>
                                <input type="text" name="eps" value="<?= htmlspecialchars($usuario['eps'] ?? '') ?>" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                       placeholder="Ej: Sura, Sanitas, Nueva EPS"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Grupo Sanguíneo (RH)</label>
                                <select name="rh" 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors"
                                        style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                                    <option value="">Seleccionar RH</option>
                                    <?php 
                                    $rh_options = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
                                    foreach ($rh_options as $option):
                                    ?>
                                        <option value="<?= $option ?>" <?= ($usuario['rh'] ?? '') === $option ? 'selected' : '' ?>><?= $option ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php if (($usuario['rol_id'] ?? '') === 'Deportistas' || $rol === 'Deportistas'): ?>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Peso Corporal de Combate (kg)</label>
                                    <input type="number" step="0.01" name="peso" value="<?= htmlspecialchars($usuario['peso'] ?? '') ?>" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                           placeholder="Ej: 64.5"
                                           style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">División Oficial de Peso</label>
                                    <input type="text" name="division" value="<?= htmlspecialchars($usuario['division'] ?? '') ?>" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                           placeholder="Ej: Feather (-68kg), Fin (-54kg)"
                                           style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- SECCIÓN 5: PERFIL PÚBLICO WEB Y LOGROS -->
                    <div class="pt-2">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="material-icons-outlined text-sm" style="color: <?= $tema['color_hex'] ?>;">military_tech</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                                Perfil Público y Palmarés Marcial
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer p-3 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                                <input type="checkbox" name="mostrar_en_web" value="1" <?= ($usuario['mostrar_en_web'] ?? 0) == 1 ? 'checked' : '' ?> 
                                       class="form-checkbox h-5 w-5 rounded border-slate-300 dark:border-slate-700 focus:ring-2"
                                       style="color: <?= $tema['color_hex'] ?>;">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                    Hacer visible mi ficha marcial en el portal web público de Jinhwan Corporation
                                </span>
                            </label>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Biografía Marcial / Presentación</label>
                                <textarea name="descripcion_perfil" rows="3" 
                                          class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                          placeholder="Años en el club, filosofía personal, metas deportivas..."
                                          style="--tw-ring-color: <?= $tema['color_hex'] ?>;"><?= htmlspecialchars($usuario['descripcion_perfil'] ?? '') ?></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Logros, Medallas y Reconocimientos</label>
                                <textarea name="logros" rows="3" 
                                          class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                          placeholder="Oro en Departamental 2024, Subcampeón Nacional Poomsae..."
                                          style="--tw-ring-color: <?= $tema['color_hex'] ?>;"><?= htmlspecialchars($usuario['logros'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 6: SEGURIDAD Y ACCESO -->
                    <div class="pt-2">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="material-icons-outlined text-sm" style="color: <?= $tema['color_hex'] ?>;">lock</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                                Clave de Acceso al Software
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Deja estos campos en blanco si no deseas modificar tu clave actual.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Nueva Contraseña</label>
                                <input type="password" name="clave_nueva" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                       placeholder="••••••••"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Confirmar Nueva Contraseña</label>
                                <input type="password" name="clave_confirmar" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:outline-none dark:text-white text-sm transition-colors" 
                                       placeholder="••••••••"
                                       style="--tw-ring-color: <?= $tema['color_hex'] ?>;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- BOTÓN DE GUARDADO MARCIAL -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-slate-400 flex items-center gap-1.5">
                            <span class="material-icons-outlined text-sm">security</span>
                            Transmisión encriptada y datos protegidos
                        </span>

                        <button type="submit" 
                                class="w-full sm:w-auto text-white font-extrabold text-sm py-3 px-8 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98]"
                                style="background: <?= $tema['belt_gradient'] ?>; box-shadow: 0 4px 18px <?= $tema['glow_rgba'] ?>;">
                            <span class="material-icons-outlined text-base">save</span>
                            Guardar Expediente
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

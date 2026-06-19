<?php 
$current_page = 'perfil';
include __DIR__ . '/../layout/estudiante_cabecera.php'; 
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Mi Perfil</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Actualiza tus datos de contacto e información personal</p>
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
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-4xl font-bold text-tkd-blue mb-4 border border-slate-200 dark:border-slate-700 transition-colors">
                    <?= substr($estudiante['nombre'] ?? 'A', 0, 1) ?>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-1 transition-colors"><?= htmlspecialchars(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')) ?></h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 transition-colors">Doc: <?= htmlspecialchars($estudiante['numero_documento'] ?? '') ?></p>
                
                <div class="w-full space-y-2 mt-2">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Nivel</span>
                        <span class="text-tkd-blue font-bold text-sm"><?= htmlspecialchars($estudiante['nombre_nivel'] ?? 'Blanco') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Sede</span>
                        <span class="text-slate-900 dark:text-slate-100 font-semibold text-sm"><?= htmlspecialchars($estudiante['nombre_sede'] ?? 'Sin Asignar') ?></span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700 flex justify-between items-center transition-colors">
                        <span class="text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">Correo</span>
                        <span class="text-slate-900 dark:text-slate-100 font-semibold text-xs"><?= htmlspecialchars($estudiante['correo'] ?? 'Sin correo') ?></span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">
                <div class="flex items-start gap-3 text-tkd-blue dark:text-blue-400">
                    <span class="material-icons-outlined mt-0.5">info</span>
                    <p class="text-sm">Algunos datos como tu nombre, documento, correo o sede solo pueden ser modificados por tu instructor o el administrador del sistema.</p>
                </div>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors duration-300">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Editar Información</h2>
                </div>
                
                <form action="<?= base_url('/estudiante/perfil/update') ?>" method="POST" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Teléfono de Contacto</label>
                            <input type="text" name="telefono" value="<?= htmlspecialchars($estudiante['telefono'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: 3001234567">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Peso (kg)</label>
                            <input type="number" step="0.1" name="peso" value="<?= htmlspecialchars($estudiante['peso'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-tkd-blue dark:text-white transition-colors" placeholder="Ej: 65.5">
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

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>

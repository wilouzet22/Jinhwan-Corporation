<?php
$error = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'db_error') {
        $error = "Hubo un problema al procesar tu registro. Por favor, inténtalo de nuevo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro de Usuario - Jinhwan Corporation</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="<?= asset('styles/output.css') ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="bg-tkd-black min-h-screen font-body flex items-center justify-center relative py-12 px-4 overflow-x-hidden">
    
    <!-- Background Image with Blur -->
    <div class="fixed inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-sm scale-110 opacity-40" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>
    </div>

    <!-- Register Container -->
    <div class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 animate-fade-in-up relative z-10 overflow-hidden">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-tkd-red via-tkd-black to-tkd-blue"></div>

        <div class="p-8 md:p-12">
            <div class="text-center mb-10">
                <div class="inline-block p-4 rounded-3xl bg-slate-50 dark:bg-slate-800 mb-6 shadow-inner">
                    <img src="<?= asset('img/visual/logo.png') ?>" alt="Jinhwan" class="h-20 w-auto object-contain">
                </div>
                <h2 class="text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider">Solicitud de Registro</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 text-lg">Únete a nuestra organización</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-8 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-center animate-shake">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">report_problem</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-6" method="POST" action="<?= base_url('/registro/process') ?>">
                <!-- Personal Info Section -->
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 pb-2">Datos Personales</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nombre(s)</label>
                        <input name="nombre" type="text" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Apellido(s)</label>
                        <input name="apellido" type="text" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Documento de Identidad</label>
                        <input name="num_doc" type="text" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all" placeholder="CC / TI / Pasaporte">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fecha de Nacimiento</label>
                        <input name="fecha_n" type="date" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                    </div>
                </div>

                <!-- Account Info Section -->
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 pb-2">Datos de Cuenta</h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Correo Electrónico</label>
                        <input name="email" type="email" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Teléfono de Contacto</label>
                        <input name="telefono" type="tel" required class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contraseña</label>
                        <input name="password" type="password" required minlength="6" class="block w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all">
                        <p class="text-[10px] text-slate-500 mt-1">Mínimo 6 caracteres</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-xl text-base font-bold text-white bg-tkd-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-1 uppercase tracking-widest font-display">
                            Enviar Solicitud
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    ¿Ya tienes una cuenta aprobada? 
                    <a href="<?= base_url('/login') ?>" class="text-tkd-blue hover:text-blue-700 font-bold decoration-2 hover:underline">Inicia sesión</a>
                </p>
            </div>
        </div>

        <div class="bg-slate-50 dark:bg-slate-800/50 py-4 text-center text-[10px] text-slate-400 uppercase tracking-widest">
            &copy; 2025 Jinhwan Corporation - Sistema de Gestión de Membresías
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }
    </style>
</body>
</html>

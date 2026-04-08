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
<body class="bg-tkd-black h-screen font-body flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-sm scale-110 opacity-50" alt="Background">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Register Container (Compacto 2 columnas) -->
    <div class="w-full max-w-2xl bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 animate-fade-in-up relative z-10 overflow-hidden text-white">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-black to-tkd-blue"></div>

        <div class="p-4 md:p-6">
            <div class="text-center mb-4">
                <div class="inline-block mb-2">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-16 w-auto object-contain">
                </div>
                <h2 class="text-xl font-display font-bold text-white uppercase tracking-wider">Solicitud de Registro</h2>
            </div>

            <?php if ($error): ?>
                <div class="mb-8 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-center animate-shake">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">report_problem</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="grid grid-cols-2 gap-x-4 gap-y-3" method="POST" action="<?= base_url('/registro/process') ?>">
                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Nombre(s)</label>
                    <input name="nombre" type="text" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Apellido(s)</label>
                    <input name="apellido" type="text" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Documento Identidad</label>
                    <input name="num_doc" type="text" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Fecha Nacimiento</label>
                    <input name="fecha_n" type="date" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Correo Electrónico</label>
                    <input name="email" type="email" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Teléfono</label>
                    <input name="telefono" type="tel" required class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div class="col-span-2">
                    <label class="block text-[11px] font-medium text-slate-300 mb-0.5">Contraseña (Mín. 6 carc.)</label>
                    <input name="password" type="password" required minlength="6" class="block w-full py-1.5 px-3 rounded-lg border-slate-700 bg-slate-800 text-white text-sm focus:ring-tkd-blue transition-all">
                </div>

                <div class="col-span-2 pt-1">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg shadow-xl text-sm font-bold text-white bg-tkd-blue hover:bg-blue-700 transition-all uppercase tracking-widest font-display">
                        Enviar Solicitud
                    </button>
                </div>
            </form>

            <div class="mt-4 pt-4 border-t border-slate-800 text-center">
                <p class="text-xs text-slate-400">
                    ¿Ya tienes cuenta? <a href="<?= base_url('/login') ?>" class="text-tkd-blue hover:text-blue-700 font-bold">Inicia sesión</a>
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

<?php
$error = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'db_error') {
        $error = "Hubo un problema al procesar tu registro. Por favor, inténtalo de nuevo.";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro de Usuario - Jinhwan Corporation</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="<?= asset('styles/output.css') ?>" rel="stylesheet">
    
    <!-- Tailwind CSS & Configs -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="<?= asset('js/styles/main-config.js') ?>"></script>
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-tkd-black min-h-screen font-body flex items-center justify-center relative overflow-hidden selection:bg-tkd-red selection:text-white">
    
    <!-- Background Image with Blur & Premium Glows -->
    <div class="absolute inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-[6px] scale-105 opacity-20" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-[#0b0f19] via-[#0b0f19]/90 to-[#111827]/80"></div>
        <!-- Ambient Light Gradients -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-tkd-red/10 blur-[120px] pointer-events-none animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-tkd-blue/10 blur-[120px] pointer-events-none animate-pulse-slow" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Register Container -->
    <div class="w-full max-w-2xl m-4 glass-panel rounded-3xl shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-slate-800/80 animate-fade-in-up relative z-10 overflow-hidden text-white">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>

        <div class="p-6 md:p-8">
            <div class="text-center mb-6">
                <div class="inline-block mb-3 transition-transform duration-500 hover:scale-105 animate-float">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-16 w-auto object-contain drop-shadow-[0_0_15px_rgba(220,38,38,0.2)]">
                </div>
                <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wider">Solicitud de Registro</h2>
                <p class="text-slate-400 mt-1 text-xs italic">Cree su cuenta para unirse a nosotros</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-950/30 border border-red-500/40 text-center animate-shake">
                    <p class="text-sm text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined text-red-400">report_problem</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4" method="POST" action="<?= base_url('/registro/process') ?>">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nombre(s)</label>
                    <input name="nombre" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Apellido(s)</label>
                    <input name="apellido" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Documento Identidad</label>
                    <input name="num_doc" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Fecha Nacimiento</label>
                    <input name="fecha_n" type="date" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300 select-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Correo Electrónico</label>
                    <input name="email" type="email" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Teléfono</label>
                    <input name="telefono" type="tel" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Contraseña (Mín. 6 carc.)</label>
                    <input name="password" type="password" required minlength="6" 
                        class="block w-full py-3 px-4 rounded-xl border-slate-700/80 bg-slate-900/50 text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300">
                </div>

                <div class="md:col-span-2 pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 rounded-xl shadow-xl text-sm font-bold text-white bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-0.5 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] active:translate-y-0 uppercase tracking-widest font-display">
                        Enviar Solicitud
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-800/80 text-center">
                <p class="text-xs text-slate-400">
                    ¿Ya tienes cuenta? <a href="<?= base_url('/login') ?>" class="text-tkd-blue hover:text-blue-400 font-bold hover:underline transition-colors ml-1">Inicia sesión</a>
                </p>
            </div>
        </div>

        <div class="bg-slate-950/40 py-4 text-center text-[10px] text-slate-500 uppercase tracking-widest border-t border-slate-800/50">
            &copy; 2025 Jinhwan Corporation - Sistema de Gestión de Membresías
        </div>
    </div>

    <style>
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

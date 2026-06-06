<?php
$error = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1) {
        $error = "Contraseña incorrecta. Inténtalo de nuevo.";
    } elseif ($_GET['error'] == 2) {
        $error = "El correo electrónico no está registrado.";
    } elseif ($_GET['error'] == 'no_session') {
        $error = "Debes iniciar sesión para acceder.";
    } elseif ($_GET['error'] == 'timeout') {
         $error = "Sesión expirada por inactividad.";
    } elseif ($_GET['error'] == 'pending') {
        $error = "Tu solicitud está en revisión. El administrador te avisará cuando puedas entrar.";
    }
}
$mensaje = null;
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sent') {
        $mensaje = "¡Solicitud enviada! Tu registro ha sido enviado al administrador para su aprobación.";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Acceso al Sistema - Jinhwan Corporation</title>
    
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

    <!-- Login Container -->
    <div class="w-full max-w-md m-4 glass-panel rounded-3xl shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-slate-800/80 animate-fade-in-up relative z-10 overflow-hidden text-white">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>

        <div class="p-6 md:p-8">
            <div class="text-center mb-8">
                <div class="inline-block mb-4 transition-transform duration-500 hover:scale-105 animate-float">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-20 w-auto object-contain drop-shadow-[0_0_15px_rgba(220,38,38,0.2)]">
                </div>
                <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wider">Acceso al Sistema</h2>
                <p class="text-slate-400 mt-1 text-xs italic">Ingrese sus credenciales</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-950/30 border border-red-500/40 text-center animate-shake">
                    <p class="text-sm text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined text-red-400">error_outline</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($mensaje): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/30 border border-emerald-500/40 text-center animate-fade-in">
                    <p class="text-sm text-emerald-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined text-emerald-400">check_circle</span>
                         <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="space-y-5" method="POST" action="<?= base_url('/login/process') ?>">
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Correo Electrónico</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">alternate_email</span>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="pl-12 block w-full py-3 rounded-xl border-slate-700/80 bg-slate-900/50 text-white placeholder-slate-500 focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300"
                            placeholder="ejemplo@jinhwa.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Contraseña</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">lock_open</span>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="pl-12 block w-full py-3 rounded-xl border-slate-700/80 bg-slate-900/50 text-white placeholder-slate-500 focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 focus:bg-slate-900 transition-all duration-300"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-xl text-sm font-bold text-white bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-0.5 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] active:translate-y-0 uppercase tracking-widest font-display">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-400">
                        ¿No tienes cuenta todavía? 
                        <a href="<?= base_url('/registro') ?>" class="text-tkd-blue hover:text-blue-400 font-bold hover:underline transition-colors ml-1">Solicita tu registro aquí</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="bg-slate-950/40 py-4 text-center text-[10px] text-slate-500 uppercase tracking-widest border-t border-slate-800/50">
            &copy; 2025 Jinhwan Corporation - Acceso Reservado
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

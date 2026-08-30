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
<html lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Acceso al Sistema - Jinhwan Corporation</title>
    
    <link rel="icon" type="image/x-icon" href="<?= asset('img/visual/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- React + TypeScript Bundle -->
    <link rel="stylesheet" href="<?= asset('dist/assets/main.css') ?>">
    <script type="module" src="<?= asset('dist/assets/main.js') ?>"></script>
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-slate-100 dark:bg-[#080c16] min-h-screen font-body flex flex-col items-center justify-center relative overflow-x-hidden overflow-y-auto selection:bg-rose-600 selection:text-white transition-colors duration-300 py-12">

    <div class="fixed inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-[4px] scale-105 opacity-50 dark:opacity-30" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-white/60 dark:from-[#0b0f19]/90 via-white/40 dark:via-[#0b0f19]/80 to-blue-100/50 dark:to-[#111827]/80"></div>
    </div>

    <a href="<?= base_url('/') ?>" class="absolute top-6 left-6 z-20 flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white dark:hover:bg-slate-800/80 transition-all backdrop-blur-sm group shadow-sm">
        <span class="material-icons-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
        <span class="text-xs font-semibold uppercase tracking-wider">Volver</span>
    </a>

    <div class="w-full max-w-md m-4 bg-white dark:bg-slate-900/80 backdrop-blur-md rounded-3xl shadow-xl dark:shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-slate-200 dark:border-slate-800/80 animate-fade-in-up relative z-10 overflow-hidden transition-colors duration-300">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>

        <div class="p-6 md:p-8">
            <div class="text-center mb-8">
                <div class="inline-block mb-4 transition-transform duration-500 hover:scale-105">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-20 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider transition-colors">Acceso al Sistema</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs italic transition-colors">Ingrese sus credenciales</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-300 dark:border-red-500/40 text-center animate-shake transition-colors">
                    <p class="text-sm text-red-700 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">error_outline</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($mensaje): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-300 dark:border-emerald-500/40 text-center animate-fade-in transition-colors">
                    <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">check_circle</span>
                         <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="space-y-5" method="POST" action="<?= base_url('/login/process') ?>">
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Correo Electrónico</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">alternate_email</span>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="pl-12 block w-full py-3 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300"
                            placeholder="ejemplo@jinhwa.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Contraseña</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">lock_open</span>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="pl-12 pr-12 block w-full py-3 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', 'toggleIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-tkd-blue transition-colors focus:outline-none">
                            <span id="toggleIcon" class="material-icons-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 uppercase tracking-widest font-display">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">
                        ¿No tienes cuenta todavía? 
                        <a href="<?= base_url('/registro') ?>" class="text-tkd-blue hover:text-blue-500 font-bold hover:underline transition-colors ml-1">Solicita tu registro aquí</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="bg-slate-50 dark:bg-slate-950/40 py-4 text-center text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest border-t border-slate-200 dark:border-slate-800/50 transition-colors">
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

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>

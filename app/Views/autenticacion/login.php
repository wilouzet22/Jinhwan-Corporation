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

    <!-- Login Container (Compacto para fit) -->
    <div class="w-full max-w-md p-6 m-2 bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 animate-fade-in-up relative z-10 overflow-hidden text-white">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-black to-tkd-blue"></div>

        <div class="p-4 md:p-6">
            <div class="text-center mb-6">
                <div class="inline-block mb-3">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-24 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wider">Acceso al Sistema</h2>
                <p class="text-slate-400 mt-1 text-xs italic">Ingrese sus credenciales</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-center animate-shake">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">error_outline</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($mensaje): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-center animate-fade-in">
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">check_circle</span>
                         <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="space-y-4" method="POST" action="<?= base_url('/login/process') ?>">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Correo Electrónico</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">alternate_email</span>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="pl-12 block w-full rounded-xl border-gray-300 dark:border-slate-700 bg-slate-800 text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all"
                            placeholder="ejemplo@jinhwa.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Contraseña</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-1 py-1 flex items-center pointer-events-none text-slate-400 group-focus-within:text-tkd-blue transition-colors">
                            <span class="material-icons-outlined ml-3">lock_open</span>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="pl-12 block w-full rounded-xl border-gray-300 dark:border-slate-700 bg-slate-800 text-white focus:ring-tkd-blue focus:border-tkd-blue transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-xl text-base font-bold text-white bg-tkd-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-1 uppercase tracking-widest font-display">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        ¿No tienes cuenta todavía? 
                        <a href="<?= base_url('/registro') ?>" class="text-tkd-blue hover:text-blue-700 font-bold decoration-2 hover:underline">Solicita tu registro aquí</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="bg-slate-50 dark:bg-slate-800/50 py-4 text-center text-[10px] text-slate-400 uppercase tracking-widest">
            &copy; 2025 Jinhwan Corporation - Acceso Reservado
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
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
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

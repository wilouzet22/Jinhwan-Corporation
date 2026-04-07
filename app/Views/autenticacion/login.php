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
    <title>Admin Login - Jinnwhan Organization</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="<?= asset('styles/output.css') ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="<?= asset('js/styles/administracion-config.js') ?>"></script>
</head>
<body class="bg-tkd-black min-h-screen font-body flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-sm scale-105 opacity-60" alt="Background">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Login Container -->
    <div class="w-full max-w-lg p-10 m-4 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 animate-fade-in-up relative z-10 overflow-hidden">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-black to-tkd-blue"></div>

        <div class="text-center mb-8">
            <div class="inline-block p-4 rounded-full bg-slate-50 dark:bg-slate-800 mb-4 shadow-sm">
                <img src="<?= asset('img/visual/logo.png') ?>" alt="Jinhwan" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wide">Admin Access</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm">Ingrese sus credenciales para continuar</p>
        </div>

        <form class="space-y-6" method="POST" action="/jinwha/login/process">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-lg">email</span>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required class="pl-10 block w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-colors">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-icons-outlined text-lg">lock</span>
                    </span>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="pl-10 block w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-tkd-blue focus:border-tkd-blue transition-colors">
                </div>
            </div>
            <?php if ($error): ?>
                <div class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-center animate-shake">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined text-sm">error</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($mensaje): ?>
                <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-center">
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined text-sm">check_circle</span>
                         <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>
            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-tkd-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-0.5 uppercase tracking-wider font-display">
                    Iniciar Sesión
                </button>
            </div>
            <div class="text-center mt-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    ¿No tienes cuenta? 
                    <a href="<?= base_url('/registro') ?>" class="text-tkd-blue hover:text-blue-700 font-bold decoration-2 hover:underline">Regístrate aquí</a>
                </p>
            </div>
        </form>
        
        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; 2025 Jinnwhan Corporation
        </div>
    </div>
</body>
</html>

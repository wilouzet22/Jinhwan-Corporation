<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_usuario = $_SESSION['nombre'] ?? 'Estudiante';
$foto_perfil = null;

if (isset($_SESSION['id'])) {
    $user_model = new Usuario();
    $logged_user = $user_model->getById($_SESSION['id']);
    if ($logged_user) {
        $nombre_usuario = $logged_user['nombre'] . ' ' . $logged_user['apellido'];
        $foto_perfil = $logged_user['foto_perfil'];
    }
}
$tema_marcial = function_exists('obtener_tema_marcial') ? obtener_tema_marcial('Deportistas', $logged_user['nombre_nivel'] ?? null) : [];
?>
<!DOCTYPE html>
<html lang="es" class="notranslate" translate="no">
<head>
<meta charset="utf-8"/>
<meta name="google" content="notranslate">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= $page_title ?? 'Portal del Alumno' ?></title>
    
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

    <!-- React + TypeScript Bundle CSS only -->
    <link rel="stylesheet" href="<?= asset('dist/assets/main.css') ?>">
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
    <?= function_exists('renderizar_variables_tema_marcial') ? renderizar_variables_tema_marcial($tema_marcial) : '' ?>
</head>
<body class="bg-slate-50/85 dark:bg-slate-950/80 text-slate-800 dark:text-slate-200 font-body antialiased selection:bg-rose-600 selection:text-white transition-colors duration-300 tkd-dobok-texture">
<div class="flex min-h-screen md:h-screen md:overflow-hidden">
    
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 dark:bg-black/55 z-40 hidden md:hidden transition-opacity duration-300 backdrop-blur-sm"></div>

    <aside id="estudiante-sidebar" class="fixed inset-y-0 left-0 z-50 w-60 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-xl md:shadow-none md:static">
        <div class="h-1.5 w-full shadow-sm" style="background: var(--tkd-gradient, linear-gradient(135deg, #2563EB, #06b6d4, #10b981));"></div>
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center transition-colors">
            <a href="<?= base_url('/index.php') ?>" class="flex flex-col items-center group">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="AppEstudiante" class="h-16 w-auto object-contain mb-2 transition-transform group-hover:scale-105 duration-300">
                <div class="text-center">
                    <h1 class="font-bold text-lg text-slate-900 dark:text-white uppercase tracking-widest leading-none transition-colors">JINHWAN</h1>
                    <span class="text-[10px] font-extrabold tracking-widest uppercase mt-1 inline-flex items-center gap-1" style="color: var(--tkd-primary, #06b6d4);">
                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: var(--tkd-primary, #06b6d4);"></span>
                        <?= htmlspecialchars($tema_marcial['nombre_visual'] ?? 'Portal Alumno') ?>
                    </span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <ul class="space-y-2">
                <li>
                    <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-white transition-all group">
                        <span class="material-icons-outlined transition-colors">arrow_back</span>
                        <span class="font-medium tracking-wide uppercase text-sm">Sitio Web</span>
                    </a>
                </li>
                <li>
                    <div class="my-4 border-t border-slate-200 dark:border-slate-800/80 transition-colors"></div>
                    <span class="px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 block">Menú Principal</span>
                </li>
                <li>
                    <a href="<?= base_url('/estudiante/dashboard') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all group <?= ($current_page ?? '') === 'dashboard' ? 'tkd-active-link' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">dashboard</span>
                        <span class="font-medium tracking-wide text-sm">Mi Resumen</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/estudiante/historial') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all group <?= ($current_page ?? '') === 'historial' ? 'tkd-active-link' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">history</span>
                        <span class="font-medium tracking-wide text-sm">Mi Historial</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/estudiante/estudio') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all group <?= ($current_page ?? '') === 'estudio' ? 'tkd-active-link' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">menu_book</span>
                        <span class="font-medium tracking-wide text-sm">Material Teórico</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('/usuario/calendario') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all group <?= ($current_page ?? '') === 'calendario' ? 'tkd-active-link' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform">event</span>
                        <span class="font-medium tracking-wide text-sm">Calendario</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Footer Compacto del Sidebar -->
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/80 flex items-center justify-between gap-2 transition-colors">
            <a href="<?= base_url('/usuario/perfil') ?>" class="flex items-center gap-2.5 min-w-0 flex-1 p-1 rounded-xl hover:bg-slate-200/60 dark:hover:bg-slate-800/60 transition-colors group" title="Mi Perfil">
                <?php if (!empty($_SESSION['foto_perfil'])): ?>
                    <img src="<?= base_url('/public/uploads/perfiles/' . $_SESSION['foto_perfil']) ?>" class="w-8 h-8 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-xs" style="background: var(--tkd-gradient, linear-gradient(135deg, var(--tkd-primary, #2563EB), var(--tkd-accent, #06b6d4)))">
                        <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-xs text-slate-900 dark:text-white truncate transition-colors leading-tight" title="<?= htmlspecialchars($nombre_usuario) ?>" style="transition: color 0.2s;" onmouseenter="this.style.color='var(--tkd-primary)'" onmouseleave="this.style.color=''">
                        <?= htmlspecialchars($nombre_usuario) ?>
                    </p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider leading-none mt-0.5">Alumno</p>
                </div>
            </a>

            <div class="flex items-center gap-1 shrink-0">
                <!-- Theme toggle compacto -->
                <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-800 rounded-lg p-1.5 transition-colors focus:outline-none cursor-pointer" title="Cambiar Tema">
                    <span id="theme-toggle-dark-icon" class="hidden material-icons-outlined text-lg">light_mode</span>
                    <span id="theme-toggle-light-icon" class="hidden material-icons-outlined text-lg">dark_mode</span>
                </button>

                <!-- Cerrar sesión compacto -->
                <a href="<?= base_url('/logout') ?>" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg p-1.5 transition-colors flex items-center justify-center cursor-pointer" title="Cerrar Sesión">
                    <span class="material-icons-outlined text-lg">logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto relative transition-colors duration-300">
        
        <header class="md:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 p-4 flex items-center justify-between z-30 transition-colors duration-300">
             <div class="flex items-center gap-3">
                 <button id="mobile-menu-btn" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                    <span class="material-icons-outlined text-3xl">menu</span>
                 </button>
                 <span class="font-bold text-lg text-slate-900 dark:text-white transition-colors">Portal Alumno</span>
             </div>
        </header>

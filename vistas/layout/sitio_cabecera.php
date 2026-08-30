<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol_id = $_SESSION['rol_id'] ?? null;
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$is_admin = $rol_id && Roles::esAdmin($rol_id);
$is_student = $rol_id == Roles::ESTUDIANTE;
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $page_title ?? 'Jinhwan Corporation - Taekwondo' ?></title>

    <link rel="icon" type="image/x-icon" href="<?= asset('img/visual/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        // Tema visual
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Control de animación: solo se reproduce una vez al entrar a la página
        if (!sessionStorage.getItem('jinhwan_intro_viewed')) {
            sessionStorage.setItem('jinhwan_intro_viewed', '1');
            document.documentElement.classList.add('first-visit-intro');
        } else {
            document.documentElement.classList.add('site-visited');
        }
    </script>

    <!-- React + TypeScript + Tailwind Bundle CSS -->
    <link rel="stylesheet" href="<?= asset('dist/assets/main.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.css" integrity="sha512-Ne9/ZPNVK3w3pBBX6xE86bNG295dJl4CHttrCp3WiuD0VLkVU1xlXnL7V/NsT3VXBWNEjpP2Dl_631+gOYAZfQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-50 via-slate-100 to-rose-50/30 dark:bg-[#080c16] font-body text-slate-800 dark:text-slate-200 antialiased selection:bg-rose-600 selection:text-white transition-colors duration-300">
<div class="flex min-h-screen md:h-screen md:overflow-hidden">
    
    <div class="md:hidden fixed top-0 w-full z-50 bg-white/90 dark:bg-[#0b0f19]/90 backdrop-blur-md shadow-md border-b border-slate-200 dark:border-slate-800 flex justify-between items-center px-4 py-3 transition-colors duration-300">
         <a class="flex items-center gap-2" href="<?= base_url('/') ?>">
            <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinnwhan Logo" class="h-10 w-auto object-contain">
            <span class="font-display font-bold text-lg text-slate-900 dark:text-white uppercase leading-none transition-colors">Jinnwhan</span>
         </a>
         <button class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors" id="mobile-menu-btn">
            <span class="material-icons-outlined text-3xl">menu</span>
        </button>
    </div>

    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 dark:bg-black/55 z-40 hidden md:hidden backdrop-blur-sm transition-opacity duration-300"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-60 border-r transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl md:shadow-none md:static
        bg-white dark:bg-slate-950
        border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200">

        <div class="absolute top-4 left-4 z-50">
            <button id="admin-menu-toggle" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800/80 text-slate-500 hover:text-rose-600 transition-all duration-300 focus:outline-none">
                <span class="material-icons-outlined text-2xl">menu</span>
            </button>

            <div id="admin-menu-dropdown" class="hidden absolute top-10 left-0 w-56 bg-white dark:bg-slate-900 backdrop-blur-md rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform origin-top-left transition-all duration-200 z-50">
                <?php if (isset($_SESSION['id'])): ?>
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/50 transition-colors">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= htmlspecialchars($_SESSION['rol_id'] ?? 'Usuario') ?></p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></p>
                    </div>
                    <div class="py-1">
                        <?php if ($is_admin): ?>
                            <a href="<?= base_url('/admin/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-rose-600 transition-colors">
                                Administración
                            </a>
                        <?php elseif ($is_student): ?>
                            <a href="<?= base_url('/estudiante/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-amber-500 transition-colors">
                                Mi Portal
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('/maestro/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-600 transition-colors">
                                Panel Instructor
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('/logout') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-rose-600 transition-colors">
                            <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">logout</span> Cerrar Sesión</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="py-1">
                        <a href="<?= base_url('/login') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-600 transition-colors">
                             <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">login</span> Iniciar Sesión</span>
                        </a>
                        <a href="<?= base_url('/registro') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-rose-600 transition-colors">
                             <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">how_to_reg</span> Registrarse</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="h-1 w-full bg-gradient-to-r from-blue-600 via-rose-600 to-amber-500 flex-shrink-0"></div>
        <div class="p-6 pt-10 flex flex-col items-center justify-center border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 transition-colors">
            <div class="relative w-36 h-36 mb-3 transition-transform duration-500 hover:scale-105 drop-shadow-md">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan Organization" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-widest leading-none mb-1 transition-colors">JINHWAN</h1>
                <h2 class="font-display font-bold text-base text-rose-600 dark:text-rose-500 tracking-widest uppercase">CORPORATION</h2>
            </div>
        </div>

        <nav class="flex-grow overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-rose-600 dark:hover:text-rose-400 transition-all group nav-link font-semibold">
                <span class="material-icons-outlined text-xl group-hover:text-rose-600 transition-colors">home</span>
                <span class="font-display tracking-wide uppercase">Inicio</span>
            </a>
            <a href="<?= base_url('/sedes') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-blue-600 dark:hover:text-blue-400 transition-all group nav-link font-semibold">
                <span class="material-icons-outlined text-blue-600 group-hover:scale-110 transition-transform">location_on</span>
                <span class="font-display tracking-wider uppercase">Sedes</span>
            </a>

            <a href="<?= base_url('/nosotros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-rose-600 dark:hover:text-rose-400 transition-all group nav-link font-semibold">
                <span class="material-icons-outlined text-rose-600 group-hover:scale-110 transition-transform">info</span>
                <span class="font-display tracking-wider uppercase">Nosotros</span>
            </a>
            <a href="<?= base_url('/miembros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-blue-600 dark:hover:text-blue-400 transition-all group nav-link font-semibold">
                <span class="material-icons-outlined group-hover:scale-110 transition-transform">people</span>
                <span class="font-display tracking-wider uppercase">Miembros</span>
            </a>
            <a href="<?= base_url('/galeria') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-rose-600 dark:hover:text-rose-400 transition-all group nav-link font-semibold">
                <span class="material-icons-outlined text-xl group-hover:text-rose-600 transition-colors">collections</span>
                <span class="font-display tracking-wide uppercase">Galería</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 transition-colors">
            <div class="flex items-center justify-between px-2">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Tema Visual</span>
                <button id="theme-toggle" type="button" class="text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg text-sm p-1.5 transition-colors focus:outline-none">
                    <span id="theme-toggle-dark-icon" class="hidden material-icons-outlined text-[20px]">light_mode</span>
                    <span id="theme-toggle-light-icon" class="hidden material-icons-outlined text-[20px]">dark_mode</span>
                </button>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden relative">
        
        <main class="flex-1 overflow-y-auto no-scrollbar scroll-smooth pt-16 md:pt-0" id="main-scroll">
            

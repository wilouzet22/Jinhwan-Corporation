<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Config\Roles;

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
    <title><?= $page_title ?? 'Jinnwhan Organization - Taekwondo' ?></title>

    <link rel="stylesheet" href="<?= asset('styles/output.css') ?>">
    <link rel="icon" type="image/x-icon" href="<?= asset('img/visual/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
        @theme {
            --color-tkd-blue: #2563EB;
            --color-tkd-red: #DC2626;
            --color-tkd-gold: #FACC15;
            --font-body: Inter, sans-serif;
            --font-display: Oswald, sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.css" integrity="sha512-Ne9/ZPNVK3w3pBBX6xE86bNG295dJl4CHttrCp3WiuD0VLkVU1xlXnL7V/NsT3VXBWNEjpP2Dl_631+gOYAZfQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 via-slate-50 to-red-50 dark:bg-[#0b0f19] font-body text-slate-800 dark:text-slate-200 antialiased selection:bg-tkd-red selection:text-white transition-colors duration-300">
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
        border-slate-200/60 dark:border-slate-800
        [background:linear-gradient(160deg,#ffffff_0%,#f0f4ff_60%,#fdf0f0_100%)] dark:[background:#020817]">

        <div class="absolute top-4 left-4 z-50">
            <button id="admin-menu-toggle" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800/80 text-slate-400 hover:text-tkd-red transition-all duration-300 focus:outline-none">
                <span class="material-icons-outlined text-2xl">menu</span>
            </button>

            <div id="admin-menu-dropdown" class="hidden absolute top-10 left-0 w-56 bg-white dark:bg-slate-900 backdrop-blur-md rounded-lg shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform origin-top-left transition-all duration-200 z-50">
                <?php if (isset($_SESSION['id'])): ?>
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/50 transition-colors">
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider"><?= htmlspecialchars($_SESSION['rol_id'] ?? 'Usuario') ?></p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></p>
                    </div>
                    <div class="py-1">
                        <?php if ($is_admin): ?>
                            <a href="<?= base_url('/admin/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-red transition-colors">
                                Administración
                            </a>
                        <?php elseif ($is_student): ?>
                            <a href="<?= base_url('/estudiante/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-gold transition-colors">
                                Mi Portal
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('/maestro/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-colors">
                                Panel Instructor
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('/logout') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-colors">
                            <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">logout</span> Cerrar Sesión</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="py-1">
                        <a href="<?= base_url('/login') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-colors">
                             <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">login</span> Iniciar Sesión</span>
                        </a>
                        <a href="<?= base_url('/registro') ?>" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-red transition-colors">
                             <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">how_to_reg</span> Registrarse</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="h-1 w-full bg-gradient-to-r from-tkd-blue via-tkd-red to-tkd-gold dark:opacity-30 flex-shrink-0"></div>
        <div class="p-8 pt-10 flex flex-col items-center justify-center border-b border-slate-200/60 dark:border-slate-800
            [background:linear-gradient(135deg,#f8faff_0%,#fff5f5_100%)] dark:[background:rgba(2,8,23,0.2)] transition-colors">
            <div class="relative w-40 h-40 mb-4 transition-transform duration-500 hover:scale-105 drop-shadow-md">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan Organization" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-widest leading-none mb-1 transition-colors">JINHWAN</h1>
                <h2 class="font-display font-bold text-lg text-tkd-red tracking-widest uppercase">CORPORATION</h2>
            </div>
        </div>

        <nav class="flex-grow overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
            <a href="<?= base_url('/') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-tkd-red dark:hover:text-tkd-red transition-all group nav-link">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-red transition-colors">home</span>
                <span class="font-display font-medium tracking-wide uppercase">Inicio</span>
            </a>
            <a href="<?= base_url('/sedes') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-tkd-blue dark:hover:text-tkd-blue transition-all group nav-link">
                <span class="material-icons-outlined text-tkd-blue group-hover:scale-110 transition-transform">location_on</span>
                <span class="font-display font-medium uppercase tracking-wider">Sedes</span>
            </a>

            <a href="<?= base_url('/nosotros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-tkd-red dark:hover:text-tkd-red transition-all group nav-link">
                <span class="material-icons-outlined text-tkd-red group-hover:scale-110 transition-transform">info</span>
                <span class="font-display font-medium uppercase tracking-wider">Nosotros</span>
            </a>
            <a href="<?= base_url('/miembros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all group nav-link">
                <span class="material-icons-outlined group-hover:scale-110 transition-transform">people</span>
                <span class="font-display font-medium uppercase tracking-wider">Miembros</span>
            </a>
            <a href="<?= base_url('/galeria') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-tkd-red dark:hover:text-tkd-red transition-all group nav-link">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-red transition-colors">collections</span>
                <span class="font-display font-medium tracking-wide uppercase">Galería</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 transition-colors">
            <div class="flex items-center justify-between px-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tema Visual</span>
                <button id="theme-toggle" type="button" class="text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg text-sm p-1.5 transition-colors focus:outline-none">
                    <span id="theme-toggle-dark-icon" class="hidden material-icons-outlined text-[20px]">light_mode</span>
                    <span id="theme-toggle-light-icon" class="hidden material-icons-outlined text-[20px]">dark_mode</span>
                </button>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden relative">
        
        <main class="flex-1 overflow-y-auto no-scrollbar scroll-smooth pt-16 md:pt-0" id="main-scroll">
            

<?php
// Iniciar sesión si no está iniciada para verificar estado de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/roles.php';
$rol_id = $_SESSION['rol_id'] ?? null;
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$is_admin = $rol_id && esAdmin($rol_id);
$is_student = $rol_id == ROL_ESTUDIANTE;
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Jinnwhan Organization - Taekwondo</title>
    
    <!-- Google Fonts: Oswald (Headings) & Roboto (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="/jinwha/js/styles/main-config.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.css" integrity="sha512-Ne9/ZPNVK3w3pBBX6xE86bNG295dJl4CHttrCp3WiuD0VLkVU1xlXnL7V/NsT3VXBWNEjpP2Dl_631+gOYAZfQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="style/custom.css" rel="stylesheet">
</head>
<body class="bg-tkd-gray dark:bg-tkd-black font-body text-slate-800 dark:text-slate-200 antialiased selection:bg-tkd-red selection:text-white overflow-hidden">
<div class="flex h-screen overflow-hidden">
    <!-- Mobile Header -->
    <div class="md:hidden fixed top-0 w-full z-50 bg-white/90 dark:bg-tkd-black/90 backdrop-blur-md shadow-lg border-b border-slate-200 dark:border-slate-800 flex justify-between items-center px-4 py-3">
         <a class="flex items-center gap-2" href="/jinwha/index.php">
            <img src="/jinwha/img/visual/logo.png" alt="Jinnwhan Logo" class="h-10 w-auto object-contain">
            <span class="font-display font-bold text-lg text-slate-900 dark:text-white uppercase leading-none">Jinnwhan</span>
         </a>
         <button class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors" id="mobile-menu-btn">
            <span class="material-icons-outlined text-3xl">menu</span>
        </button>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden glass-backdrop transition-opacity duration-300"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-tkd-black border-r border-slate-200 dark:border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl md:shadow-none md:static relative">
        
        <!-- Admin Menu Hamburger (Top Left) -->
        <div class="absolute top-4 left-4 z-50">
            <button id="admin-menu-toggle" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-tkd-red transition-all duration-300">
                <span class="material-icons-outlined text-2xl">menu</span>
            </button>
            
            <!-- Admin Dropdown Menu -->
            <div id="admin-menu-dropdown" class="hidden absolute top-10 left-0 w-56 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden transform origin-top-left transition-all duration-200 z-50">
                <?php if ($is_admin): ?>
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Administración</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></p>
                    </div>
                    <div class="py-1">

                        <a href="/jinwha/panel/administracion-cedes.php" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-tkd-red">
                            <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">place</span> Sedes</span>
                        </a>
                         <a href="/jinwha/panel/administracion-ascensos.php" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-tkd-red">
                            <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">timeline</span> Ascensos</span>
                        </a>

                        <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                        <a href="/jinwha/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">logout</span> Cerrar Sesión</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="py-1">
                        <a href="/jinwha/panel/administracion-login.php" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-tkd-blue">
                             <span class="flex items-center gap-2"><span class="material-icons-outlined text-sm">login</span> Iniciar Sesión</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Header (Logo) -->
        <div class="p-8 pt-12 flex flex-col items-center justify-center border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            <div class="relative w-40 h-40 mb-4 transition-transform duration-500 hover:scale-105">
                <img src="/jinwha/img/visual/logo.png" alt="Jinhwan Organization" class="w-full h-full object-contain filter drop-shadow-xl">
            </div>
            <div class="text-center">
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-widest leading-none mb-1">JINHWAN</h1>
                <h2 class="font-display font-bold text-lg text-tkd-red tracking-widest uppercase">CORPORATION</h2>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
            <a href="/jinwha/index.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-red transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-red transition-colors">home</span>
                <span class="font-display font-medium tracking-wide uppercase">Inicio</span>
            </a>
            <a href="/jinwha/includes/sidebar/cedes.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-blue transition-colors">place</span>
                <span class="font-display font-medium tracking-wide uppercase">Sedes</span>
            </a>
            <?php if ($is_admin || $is_student): ?>
            <a href="/jinwha/includes/sidebar/ascensos.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-gold transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-gold transition-colors">timeline</span>
                <span class="font-display font-medium tracking-wide uppercase">Ascensos</span>
            </a>
            <?php endif; ?>
            <a href="/jinwha/includes/sidebar/nosotros.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-red transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-red transition-colors">groups</span>
                <span class="font-display font-medium tracking-wide uppercase">Nosotros</span>
            </a>
            <a href="/jinwha/includes/sidebar/instructores.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-blue transition-colors">sports_martial_arts</span>
                <span class="font-display font-medium tracking-wide uppercase">Instructores</span>
            </a>

            <a href="/jinwha/includes/sidebar/galeria.php" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-red transition-all group">
                <span class="material-icons-outlined text-xl group-hover:text-tkd-red transition-colors">collections</span>
                <span class="font-display font-medium tracking-wide uppercase">Galería</span>
            </a>
        </nav>

        <!-- Sidebar Footer Removed (Admin Button was here) -->
        <div class="h-4"></div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Scrollable Content Area -->
        <main class="flex-1 overflow-y-auto no-scrollbar scroll-smooth pt-16 md:pt-0" id="main-scroll">
            
<script src="/jinwha/js/modules/navigation.js" defer></script>

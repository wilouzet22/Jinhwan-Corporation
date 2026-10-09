<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_usuario = $_SESSION['nombre'] ?? $_SESSION['usuario']['nombre'] ?? 'Maestro';
$foto_perfil = null;

if (isset($_SESSION['id'])) {
    $user_model = new Usuario();
    $logged_user = $user_model->getById($_SESSION['id']);
    if ($logged_user) {
        $nombre_usuario = $logged_user['nombre'] . ' ' . $logged_user['apellido'];
        $foto_perfil = $logged_user['foto_perfil'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= $page_title ?? 'Panel de Maestro/Instructor' ?></title>
    
    <link rel="icon" type="image/x-icon" href="<?= asset('img/visual/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <!-- React + TypeScript Bundle CSS only -->
    <link rel="stylesheet" href="<?= asset('dist/assets/main.css') ?>">
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-body antialiased selection:bg-rose-600 selection:text-white transition-colors duration-300">
<div class="flex min-h-screen md:h-screen md:overflow-hidden">
    
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 z-40 hidden md:hidden transition-opacity duration-300 backdrop-blur-sm"></div>

    <aside id="maestro-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-xl md:shadow-none md:static">
        <div class="h-1 w-full bg-gradient-to-r from-purple-700 via-purple-500 to-amber-500"></div>
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-center transition-colors">
            <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-3 group">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="AppAdmin" class="h-10 w-auto object-contain transition-transform group-hover:scale-105 duration-300">
                <div class="flex flex-col">
                    <h1 class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wider leading-none transition-colors">JINHWAN</h1>
                    <span class="text-[10px] font-extrabold text-purple-600 dark:text-purple-400 tracking-widest uppercase mt-0.5 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                        Panel Instructor
                    </span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <ul class="space-y-1">
                <li>
                    <span class="px-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 block mt-2">Navegación</span>
                </li>
                <li>
                    <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors group">
                        <span class="material-icons-outlined text-xl group-hover:text-purple-700 transition-colors">public</span>
                        <span class="font-medium text-sm">Volver al Sitio</span>
                    </a>
                </li>
                <li>
                    <div class="my-4 border-t border-slate-200 dark:border-slate-800/60 transition-colors"></div>
                    <span class="px-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2 block">Instructor</span>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/dashboard') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'dashboard' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'dashboard' ? 'text-purple-500 dark:text-purple-300' : '' ?>">dashboard</span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/alumnos') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'alumnos' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'alumnos' ? 'text-purple-500 dark:text-purple-300' : '' ?>">people</span>
                        <span class="text-sm font-medium">Alumnos de Jinhwan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/cronogramas') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'cronogramas' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'cronogramas' ? 'text-purple-500 dark:text-purple-300' : '' ?>">event_note</span>
                        <span class="text-sm font-medium">Cronogramas de Clase</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/ejercicios') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'ejercicios' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'ejercicios' ? 'text-purple-500 dark:text-purple-300' : '' ?>">fitness_center</span>
                        <span class="text-sm font-medium">Biblioteca Ejercicios</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/usuario/calendario') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'calendario' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'calendario' ? 'text-purple-500 dark:text-purple-300' : '' ?>">event</span>
                        <span class="text-sm font-medium">Calendario</span>
                    </a>
                </li>

                <?php
                $hasSedes = Security::hasPermission('sedes');
                $hasRegistros = Security::hasPermission('registros');
                $hasAscensosAdmin = Security::hasPermission('ascensos');
                $hasCalendarioAdmin = Security::hasPermission('calendario');
                $hasGaleria = Security::hasPermission('galeria');
                $hasReportes = Security::hasPermission('reportes');

                if ($hasSedes || $hasRegistros || $hasAscensosAdmin || $hasCalendarioAdmin || $hasGaleria || $hasReportes): 
                ?>
                <li>
                    <div class="my-4 border-t border-slate-200 dark:border-slate-800/60 transition-colors"></div>
                    <span class="px-4 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2 block">Administración Extra</span>
                </li>
                <?php if($hasSedes): ?>
                <li>
                    <a href="<?= base_url('/admin/sedes') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'sedes' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'sedes' ? 'text-purple-500 dark:text-purple-300' : '' ?>">place</span>
                        <span class="text-sm font-medium">Sedes</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasRegistros): ?>
                <li>
                    <a href="<?= base_url('/admin/registros') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'registros' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'registros' ? 'text-purple-500 dark:text-purple-300' : '' ?>">how_to_reg</span>
                        <span class="text-sm font-medium">Aprobar Registros</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasAscensosAdmin): ?>
                <li>
                    <a href="<?= base_url('/admin/ascensos') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'ascensos' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'ascensos' ? 'text-purple-500 dark:text-purple-300' : '' ?>">military_tech</span>
                        <span class="text-sm font-medium">Ascensos</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasCalendarioAdmin): ?>
                <li>
                    <a href="<?= base_url('/admin/calendario') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'calendario_admin' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'calendario_admin' ? 'text-purple-500 dark:text-purple-300' : '' ?>">edit_calendar</span>
                        <span class="text-sm font-medium">Gestionar Calendario</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasGaleria): ?>
                <li>
                    <a href="<?= base_url('/admin/galeria') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'galeria' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'galeria' ? 'text-purple-500 dark:text-purple-300' : '' ?>">collections</span>
                        <span class="text-sm font-medium">Gestionar Galería</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasReportes): ?>
                <li>
                    <a href="<?= base_url('/admin/reportes') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'reportes' ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'reportes' ? 'text-purple-500 dark:text-purple-300' : '' ?>">analytics</span>
                        <span class="text-sm font-medium">Reportes</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- Footer Compacto del Sidebar -->
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/80 flex items-center justify-between gap-2 transition-colors">
            <a href="<?= base_url('/usuario/perfil') ?>" class="flex items-center gap-2.5 min-w-0 flex-1 p-1 rounded-xl hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors group" title="Mi Perfil">
                <?php if (!empty($_SESSION['foto_perfil'])): ?>
                    <img src="<?= base_url('/public/uploads/perfiles/' . $_SESSION['foto_perfil']) ?>" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-700 to-purple-600 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-xs">
                        <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-xs text-slate-900 dark:text-white truncate group-hover:text-purple-700 transition-colors leading-tight" title="<?= htmlspecialchars($nombre_usuario) ?>">
                        <?= htmlspecialchars($nombre_usuario) ?>
                    </p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider leading-none mt-0.5">Instructor</p>
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
                 <button id="mobile-menu-btn" class="p-2 -ml-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors">
                    <span class="material-icons-outlined text-2xl">menu</span>
                 </button>
                 <span class="font-display font-bold text-lg text-slate-900 dark:text-white">JINHWAN</span>
             </div>
             <a href="<?= base_url('/index.php') ?>" class="text-xs font-semibold text-purple-700 uppercase tracking-wider">Ver Web</a>
        </header>

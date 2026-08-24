<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_usuario = $_SESSION['nombre'] ?? $_SESSION['usuario']['nombre'] ?? 'Maestro';
$foto_perfil = null;

if (isset($_SESSION['id'])) {
    $user_model = new \App\Models\Usuario();
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
    
    <link rel="stylesheet" href="<?= asset('styles/output.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
        @theme {
            --color-tkd-blue: #2563EB;
            --color-tkd-red: #DC2626;
            --color-tkd-gold: #FACC15;
            --color-tkd-purple: #7C3AED;
            --color-tkd-purple-hover: #6D28D9;
            --font-body: Inter, sans-serif;
            --font-display: Oswald, sans-serif;
        }
    </style>
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-body antialiased selection:bg-tkd-purple selection:text-white transition-colors duration-300">
<div class="flex min-h-screen md:h-screen md:overflow-hidden">
    
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 dark:bg-black/60 z-40 hidden md:hidden transition-opacity duration-300 backdrop-blur-sm"></div>

    <aside id="maestro-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-xl md:shadow-none md:static">
        <div class="h-1 w-full bg-gradient-to-r from-tkd-purple via-purple-500 to-amber-500"></div>
        <div class="p-6 border-b border-slate-800 flex items-center justify-center">
            <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-3 group">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="AppAdmin" class="h-10 w-auto object-contain transition-transform group-hover:scale-105 duration-300">
                <div class="flex flex-col">
                    <h1 class="font-display font-bold text-xl text-white tracking-wider leading-none">JINHWAN</h1>
                    <span class="text-[10px] font-extrabold text-purple-400 tracking-widest uppercase mt-0.5 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                        Panel Instructor
                    </span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <ul class="space-y-1">
                <li>
                    <span class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block mt-2">Navegación</span>
                </li>
                <li>
                    <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors group">
                        <span class="material-icons-outlined text-xl group-hover:text-tkd-purple transition-colors">public</span>
                        <span class="font-medium text-sm">Volver al Sitio</span>
                    </a>
                </li>
                <li>
                    <div class="my-4 border-t border-slate-800/60"></div>
                    <span class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block">Instructor</span>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/dashboard') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'dashboard' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'dashboard' ? 'text-tkd-purple' : '' ?>">dashboard</span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/alumnos') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'alumnos' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'alumnos' ? 'text-tkd-purple' : '' ?>">people</span>
                        <span class="text-sm font-medium">Mis Alumnos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/maestro/solicitudes-ascenso') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'solicitudes_ascenso' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'solicitudes_ascenso' ? 'text-tkd-purple' : '' ?>">timeline</span>
                        <span class="text-sm font-medium">Solicitudes Ascenso</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/usuario/calendario') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'calendario' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'calendario' ? 'text-tkd-purple' : '' ?>">event</span>
                        <span class="text-sm font-medium">Calendario</span>
                    </a>
                </li>

                <?php
                $hasSedes = \App\Core\Security::hasPermission('sedes');
                $hasRegistros = \App\Core\Security::hasPermission('registros');
                $hasAscensosAdmin = \App\Core\Security::hasPermission('ascensos');
                $hasCalendarioAdmin = \App\Core\Security::hasPermission('calendario');
                $hasGaleria = \App\Core\Security::hasPermission('galeria');
                $hasReportes = \App\Core\Security::hasPermission('reportes');

                if ($hasSedes || $hasRegistros || $hasAscensosAdmin || $hasCalendarioAdmin || $hasGaleria || $hasReportes): 
                ?>
                <li>
                    <div class="my-4 border-t border-slate-800/60"></div>
                    <span class="px-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-2 block">Administración Extra</span>
                </li>
                <?php if($hasSedes): ?>
                <li>
                    <a href="<?= base_url('/admin/sedes') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'sedes' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'sedes' ? 'text-tkd-purple' : '' ?>">place</span>
                        <span class="text-sm font-medium">Sedes</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasRegistros): ?>
                <li>
                    <a href="<?= base_url('/admin/registros') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'registros' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'registros' ? 'text-tkd-purple' : '' ?>">how_to_reg</span>
                        <span class="text-sm font-medium">Aprobar Registros</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasAscensosAdmin): ?>
                <li>
                    <a href="<?= base_url('/admin/ascensos') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'ascensos' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'ascensos' ? 'text-tkd-purple' : '' ?>">military_tech</span>
                        <span class="text-sm font-medium">Aprobar Ascensos</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasCalendarioAdmin): ?>
                <li>
                    <a href="<?= base_url('/admin/calendario') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'calendario_admin' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'calendario_admin' ? 'text-tkd-purple' : '' ?>">edit_calendar</span>
                        <span class="text-sm font-medium">Gestionar Calendario</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasGaleria): ?>
                <li>
                    <a href="<?= base_url('/admin/galeria') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'galeria' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'galeria' ? 'text-tkd-purple' : '' ?>">collections</span>
                        <span class="text-sm font-medium">Gestionar Galería</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($hasReportes): ?>
                <li>
                    <a href="<?= base_url('/admin/reportes') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors group <?= ($current_page ?? '') === 'reportes' ? 'bg-tkd-purple/20 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="material-icons-outlined text-xl transition-transform group-hover:scale-110 <?= ($current_page ?? '') === 'reportes' ? 'text-tkd-purple' : '' ?>">analytics</span>
                        <span class="text-sm font-medium">Reportes</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

            </ul>
        </nav>
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
             <div class="flex items-center justify-between mb-3 px-2">
                 <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tema Visual</span>
                 <button id="theme-toggle" type="button" class="text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg text-sm p-1.5 transition-colors focus:outline-none">
                     <span id="theme-toggle-dark-icon" class="hidden material-icons-outlined text-[20px]">light_mode</span>
                     <span id="theme-toggle-light-icon" class="hidden material-icons-outlined text-[20px]">dark_mode</span>
                 </button>
             </div>
             
             <a href="<?= base_url('/usuario/perfil') ?>" class="flex items-center gap-3 p-2 hover:bg-slate-800 rounded-lg transition-colors group <?= ($current_page ?? '') === 'perfil' ? 'bg-slate-800' : '' ?>">
                <?php if (!empty($_SESSION['foto_perfil'])): ?>
                    <img src="<?= base_url('/public/uploads/perfiles/' . $_SESSION['foto_perfil']) ?>" class="w-10 h-10 rounded-lg object-cover shadow-sm shrink-0 border border-slate-700">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-tkd-purple to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                        <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="text-sm overflow-hidden flex-1">
                    <p class="font-bold text-white truncate group-hover:text-tkd-purple transition-colors" title="<?= htmlspecialchars($nombre_usuario) ?>"><?= htmlspecialchars($nombre_usuario) ?></p>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Instructor</p>
                </div>
             </a>
             
             <a href="<?= base_url('/logout') ?>" class="flex items-center justify-center gap-2 w-full p-2 text-red-400 hover:bg-red-900/20 hover:text-red-300 rounded-lg transition-colors text-sm font-semibold mt-1" title="Cerrar Sesión">
                <span class="material-icons-outlined text-lg">logout</span>
                <span>Cerrar Sesión</span>
              </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto relative transition-colors duration-300">
        
        <header class="md:hidden bg-slate-900 border-b border-slate-800 p-4 flex items-center justify-between z-30 transition-colors duration-300">
             <div class="flex items-center gap-3">
                 <button id="mobile-menu-btn" class="p-2 -ml-2 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors">
                    <span class="material-icons-outlined text-2xl">menu</span>
                 </button>
                 <span class="font-display font-bold text-lg text-white">JINHWAN</span>
             </div>
             <a href="<?= base_url('/index.php') ?>" class="text-xs font-semibold text-tkd-purple uppercase tracking-wider">Ver Web</a>
        </header>

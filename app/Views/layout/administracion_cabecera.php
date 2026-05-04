<?php
// Iniciar sesión si no está iniciada para verificar estado de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// En administración, a veces usamos 'usuario' o variables directas de sesión
$nombre_usuario = $_SESSION['nombre'] ?? $_SESSION['usuario']['nombre'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= $page_title ?? 'Panel de Administración' ?></title>
    <!-- Google Fonts: Oswald (Headings) & Roboto (Body) -->
    <link rel="stylesheet" href="<?= asset('styles/output.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="<?= asset('js/styles/administracion-config.js') ?>"></script>
</head>
<body class="bg-tkd-gray dark:bg-tkd-black text-slate-800 dark:text-slate-200 font-body antialiased selection:bg-tkd-red selection:text-white">
<div class="flex min-h-screen bg-tkd-gray dark:bg-tkd-black">
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden glass-backdrop transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl md:shadow-none md:static">
        <div class="p-8 border-b border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50">
            <a href="<?= base_url('/index.php') ?>" class="flex flex-col items-center group">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="AppAdmin" class="h-24 w-auto object-contain mb-3 drop-shadow-lg transition-transform group-hover:scale-105 duration-300">
                <div class="text-center">
                    <h1 class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-widest leading-none">JINHWAN</h1>
                    <span class="text-xs font-bold text-tkd-red tracking-[0.2em] uppercase">Admin Panel</span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <ul class="space-y-2">
                <li>
                    <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-tkd-blue transition-all group">
                        <span class="material-icons-outlined group-hover:text-tkd-blue transition-colors">arrow_back</span>
                        <span class="font-display font-medium tracking-wide uppercase">Volver</span>
                    </a>
                </li>
                <li>
                    <div class="my-4 border-t border-slate-100 dark:border-slate-800"></div>
                    <span class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Gestión</span>
                </li>
                <li>
                    <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'dashboard' ? 'bg-blue-50 dark:bg-blue-900/20 text-tkd-blue dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'dashboard' ? 'text-tkd-blue dark:text-blue-400' : '' ?>">dashboard</span>
                        <span class="font-display font-medium tracking-wide">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/sedes') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'sedes' ? 'bg-blue-50 dark:bg-blue-900/20 text-tkd-blue dark:text-blue-400 ring-1 ring-blue-200 dark:ring-blue-800' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'sedes' ? 'text-tkd-blue dark:text-blue-400' : '' ?>">place</span>
                        <span class="font-display font-medium tracking-wide">Sedes</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/ascensos') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'ascensos' ? 'bg-amber-50 dark:bg-amber-900/20 text-tkd-gold dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-800' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'ascensos' ? 'text-tkd-gold dark:text-amber-400' : '' ?>">timeline</span>
                        <span class="font-display font-medium tracking-wide">Ascensos</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/miembros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'miembros' ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'miembros' ? 'text-emerald-600 dark:text-emerald-400' : '' ?>">people</span>
                        <span class="font-display font-medium tracking-wide">Miembros</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/registros') ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'registros' ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-200 dark:ring-indigo-800' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'registros' ? 'text-indigo-600 dark:text-indigo-400' : '' ?>">how_to_reg</span>
                        <span class="font-display font-medium tracking-wide">Solicitudes</span>
                    </a>
                </li>
                
            </ul>
        </nav>
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
             <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition group">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-tkd-blue to-blue-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-white dark:ring-slate-800 shadow-md">
                    <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                </div>
                <div class="text-sm overflow-hidden flex-1">
                    <p class="font-medium text-slate-700 dark:text-slate-200 truncate" title="<?= htmlspecialchars($nombre_usuario) ?>"><?= htmlspecialchars($nombre_usuario) ?></p>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Perfil</p>
                </div>
             </a>
             <a href="<?= base_url('/logout') ?>" class="flex items-center justify-center gap-2 w-full p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors text-sm font-medium" title="Cerrar Sesión">
                <span class="material-icons-outlined text-lg">logout</span>
                <span>Salir</span>
             </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        <!-- Top Mobile Header (visible only on mobile) -->
        <header class="md:hidden bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-4 flex items-center justify-between z-30">
             <div class="flex items-center gap-3">
                 <button id="mobile-menu-btn" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors">
                    <span class="material-icons-outlined text-3xl">menu</span>
                 </button>
                 <a href="<?= base_url('/index.php') ?>" class="font-bold text-lg">AppAdmin</a>
             </div>
             <a href="<?= base_url('/index.php') ?>" class="text-sm text-blue-600">Volver a Web</a>
        </header>

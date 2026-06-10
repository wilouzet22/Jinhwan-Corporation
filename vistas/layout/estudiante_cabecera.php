<?php
// Iniciar sesión si no está iniciada para verificar estado de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_usuario = $_SESSION['nombre'] ?? 'Estudiante';
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= $page_title ?? 'Portal del Alumno' ?></title>
    <!-- Google Fonts: Oswald (Headings) & Inter (Body) -->
    <link rel="stylesheet" href="<?= asset('styles/output.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="<?= asset('js/styles/administracion-config.js') ?>"></script>
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-200 font-body antialiased selection:bg-blue-500 selection:text-white">
<div class="flex min-h-screen bg-[#0f172a]">
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/55 z-40 hidden md:hidden transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="estudiante-sidebar" class="fixed inset-y-0 left-0 z-50 w-60 bg-[#1e293b] border-r border-slate-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-xl md:shadow-none md:static">
        <div class="p-8 border-b border-slate-800 flex flex-col items-center justify-center">
            <a href="<?= base_url('/index.php') ?>" class="flex flex-col items-center group">
                <img src="<?= asset('img/visual/logo.svg') ?>" alt="AppEstudiante" class="h-24 w-auto object-contain mb-3 transition-transform group-hover:scale-105 duration-300">
                <div class="text-center">
                    <h1 class="font-bold text-xl text-white uppercase tracking-widest leading-none">JINHWAN</h1>
                    <span class="text-xs font-semibold text-blue-400 tracking-[0.1em] uppercase">Portal Alumno</span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
            <ul class="space-y-2">
                <li>
                    <a href="<?= base_url('/index.php') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-all group">
                        <span class="material-icons-outlined transition-colors">arrow_back</span>
                        <span class="font-medium tracking-wide uppercase text-sm">Sitio Web</span>
                    </a>
                </li>
                <li>
                    <div class="my-4 border-t border-slate-800/80"></div>
                    <span class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Menú Principal</span>
                </li>
                <li>
                    <a href="<?= base_url('/estudiante/dashboard') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'dashboard' ? 'bg-blue-500/10 border-l-2 border-l-blue-500 text-blue-400' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'dashboard' ? 'text-blue-400' : '' ?>">dashboard</span>
                        <span class="font-medium tracking-wide text-sm">Mi Resumen</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/estudiante/estudio') ?>" class="flex items-center gap-4 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition-all group <?= ($current_page ?? '') === 'estudio' ? 'bg-red-500/10 border-l-2 border-l-red-500 text-red-400' : '' ?>">
                        <span class="material-icons-outlined text-xl group-hover:scale-110 transition-transform <?= ($current_page ?? '') === 'estudio' ? 'text-red-400' : '' ?>">menu_book</span>
                        <span class="font-medium tracking-wide text-sm">Material Teórico</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-6 border-t border-slate-800 bg-slate-900/50">
             <a href="#" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition group">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                    <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                </div>
                <div class="text-sm overflow-hidden flex-1">
                    <p class="font-medium text-white truncate" title="<?= htmlspecialchars($nombre_usuario) ?>"><?= htmlspecialchars($nombre_usuario) ?></p>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Alumno</p>
                </div>
             </a>
             <a href="<?= base_url('/logout') ?>" class="flex items-center justify-center gap-2 w-full p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-colors text-sm font-medium mt-2" title="Cerrar Sesión">
                <span class="material-icons-outlined text-lg">logout</span>
                <span>Salir</span>
             </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative bg-[#0f172a]">
        <!-- Top Mobile Header (visible only on mobile) -->
        <header class="md:hidden bg-slate-900 border-b border-slate-800 p-4 flex items-center justify-between z-30">
             <div class="flex items-center gap-3">
                 <button id="mobile-menu-btn" class="p-2 rounded-md hover:bg-slate-800 text-slate-300 transition-colors">
                    <span class="material-icons-outlined text-3xl">menu</span>
                 </button>
                 <span class="font-bold text-lg text-white">Portal Alumno</span>
             </div>
        </header>

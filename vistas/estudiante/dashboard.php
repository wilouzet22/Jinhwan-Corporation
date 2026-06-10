<?php 
$current_page = 'dashboard';
include __DIR__ . '/../layout/estudiante_cabecera.php'; 
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-100">Portal del Alumno</h1>
            <p class="text-slate-400 mt-1 text-sm">Bienvenido de nuevo, <?= htmlspecialchars($estudiante['nombre'] ?? 'Estudiante') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-slate-900 rounded-xl shadow-sm border border-slate-800 p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-slate-800 rounded-full flex items-center justify-center text-4xl font-bold text-blue-500 mb-4 border border-slate-700">
                    <?= substr($estudiante['nombre'] ?? 'A', 0, 1) ?>
                </div>
                <h2 class="text-xl font-bold text-slate-100 mb-1"><?= htmlspecialchars(($estudiante['nombre'] ?? '') . ' ' . ($estudiante['apellido'] ?? '')) ?></h2>
                <p class="text-slate-400 text-sm mb-6">Doc: <?= htmlspecialchars($estudiante['numero_documento'] ?? '') ?></p>
                
                <div class="w-full bg-slate-800 rounded-lg p-3 mb-3 border border-slate-700 flex justify-between items-center">
                    <span class="text-slate-400 text-sm">Cinturón</span>
                    <span class="text-blue-400 font-bold text-sm"><?= htmlspecialchars($estudiante['nombre_nivel'] ?? 'Blanco') ?></span>
                </div>
                <div class="w-full bg-slate-800 rounded-lg p-3 border border-slate-700 flex justify-between items-center">
                    <span class="text-slate-400 text-sm">Sede</span>
                    <span class="text-slate-100 font-semibold text-sm"><?= htmlspecialchars($estudiante['nombre_sede'] ?? 'Sin Asignar') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <a href="<?= base_url('/estudiante/estudio') ?>" class="bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-800 flex items-center gap-6 hover:border-slate-700 transition-colors group">
                <div class="w-14 h-14 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-icons-outlined text-3xl">menu_book</span>
                </div>
                <div class="flex-grow">
                    <h3 class="text-lg font-bold text-slate-100 mb-1 group-hover:text-red-400 transition-colors">Estudio Teórico</h3>
                    <p class="text-slate-400 text-sm">Accede a todo el material teórico, videos y documentos para preparar tu próximo ascenso o repasar temas anteriores.</p>
                </div>
                <div class="hidden sm:block ml-auto text-slate-500 group-hover:text-red-400 transition-colors">
                    <span class="material-icons-outlined">arrow_forward_ios</span>
                </div>
            </a>

            <div class="bg-slate-900 rounded-xl p-6 border border-slate-800 flex items-center gap-6 opacity-70">
                <div class="w-14 h-14 rounded-lg bg-slate-800 text-slate-500 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-outlined text-3xl">history</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-400 mb-1">Mi Historial (Próximamente)</h3>
                    <p class="text-slate-500 text-sm">Aquí podrás ver tu progreso, asistencias y fechas de ascensos anteriores.</p>
                </div>
            </div>
        </div>
        
    </div>

</main>

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>

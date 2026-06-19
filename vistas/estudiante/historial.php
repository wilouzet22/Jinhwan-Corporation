<?php 
$current_page = 'historial';
include __DIR__ . '/../layout/estudiante_cabecera.php'; 
?>

<main class="flex-grow p-6 lg:p-10 space-y-8 overflow-y-auto h-screen custom-scrollbar transition-colors duration-300">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 transition-colors">Mi Historial de Ascensos</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Tu progreso en la academia a lo largo del tiempo</p>
        </div>
    </div>

    <!-- Timeline Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 lg:p-10 transition-colors duration-300">
        <?php if (empty($ascensos)): ?>
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500">
                    <span class="material-icons-outlined text-3xl">timeline</span>
                </div>
                <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-1">Aún no tienes historial</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Tus ascensos aparecerán aquí conforme vayas progresando.</p>
            </div>
        <?php else: ?>
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 dark:before:via-slate-700 before:to-transparent">
                <?php foreach ($ascensos as $ascenso): 
                    $estadoColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400';
                    $estadoIcon = 'pending_actions';
                    $borderColor = 'border-yellow-200 dark:border-yellow-800/50';
                    
                    if ($ascenso['estado'] == 'aprobado') {
                        $estadoColor = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400';
                        $estadoIcon = 'military_tech';
                        $borderColor = 'border-emerald-200 dark:border-emerald-800/50';
                    } elseif ($ascenso['estado'] == 'rechazado') {
                        $estadoColor = 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400';
                        $estadoIcon = 'cancel';
                        $borderColor = 'border-red-200 dark:border-red-800/50';
                    }
                ?>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <!-- Icon Marker -->
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white dark:border-slate-900 <?= $ascenso['estado'] == 'aprobado' ? 'bg-tkd-blue text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-300' ?> shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        <span class="material-icons-outlined text-xl"><?= $estadoIcon ?></span>
                    </div>
                    
                    <!-- Content Card -->
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-800 p-5 rounded-xl border <?= $borderColor ?> shadow-sm hover:shadow-md transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 gap-2">
                            <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100">
                                <?= $ascenso['estado'] == 'aprobado' ? 'Ascendido a' : 'Solicitud para' ?> <?= htmlspecialchars($ascenso['grado_solicitado']) ?>
                            </h3>
                            <span class="inline-flex w-max text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider <?= $estadoColor ?>">
                                <?= htmlspecialchars($ascenso['estado']) ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-400 mb-3">
                            <span class="bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded border border-slate-200 dark:border-slate-700 font-medium">
                                <?= htmlspecialchars($ascenso['grado_actual']) ?>
                            </span>
                            <span class="material-icons-outlined text-slate-400 text-sm">arrow_forward</span>
                            <span class="bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded border border-slate-200 dark:border-slate-700 font-medium">
                                <?= htmlspecialchars($ascenso['grado_solicitado']) ?>
                            </span>
                        </div>
                        
                        <?php if(!empty($ascenso['observaciones'])): ?>
                            <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-100 dark:border-slate-700/50 mb-3">
                                <p class="text-sm text-slate-600 dark:text-slate-300 italic">"<?= htmlspecialchars($ascenso['observaciones']) ?>"</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <span class="flex items-center gap-1">
                                <span class="material-icons-outlined text-[14px]">person</span>
                                Maestro: <?= htmlspecialchars($ascenso['maestro_nombre'] . ' ' . $ascenso['maestro_apellido']) ?>
                            </span>
                            <span class="flex items-center gap-1 font-medium">
                                <span class="material-icons-outlined text-[14px]">event</span>
                                <?= !empty($ascenso['fecha_resolucion']) ? date('d M, Y', strtotime($ascenso['fecha_resolucion'])) : 'En proceso' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../layout/estudiante_pie.php'; ?>

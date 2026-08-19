<?php
if (!isset($_SESSION['temp_registro'])) {
    header("Location: " . base_url('/registro'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Completar Registro - Jinhwan Corporation</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="<?= asset('styles/output.css') ?>" rel="stylesheet">

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
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-slate-100 dark:bg-[#0b0f19] min-h-screen font-body flex flex-col items-center justify-center relative overflow-x-hidden overflow-y-auto selection:bg-tkd-red selection:text-white py-12">

    <div class="fixed inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-[4px] scale-105 opacity-50 dark:opacity-30" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-white/60 dark:from-[#0b0f19]/90 via-white/40 dark:via-[#0b0f19]/80 to-blue-100/50 dark:to-[#111827]/80"></div>
    </div>

    <div class="w-full max-w-2xl m-4 bg-white dark:bg-slate-900/80 backdrop-blur-md rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800/80 relative z-10 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>

        <div class="p-6 md:p-8">
            <div class="text-center mb-6">
                <div class="inline-block mb-3">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-16 w-auto object-contain">
                </div>
                <h2 class="text-2xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider">Completa tus datos</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs italic">Casi terminas. Necesitamos unos datos más para tu solicitud.</p>
            </div>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4" method="POST" action="<?= base_url('/registro/completar/process') ?>">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Nombre(s)</label>
                    <input name="nombre" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Apellido(s)</label>
                    <input name="apellido" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Fecha de Nacimiento</label>
                    <input name="fecha_nacimiento" type="date" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Teléfono</label>
                    <input name="telefono" type="tel" placeholder="Ej: +57 300 123 4567" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Sede de Entrenamiento</label>
                    <select name="sede_id" required class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50">
                        <option value="" disabled selected>Seleccione una sede...</option>
                        <?php foreach($sedes as $sede): ?>
                            <option value="<?= $sede['id'] ?>"><?= htmlspecialchars($sede['nombre']) ?> (<?= htmlspecialchars($sede['direccion']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none uppercase tracking-widest font-display transform hover:-translate-y-0.5 transition-all">
                        Finalizar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

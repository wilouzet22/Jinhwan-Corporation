<?php
$error = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'db_error') {
        $error = "Hubo un problema al procesar tu registro. Por favor, inténtalo de nuevo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>  
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro de Usuario - Jinhwan Corporation</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="<?= asset('styles/output.css') ?>" rel="stylesheet">
    
    <!-- Theme Init (prevent FOUC) -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS & Configs -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="<?= asset('js/styles/main-config.js') ?>"></script>
    <link href="<?= asset('styles/custom.css') ?>" rel="stylesheet">
</head>
<body class="bg-slate-100 dark:bg-[#0b0f19] min-h-screen font-body flex items-center justify-center relative overflow-hidden selection:bg-tkd-red selection:text-white transition-colors duration-300">
    
    <!-- Background Image with Blur & Premium Glows -->
    <div class="absolute inset-0 z-0">
        <img src="<?= asset('img/slider.png') ?>" class="w-full h-full object-cover filter blur-[6px] scale-105 opacity-10 dark:opacity-20" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-100 dark:from-[#0b0f19] via-slate-100/90 dark:via-[#0b0f19]/90 to-slate-200/80 dark:to-[#111827]/80"></div>
        <!-- Ambient Light Gradients -->
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-tkd-red/5 dark:bg-tkd-red/10 blur-[120px] pointer-events-none animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-tkd-blue/5 dark:bg-tkd-blue/10 blur-[120px] pointer-events-none animate-pulse-slow" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Back to Site Button -->
    <a href="<?= base_url('/') ?>" class="absolute top-6 left-6 z-20 flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white dark:hover:bg-slate-800/80 transition-all backdrop-blur-sm group shadow-sm">
        <span class="material-icons-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
        <span class="text-xs font-semibold uppercase tracking-wider">Volver</span>
    </a>

    <!-- Register Container -->
    <div class="w-full max-w-2xl m-4 bg-white dark:bg-slate-900/80 backdrop-blur-md rounded-3xl shadow-xl dark:shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-slate-200 dark:border-slate-800/80 animate-fade-in-up relative z-10 overflow-hidden transition-colors duration-300">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-tkd-red via-tkd-gold to-tkd-blue"></div>

        <div class="p-6 md:p-8">
            <div class="text-center mb-6">
                <div class="inline-block mb-3 transition-transform duration-500 hover:scale-105 animate-float">
                    <img src="<?= asset('img/visual/logo.svg') ?>" alt="Jinhwan" class="h-16 w-auto object-contain drop-shadow-[0_0_15px_rgba(220,38,38,0.2)]">
                </div>
                <h2 class="text-2xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider transition-colors">Solicitud de Registro</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-xs italic transition-colors">Cree su cuenta para unirse a nosotros</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-300 dark:border-red-500/40 text-center animate-shake transition-colors">
                    <p class="text-sm text-red-700 dark:text-red-400 font-medium flex items-center justify-center gap-2">
                         <span class="material-icons-outlined">error_outline</span>
                         <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4" method="POST" action="<?= base_url('/registro/process') ?>">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Nombre(s)</label>
                    <input name="nombre" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Apellido(s)</label>
                    <input name="apellido" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Documento Identidad</label>
                    <input name="num_doc" type="text" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Fecha Nacimiento</label>
                    <input name="fecha_n" type="date" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300 select-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Correo Electrónico</label>
                    <input name="email" type="email" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Teléfono</label>
                    <input name="telefono" type="tel" required 
                        class="block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2 transition-colors">Contraseña (Mín. 6 carc.)</label>
                    <div class="relative group">
                        <input id="password" name="password" type="password" required minlength="6" 
                            class="pr-12 block w-full py-3 px-4 rounded-xl border-slate-300 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue/50 transition-all duration-300">
                        <button type="button" onclick="togglePassword('password', 'toggleIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-tkd-blue transition-colors focus:outline-none">
                            <span id="toggleIcon" class="material-icons-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2 flex items-start mt-2">
                    <div class="flex items-center h-5">
                        <input id="terminos" name="terminos" type="checkbox" required
                            class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 text-tkd-blue focus:ring-tkd-blue focus:ring-offset-white dark:focus:ring-offset-slate-900 transition-colors">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terminos" class="font-medium text-slate-600 dark:text-slate-300 transition-colors">
                            Acepto los <button type="button" onclick="openModal()" class="text-tkd-blue hover:text-blue-600 dark:hover:text-blue-400 font-bold hover:underline transition-colors focus:outline-none">Términos y Condiciones</button>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2 pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-tkd-blue to-blue-700 hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tkd-blue transition-all transform hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 uppercase tracking-widest font-display">
                        Enviar Solicitud
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800/80 text-center transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">
                    ¿Ya tienes cuenta? <a href="<?= base_url('/login') ?>" class="text-tkd-blue hover:text-blue-500 font-bold hover:underline transition-colors ml-1">Inicia sesión</a>
                </p>
            </div>
        </div>

        <div class="bg-slate-50 dark:bg-slate-950/40 py-4 text-center text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest border-t border-slate-200 dark:border-slate-800/50 transition-colors">
            &copy; 2025 Jinhwan Corporation - Sistema de Gestión de Membresías
        </div>
    </div>

    <!-- Modal Terminos y Condiciones -->
    <div id="termsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-2xl w-full max-w-lg mx-4 transform scale-95 transition-transform duration-300 overflow-hidden transition-colors" id="modalContent">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/80 transition-colors">
                <h3 class="text-xl font-display font-bold text-slate-900 dark:text-white uppercase transition-colors">Términos y Condiciones</h3>
                <button type="button" onclick="closeModal()" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors focus:outline-none">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="p-6 text-slate-600 dark:text-slate-300 text-sm max-h-[60vh] overflow-y-auto space-y-4 transition-colors">
                <p>Bienvenido al Sistema de Gestión de Jinhwan Corporation. Al acceder o usar nuestra plataforma, usted acepta estar sujeto a los siguientes términos y condiciones.</p>
                <h4 class="text-slate-800 dark:text-white font-semibold mt-4 transition-colors">1. Uso de la cuenta</h4>
                <p>Su cuenta es personal e intransferible. Usted es responsable de mantener la confidencialidad de sus credenciales de acceso y de todas las actividades que ocurran bajo su cuenta.</p>
                <h4 class="text-slate-800 dark:text-white font-semibold mt-4 transition-colors">2. Privacidad y Datos</h4>
                <p>Nos comprometemos a proteger sus datos personales. Su información será utilizada estrictamente para los fines administrativos de la corporación y no será compartida con terceros sin su consentimiento previo.</p>
                <h4 class="text-slate-800 dark:text-white font-semibold mt-4 transition-colors">3. Conducta del Usuario</h4>
                <p>Se espera que todos los usuarios interactúen con el sistema de manera responsable y ética. Cualquier uso malintencionado, intento de vulneración de seguridad o abuso resultará en la suspensión inmediata de la cuenta.</p>
            </div>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/80 flex justify-end transition-colors">
                <button type="button" onclick="closeModal()" class="px-6 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-white font-semibold rounded-lg transition-colors focus:outline-none">
                    Aceptar y Cerrar
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }
    </style>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        const modal = document.getElementById('termsModal');
        const modalContent = document.getElementById('modalContent');

        function openModal() {
            modal.classList.remove('hidden');
            // Timeout to allow the element to render before adding opacity/scale for transition
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            // Wait for transition to end
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>

<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero Section -->
<section class="relative py-20 bg-tkd-black overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-tkd-black via-tkd-black/90 to-tkd-blue/20"></div>
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#444 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold text-white mb-4 uppercase tracking-tight">
            Nuestros <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red to-tkd-gold">Maestros</span>
        </h1>
        <div class="w-24 h-1.5 bg-gradient-to-r from-tkd-blue via-tkd-red to-tkd-gold mx-auto rounded-full mb-6"></div>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light">
            Guiando tu camino con experiencia, disciplina y pasión por el Taekwondo.
        </p>
    </div>
</section>

<!-- Instructors Grid -->
<section class="py-20 bg-tkd-gray dark:bg-tkd-black relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Instructor 1: Nelson Restrepo Montaña -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-tkd-red">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-tkd-black/80 to-transparent z-10"></div>
                    <img src="/jinwha/public/img/intructores/nelson-edited-1024x1024.jpg" alt="Nelson Restrepo Montaña" class="w-full h-full object-cover object-top transform group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-1">Nelson Restrepo Montaña</h3>
                        <span class="inline-block px-3 py-1 bg-tkd-red text-white text-xs font-bold uppercase tracking-wider rounded">Director General</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Grado</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Cinturón Negro 2º Dan (Kukkiwon) <br> Cinturón Negro 6º Dan (Fed. Colombiana)</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Logros</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Campeón selección Antioquia</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Experiencia</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Más de 45 años practicando y 40 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Especialista en entrenamiento de combate, defensa personal y entrenamiento personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 2: Ana Patricia Giraldo Gómez -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-tkd-blue">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-tkd-black/80 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/ana-edited-768x768.jpeg') ?>" alt="Master Instructor" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-1">Ana Patricia Giraldo</h3>
                        <span class="inline-block px-3 py-1 bg-tkd-blue text-white text-xs font-bold uppercase tracking-wider rounded">Instructora Senior</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Grado</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Cinturón Negro 4º Dan (Federado y Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Logros</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Selección Antioquía y Colombia (Poomsae). Multimedallista nacional.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Experiencia</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Más de 20 años practicando y 15 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Especialista en Poomsaes y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 3: Nilton Felipe Alvarez Cano -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-tkd-gold">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-tkd-black/80 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/nilton-edited-768x768.jpeg') ?>" alt="Nilton Felipe Alvarez Cano" class="w-full h-full object-cover object-top transform group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-1">Nilton Felipe Alvarez</h3>
                        <span class="inline-block px-3 py-1 bg-tkd-gold text-white text-xs font-bold uppercase tracking-wider rounded">Instructor Senior</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Grado</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Cinturón Negro 4º Dan (Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Logros</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Selección Antioquía</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Experiencia</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Más de 20 años practicando y 15 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Especialista en Entrenamiento de Combate y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 4: Fran Posada Arango -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-slate-600">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-tkd-black/80 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/fran-edited-768x768.jpeg') ?>" alt="Fran Posada Arango" class="w-full h-full object-cover object-top transform group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-1">Fran Posada Arango</h3>
                        <span class="inline-block px-3 py-1 bg-slate-600 text-white text-xs font-bold uppercase tracking-wider rounded">Instructor</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Grado</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Cinturón Negro 2º Dan (Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Logros</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Selección Antioquía</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Experiencia</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Más de 10 años practicando y 5 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Especialista en Entrenamiento de Combate y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 5: Cristian Damian Hincapié Vasquez -->
            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-slate-600">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-tkd-black/80 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/cristian-edited-768x768.jpeg') ?>" alt="Cristian Damian Hincapié Vasquez" class="w-full h-full object-cover object-top transform group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-1">Cristian Hincapié</h3>
                        <span class="inline-block px-3 py-1 bg-slate-600 text-white text-xs font-bold uppercase tracking-wider rounded">Instructor</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Grado</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Cinturón Negro 1º Dan</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Logros</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Competidor de Taekwondo</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-tkd-blue uppercase tracking-wide mb-1">Experiencia</h4>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">Más de 10 años practicando y 5 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Competidor de alto rendimiento en combate.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

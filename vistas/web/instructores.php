<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero Section -->
<section class="relative py-20 bg-[#0b0f19] overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0b0f19] via-[#0b0f19]/90 to-tkd-blue/15"></div>
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold text-white mb-4 uppercase tracking-tight">
            Nuestros <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red to-tkd-gold">Maestros</span>
        </h1>
        <div class="w-24 h-1.5 bg-gradient-to-r from-tkd-blue via-tkd-red to-tkd-gold mx-auto rounded-full mb-6 shadow-[0_0_10px_rgba(220,38,38,0.5)]"></div>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light">
            Guiando tu camino con experiencia, disciplina y pasión por el Taekwondo.
        </p>
    </div>
</section>

<!-- Instructors Grid -->
<section class="py-20 bg-[#0b0f19] relative overflow-hidden">
    <!-- Abstract Ambient Glows -->
    <div class="absolute top-1/3 right-1/10 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/3 left-1/10 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Instructor 1: Nelson Restrepo Montaña -->
            <div class="group glass-card rounded-2xl overflow-hidden border-t-2 border-t-tkd-red/40">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-[#0b0f19]/40 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/nelson-edited-1024x1024.jpg') ?>" alt="Nelson Restrepo Montaña" class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-750">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-2">Nelson Restrepo Montaña</h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-tkd-red/10 border border-tkd-red/30 text-tkd-red backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-tkd-red animate-pulse"></span>
                            Director General
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Grado</h4>
                        <p class="text-slate-300 text-sm font-light leading-relaxed">Cinturón Negro 2º Dan (Kukkiwon) <br> Cinturón Negro 6º Dan (Fed. Colombiana)</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Logros</h4>
                        <p class="text-slate-300 text-sm font-light">Campeón selección Antioquia</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Experiencia</h4>
                        <p class="text-slate-300 text-sm font-light">Más de 45 años practicando y 40 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80">
                        <p class="text-xs text-slate-500 italic font-light">Especialista en entrenamiento de combate, defensa personal y entrenamiento personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 2: Ana Patricia Giraldo Gómez -->
            <div class="group glass-card rounded-2xl overflow-hidden border-t-2 border-t-tkd-blue/40">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-[#0b0f19]/40 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/ana-edited-768x768.jpeg') ?>" alt="Master Instructor" class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-750">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-2">Ana Patricia Giraldo</h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-tkd-blue/10 border border-tkd-blue/30 text-tkd-blue backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-tkd-blue animate-pulse"></span>
                            Instructora Senior
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Grado</h4>
                        <p class="text-slate-300 text-sm font-light">Cinturón Negro 4º Dan (Federado y Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Logros</h4>
                        <p class="text-slate-300 text-sm font-light">Selección Antioquía y Colombia (Poomsae). Multimedallista nacional.</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Experiencia</h4>
                        <p class="text-slate-300 text-sm font-light">Más de 20 años practicando y 15 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80">
                        <p class="text-xs text-slate-500 italic font-light">Especialista en Poomsaes y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 3: Nilton Felipe Alvarez Cano -->
            <div class="group glass-card rounded-2xl overflow-hidden border-t-2 border-t-tkd-gold/40">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-[#0b0f19]/40 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/nilton-edited-768x768.jpeg') ?>" alt="Nilton Felipe Alvarez Cano" class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-750">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-2">Nilton Felipe Alvarez</h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-tkd-gold/10 border border-tkd-gold/30 text-tkd-gold backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-tkd-gold animate-pulse"></span>
                            Instructor Senior
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Grado</h4>
                        <p class="text-slate-300 text-sm font-light">Cinturón Negro 4º Dan (Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Logros</h4>
                        <p class="text-slate-300 text-sm font-light">Selección Antioquía</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Experiencia</h4>
                        <p class="text-slate-300 text-sm font-light">Más de 20 años practicando y 15 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80">
                        <p class="text-xs text-slate-500 italic font-light">Especialista en Entrenamiento de Combate y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 4: Fran Posada Arango -->
            <div class="group glass-card rounded-2xl overflow-hidden border-t-2 border-t-slate-700">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-[#0b0f19]/40 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/fran-edited-768x768.jpeg') ?>" alt="Fran Posada Arango" class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-750">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-2">Fran Posada Arango</h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-800/85 border border-slate-700 text-slate-300 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-pulse"></span>
                            Instructor
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Grado</h4>
                        <p class="text-slate-300 text-sm font-light">Cinturón Negro 2º Dan (Kukkiwon)</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Logros</h4>
                        <p class="text-slate-300 text-sm font-light">Selección Antioquía</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Experiencia</h4>
                        <p class="text-slate-300 text-sm font-light">Más de 10 años practicando y 5 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80">
                        <p class="text-xs text-slate-500 italic font-light">Especialista en Entrenamiento de Combate y defensa personal.</p>
                    </div>
                </div>
            </div>

            <!-- Instructor 5: Cristian Damian Hincapié Vasquez -->
            <div class="group glass-card rounded-2xl overflow-hidden border-t-2 border-t-slate-700">
                <div class="relative h-80 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-[#0b0f19]/40 to-transparent z-10"></div>
                    <img src="<?= asset('img/intructores/cristian-edited-768x768.jpeg') ?>" alt="Cristian Damian Hincapié Vasquez" class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-750">
                    <div class="absolute bottom-0 left-0 p-6 z-20">
                        <h3 class="text-2xl font-display font-bold text-white uppercase mb-2">Cristian Hincapié</h3>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-800/85 border border-slate-700 text-slate-300 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-pulse"></span>
                            Instructor
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Grado</h4>
                        <p class="text-slate-300 text-sm font-light">Cinturón Negro 1º Dan</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Logros</h4>
                        <p class="text-slate-300 text-sm font-light">Competidor de Taekwondo</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-tkd-blue uppercase tracking-wider mb-1">Experiencia</h4>
                        <p class="text-slate-300 text-sm font-light">Más de 10 años practicando y 5 años como instructor.</p>
                    </div>
                    <div class="pt-4 border-t border-slate-800/80">
                        <p class="text-xs text-slate-500 italic font-light">Competidor de alto rendimiento en combate.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

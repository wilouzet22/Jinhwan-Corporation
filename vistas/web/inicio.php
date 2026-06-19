<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero Section -->
<section class="relative w-full h-[85vh] overflow-hidden bg-slate-100 dark:bg-[#0b0f19] transition-colors duration-300">
    <!-- Slider Container -->
    <div id="hero-slider" class="absolute inset-0">
        <!-- Slide 1 -->
        <div class="slider-item active h-full relative">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-100 dark:from-[#0b0f19] via-slate-100/70 dark:via-[#0b0f19]/70 to-transparent z-10"></div>
            <img alt="Taekwondo Training" class="w-full h-full object-cover opacity-45" src="<?= asset('img/slider.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-tkd-red/10 border border-tkd-red/35 text-tkd-red font-bold text-xs uppercase tracking-widest mb-6 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-tkd-red animate-pulse"></span>
                        Excelencia Marcial
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-slate-900 dark:text-white mb-6 leading-tight tracking-tight uppercase">
                        IMPULSANDO EL <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-blue via-blue-400 to-blue-700">FUTURO</span> JUNTOS
                    </h1>
                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-2xl font-light border-l-2 border-tkd-blue/50 pl-6 leading-relaxed">
                        Nuestra organización se dedica a la excelencia, la evolución y la formación de carácter a través del Taekwondo.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?= base_url('/sedes') ?>" class="px-8 py-4 bg-tkd-red text-white font-display font-bold uppercase tracking-wider rounded-lg transition-all hover:bg-red-700 hover:shadow-lg transform hover:-translate-y-0.5">
                            Encuentra tu Sede
                        </a>
                        <a href="<?= base_url('/ascensos') ?>" class="px-8 py-4 border border-slate-400 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-slate-600 dark:hover:border-slate-500 font-display font-bold uppercase tracking-wider rounded-lg transition-all transform hover:-translate-y-0.5 bg-white/50 dark:bg-slate-900/35 backdrop-blur">
                            Ver Programas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-100 dark:from-[#0b0f19] via-slate-100/70 dark:via-[#0b0f19]/70 to-transparent z-10"></div>
            <img alt="Dojang Interior" class="w-full h-full object-cover opacity-45" src="<?= asset('img/slider2.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-tkd-blue/10 border border-tkd-blue/35 text-tkd-blue font-bold text-xs uppercase tracking-widest mb-6 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-tkd-blue animate-pulse"></span>
                        Disciplina y Honor
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-slate-900 dark:text-white mb-6 leading-tight tracking-tight uppercase">
                        FORJANDO <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-gold via-amber-400 to-amber-600">CAMPEONES</span>
                    </h1>
                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-2xl font-light border-l-2 border-tkd-gold/50 pl-6 leading-relaxed">
                        Instalaciones de primer nivel y maestros certificados para guiar tu camino marcial.
                    </p>
                    <a href="<?= base_url('/sedes') ?>" class="px-8 py-4 bg-tkd-blue text-white font-display font-bold uppercase tracking-wider rounded-lg transition-all hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-0.5">
                        Conoce Más
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-100 dark:from-[#0b0f19] via-slate-100/70 dark:via-[#0b0f19]/70 to-transparent z-10"></div>
            <img alt="Taekwondo Action" class="w-full h-full object-cover opacity-45" src="<?= asset('img/slider3.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-tkd-gold/10 border border-tkd-gold/35 text-tkd-gold font-bold text-xs uppercase tracking-widest mb-6 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-tkd-gold animate-pulse"></span>
                        Espíritu Indomable
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-slate-900 dark:text-white mb-6 leading-tight tracking-tight uppercase">
                        SUPERA TUS <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red via-red-400 to-red-700">LÍMITES</span>
                    </h1>
                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-2xl font-light border-l-2 border-tkd-red/50 pl-6 leading-relaxed">
                        El Taekwondo no es solo un deporte, es un estilo de vida que fortalece cuerpo y mente.
                    </p>
                    <a href="<?= base_url('/ascensos') ?>" class="px-8 py-4 bg-tkd-gold text-white font-display font-bold uppercase tracking-wider rounded-lg transition-all hover:bg-amber-600 hover:shadow-lg transform hover:-translate-y-0.5">
                        Únete Hoy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Controls -->
    <div class="absolute bottom-10 right-10 z-30 flex space-x-4">
        <button id="prev-btn" class="w-12 h-12 rounded-full bg-white/60 dark:bg-slate-900/60 border border-slate-300 dark:border-slate-800 text-slate-700 dark:text-white flex items-center justify-center hover:bg-tkd-red hover:text-white hover:border-tkd-red transition-all shadow-lg backdrop-blur">
            <span class="material-icons-outlined">chevron_left</span>
        </button>
        <button id="next-btn" class="w-12 h-12 rounded-full bg-white/60 dark:bg-slate-900/60 border border-slate-300 dark:border-slate-800 text-slate-700 dark:text-white flex items-center justify-center hover:bg-tkd-red hover:text-white hover:border-tkd-red transition-all shadow-lg backdrop-blur">
            <span class="material-icons-outlined">chevron_right</span>
        </button>
    </div>
    
    <!-- Decorative Bottom Shape -->
    <div class="absolute bottom-0 left-0 w-full h-24 bg-slate-100 dark:bg-[#0b0f19] z-20 transition-colors duration-300" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
</section>

<!-- Features Section -->
<section class="py-24 bg-slate-100 dark:bg-[#0b0f19] relative overflow-hidden transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wider transition-colors">
                Nuestros <span class="text-tkd-red">Pilares</span>
            </h2>
            <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-8 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-red group-hover:bg-tkd-red group-hover:text-white group-hover:border-tkd-red transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">fitness_center</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Entrenamiento Físico</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Desarrolla fuerza, flexibilidad y resistencia con nuestros programas diseñados para todos los niveles.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 border-t-2 border-t-tkd-blue/50 p-8 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-blue group-hover:bg-tkd-blue group-hover:text-white group-hover:border-tkd-blue transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">psychology</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Disciplina Mental</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Fortalece tu mente, mejora tu concentración y cultiva el autocontrol a través de la práctica marcial.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-8 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-gold group-hover:bg-tkd-gold group-hover:text-white group-hover:border-tkd-gold transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">groups</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Comunidad</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Únete a una familia unida por el respeto, la colaboración y el deseo mutuo de superación.
                </p>
            </div>
        </div>
    </div>
</section>

<script src="<?= asset('js/modules/index-slider.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

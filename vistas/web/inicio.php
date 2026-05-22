<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero Section -->
<section class="relative w-full h-[85vh] overflow-hidden bg-tkd-black">
    <!-- Slider Container -->
    <div id="hero-slider" class="absolute inset-0">
        <!-- Slide 1 -->
        <div class="slider-item active h-full relative">
            <div class="absolute inset-0 bg-gradient-to-r from-tkd-black/90 via-tkd-black/50 to-transparent z-10"></div>
            <img alt="Taekwondo Training" class="w-full h-full object-cover opacity-60" src="<?= asset('img/slider.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-block px-4 py-2 bg-tkd-red text-white font-bold uppercase tracking-widest mb-4 transform -skew-x-12">
                        <span class="block transform skew-x-12">Excelencia Marcial</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight">
                        IMPULSANDO EL <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-blue to-white">FUTURO</span> JUNTOS
                    </h1>
                    <p class="text-xl text-slate-300 mb-8 max-w-2xl font-light border-l-4 border-tkd-blue pl-6">
                        Nuestra organización se dedica a la excelencia, la evolución y la formación de carácter a través del Taekwondo.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?= base_url('/sedes') ?>" class="px-8 py-4 bg-tkd-red hover:bg-red-700 text-white font-display font-bold uppercase tracking-wider rounded transition-all transform hover:-translate-y-1 shadow-lg shadow-red-900/20">
                            Encuentra tu Sede
                        </a>
                        <a href="<?= base_url('/ascensos') ?>" class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-tkd-black font-display font-bold uppercase tracking-wider rounded transition-all transform hover:-translate-y-1">
                            Ver Programas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-tkd-black/90 via-tkd-black/50 to-transparent z-10"></div>
            <img alt="Dojang Interior" class="w-full h-full object-cover opacity-60" src="<?= asset('img/slider2.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-block px-4 py-2 bg-tkd-blue text-white font-bold uppercase tracking-widest mb-4 transform -skew-x-12">
                        <span class="block transform skew-x-12">Disciplina y Honor</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight">
                        FORJANDO <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-gold to-white">CAMPEONES</span>
                    </h1>
                    <p class="text-xl text-slate-300 mb-8 max-w-2xl font-light border-l-4 border-tkd-gold pl-6">
                        Instalaciones de primer nivel y maestros certificados para guiar tu camino marcial.
                    </p>
                    <a href="<?= base_url('/sedes') ?>" class="px-8 py-4 bg-tkd-blue hover:bg-blue-700 text-white font-display font-bold uppercase tracking-wider rounded transition-all transform hover:-translate-y-1 shadow-lg shadow-blue-900/20">
                        Conoce Más
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-tkd-black/90 via-tkd-black/50 to-transparent z-10"></div>
            <img alt="Taekwondo Action" class="w-full h-full object-cover opacity-60" src="<?= asset('img/slider3.png') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl animate-fade-in-up">
                    <div class="inline-block px-4 py-2 bg-tkd-gold text-white font-bold uppercase tracking-widest mb-4 transform -skew-x-12">
                        <span class="block transform skew-x-12">Espíritu Indomable</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight">
                        SUPERA TUS <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red to-white">LÍMITES</span>
                    </h1>
                    <p class="text-xl text-slate-300 mb-8 max-w-2xl font-light border-l-4 border-tkd-red pl-6">
                        El Taekwondo no es solo un deporte, es un estilo de vida que fortalece cuerpo y mente.
                    </p>
                    <a href="<?= base_url('/ascensos') ?>" class="px-8 py-4 bg-tkd-gold hover:bg-amber-600 text-white font-display font-bold uppercase tracking-wider rounded transition-all transform hover:-translate-y-1 shadow-lg shadow-amber-900/20">
                        Únete Hoy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider Controls -->
    <div class="absolute bottom-10 right-10 z-30 flex space-x-4">
        <button id="prev-btn" class="w-12 h-12 rounded-full bg-tkd-red text-white flex items-center justify-center hover:bg-red-700 transition-all shadow-lg shadow-red-900/50">
            <span class="material-icons-outlined">chevron_left</span>
        </button>
        <button id="next-btn" class="w-12 h-12 rounded-full bg-tkd-red text-white flex items-center justify-center hover:bg-red-700 transition-all shadow-lg shadow-red-900/50">
            <span class="material-icons-outlined">chevron_right</span>
        </button>
    </div>
    
    <!-- Decorative Bottom Shape -->
    <div class="absolute bottom-0 left-0 w-full h-24 bg-tkd-gray dark:bg-tkd-black z-20" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
</section>

<!-- Features Section -->
<section class="py-20 bg-tkd-gray dark:bg-tkd-black relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase">
                Nuestros <span class="text-tkd-red">Pilares</span>
            </h2>
            <div class="w-24 h-1 bg-tkd-blue mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tkd-red transition-colors duration-300">
                    <span class="material-icons-outlined text-3xl text-tkd-red group-hover:text-white">fitness_center</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-3">Entrenamiento Físico</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                    Desarrolla fuerza, flexibilidad y resistencia con nuestros programas diseñados para todos los niveles.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition-all duration-300 group border-t-4 border-tkd-blue">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tkd-blue transition-colors duration-300">
                    <span class="material-icons-outlined text-3xl text-tkd-blue group-hover:text-white">psychology</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-3">Disciplina Mental</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                    Fortalece tu mente, mejora tu concentración y cultiva el autocontrol a través de la práctica marcial.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="glass-card p-8 rounded-2xl hover:transform hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tkd-gold transition-colors duration-300">
                    <span class="material-icons-outlined text-3xl text-tkd-gold group-hover:text-white">groups</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-3">Comunidad</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                    Únete a una familia unida por el respeto, la colaboración y el deseo mutuo de superación.
                </p>
            </div>
        </div>
    </div>
</section>

<script src="<?= asset('js/modules/index-slider.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="relative w-full h-[75vh] sm:h-[85vh] overflow-hidden dark:bg-[#0b0f19] transition-colors duration-300">
    
    <div class="absolute inset-0 dark:hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%)"></div>
    
    <div id="hero-slider" class="absolute inset-0">
        
        <div class="slider-item active h-full relative">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent z-10"></div>
            <img alt="Taekwondo Training" class="w-full h-full object-cover opacity-80 dark:opacity-45" src="<?= asset('img/slider1.jpg') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-600/20 border border-rose-500/40 text-rose-400 font-bold text-xs uppercase tracking-widest mb-6 rounded-full backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        Excelencia Marcial
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight tracking-tight uppercase drop-shadow-md">
                        IMPULSANDO EL <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-500">FUTURO</span> JUNTOS
                    </h1>
                    <p class="text-lg md:text-xl text-slate-200 mb-8 max-w-2xl font-light border-l-2 border-rose-500/80 pl-6 leading-relaxed">
                        Nuestra organización se dedica a la excelencia, la evolución y la formación de carácter a través del Taekwondo.
                    </p>
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                        <a href="<?= base_url('/grupos') ?>" class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-display font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg hover:shadow-rose-600/30 transform hover:-translate-y-0.5">
                            Nuestros Grupos
                        </a>
                        <a href="<?= base_url('/nosotros') ?>" class="px-8 py-4 border border-white/30 text-white hover:bg-white/10 font-display font-bold uppercase tracking-wider rounded-xl transition-all backdrop-blur-md">
                            Conoce Más
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent z-10"></div>
            <img alt="Dojang Interior" class="w-full h-full object-cover opacity-80 dark:opacity-45" src="<?= asset('img/slider2.jpg') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-600/20 border border-blue-500/40 text-blue-400 font-bold text-xs uppercase tracking-widest mb-6 rounded-full backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        Disciplina y Honor
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight tracking-tight uppercase drop-shadow-md">
                        FORJANDO <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500">CAMPEONES</span>
                    </h1>
                    <p class="text-lg md:text-xl text-slate-200 mb-8 max-w-2xl font-light border-l-2 border-amber-500/80 pl-6 leading-relaxed">
                        Instalaciones de primer nivel y maestros certificados para guiar tu camino marcial.
                    </p>
                    <a href="<?= base_url('/grupos') ?>" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-display font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg hover:shadow-blue-600/30 transform hover:-translate-y-0.5">
                        Conoce Nuestros Grupos
                    </a>
                </div>
            </div>
        </div>

        <div class="slider-item h-full relative hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent z-10"></div>
            <img alt="Taekwondo Action" class="w-full h-full object-cover opacity-80 dark:opacity-45" src="<?= asset('img/slider3.jpg') ?>"/>
            <div class="absolute inset-0 z-20 flex items-center container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-600/20 border border-amber-500/40 text-amber-400 font-bold text-xs uppercase tracking-widest mb-6 rounded-full backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Espíritu Indomable
                    </div>
                    <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6 leading-tight tracking-tight uppercase drop-shadow-md">
                        SUPERA TUS <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 via-red-400 to-rose-600">LÍMITES</span>
                    </h1>
                    <p class="text-lg md:text-xl text-slate-200 mb-8 max-w-2xl font-light border-l-2 border-rose-500/80 pl-6 leading-relaxed">
                        El Taekwondo no es solo un deporte, es un estilo de vida que fortalece cuerpo y mente.
                    </p>
                    <a href="<?= base_url('/registro') ?>" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-slate-950 font-display font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg hover:shadow-amber-500/30 transform hover:-translate-y-0.5">
                        Únete Hoy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-10 right-10 z-30 flex space-x-4">
        <button id="prev-btn" class="w-12 h-12 rounded-full bg-white/60 dark:bg-slate-900/60 border border-slate-300 dark:border-slate-800 text-slate-700 dark:text-white flex items-center justify-center hover:bg-tkd-red hover:text-white hover:border-tkd-red transition-all shadow-lg backdrop-blur">
            <span class="material-icons-outlined">chevron_left</span>
        </button>
        <button id="next-btn" class="w-12 h-12 rounded-full bg-white/60 dark:bg-slate-900/60 border border-slate-300 dark:border-slate-800 text-slate-700 dark:text-white flex items-center justify-center hover:bg-tkd-red hover:text-white hover:border-tkd-red transition-all shadow-lg backdrop-blur">
            <span class="material-icons-outlined">chevron_right</span>
        </button>
    </div>

    <div class="absolute bottom-0 left-0 w-full h-24 z-20 transition-colors duration-300" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);" id="hero-bottom-shape">
        <div class="absolute inset-0 dark:hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%)"></div>
        <div class="absolute inset-0 hidden dark:block bg-[#0b0f19]"></div>
    </div>
</section>

<section class="py-24 relative overflow-hidden transition-colors duration-300">
    <div class="dark:hidden absolute inset-0" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wider transition-colors">
                Nuestros <span class="text-tkd-red">Pilares</span>
            </h2>
            <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 border-l-4 border-l-tkd-red p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-red-50 dark:bg-slate-900/80 border border-red-100 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-red group-hover:bg-tkd-red group-hover:text-white group-hover:border-tkd-red transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">fitness_center</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Entrenamiento Físico</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Desarrolla fuerza, flexibilidad y resistencia con nuestros programas diseñados para todos los niveles.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 border-l-4 border-l-tkd-blue p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-blue-50 dark:bg-slate-900/80 border border-blue-100 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-blue group-hover:bg-tkd-blue group-hover:text-white group-hover:border-tkd-blue transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">psychology</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Disciplina Mental</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Fortalece tu mente, mejora tu concentración y cultiva el autocontrol a través de la práctica marcial.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 border-l-4 border-l-tkd-gold p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 group">
                <div class="w-16 h-16 bg-amber-50 dark:bg-slate-900/80 border border-amber-100 dark:border-slate-800 rounded-2xl flex items-center justify-center mb-6 text-tkd-gold group-hover:bg-tkd-gold group-hover:text-white group-hover:border-tkd-gold transition-all duration-300">
                    <span class="material-icons-outlined text-3xl">groups</span>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 dark:text-white mb-3 transition-colors">Comunidad</h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-light transition-colors">
                    Únete a una familia unida por el respeto, la colaboración y el deseo mutuo de superación.
                </p>
            </div>
        </div>

        <!-- Martial Arts Highlights & Stats Grid -->
        <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center shadow-sm hover-lift">
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-rose-500 font-display mb-1">+15</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Años de Tradición</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center shadow-sm hover-lift">
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 font-display mb-1">3</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sedes Principales</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center shadow-sm hover-lift">
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-400 font-display mb-1">+200</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Cinturones Negros</div>
            </div>
            <div class="bg-white dark:bg-slate-900/90 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center shadow-sm hover-lift">
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400 font-display mb-1">100%</div>
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Maestros Certificados</div>
            </div>
        </div>
    </div>
</section>

<script src="<?= asset('js/modules/index-slider.js') ?>" defer></script>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

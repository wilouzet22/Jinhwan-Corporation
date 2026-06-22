<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<!-- Hero / Intro Section -->
<section class="relative py-24 overflow-hidden transition-colors duration-300" style="background: linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(135deg,#eff6ff 0%,#fafafa 50%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="absolute inset-0">
        <img src="<?= asset('img/visual/alumnos-1024x768.jpeg') ?>" alt="Grupo Jinwhan" class="w-full h-full object-cover opacity-20 dark:opacity-25 blur-sm">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-100/80 dark:from-[#0b0f19]/80 via-slate-100/60 dark:via-[#0b0f19]/60 to-slate-100 dark:to-[#0b0f19]"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 bg-tkd-red/10 border border-tkd-red/35 text-tkd-red font-bold text-xs uppercase tracking-widest mb-4 rounded-full backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-tkd-red animate-pulse"></span>
            Desde 2003
        </span>
        <h1 class="text-5xl md:text-7xl font-display font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-tight transition-colors">
            Nuestra <span class="text-transparent bg-clip-text bg-gradient-to-r from-tkd-red to-tkd-gold">Misión</span>
        </h1>
        <div class="w-32 h-1.5 bg-gradient-to-r from-tkd-blue via-tkd-red to-tkd-gold mx-auto rounded-full mb-8"></div>
        <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-300 max-w-4xl mx-auto font-light leading-relaxed transition-colors">
            "Enfocando todos nuestros esfuerzos en poder pasar por la vida de las personas y dejar una pequeña transformación, que les permitan mejorar su propios proyectos de vida."
        </p>
    </div>
</section>

<!-- Main Infographic Content -->
<section class="py-16 relative overflow-hidden transition-colors duration-300" style="background: linear-gradient(180deg,#ffffff 0%,#eff6ff 50%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#ffffff 0%,#eff6ff 50%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-tkd-blue/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section 1: History & Mission (Image Right) -->
        <div class="flex flex-col lg:flex-row items-center gap-12 mb-24">
            <div class="lg:w-1/2 space-y-6">
                <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-tkd-blue text-white rounded-xl shadow-lg">
                        <span class="material-icons-outlined text-2xl">history_edu</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider transition-colors">
                        Propósito <span class="text-tkd-blue">Institucional</span>
                    </h2>
                </div>
                <div class="prose dark:prose-invert text-slate-600 dark:text-slate-300 leading-relaxed text-lg text-justify font-light transition-colors">
                    <p>
                        Desarrollando seres en su saber, en su capacidad de transformar su mundo desde el recognition de sus habilidades que convoque a la comunidad y los asociados a dar lo mejor de cada uno, en beneficio de todos sus miembros, mediante la formación de hombres y mujeres capaces, competitivos, de alta calidad humana, innovadores y creativos, para que pueda responder con efectividad a los retos cambiantes del mundo.
                    </p>
                    <p>
                        Buscando ser parte de la transformación de nuestra ciudad, nuestro país y aportar desde nuestro conocimiento, nuestro compromiso y motivación a la construcción de la una conciencia personal y social.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <div class="p-4 bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/80 rounded-xl border-l-4 border-l-tkd-gold transition-colors">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1 transition-colors">Calidad Humana</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 transition-colors">Formación de hombres y mujeres capaces</p>
                    </div>
                    <div class="p-4 bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/80 rounded-xl border-l-4 border-l-tkd-red transition-colors">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1 transition-colors">Conciencia Social</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 transition-colors">Aporte a la ciudad y al país</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative group">
                <div class="absolute inset-0 bg-tkd-blue/10 rounded-2xl transform rotate-3 transition-transform group-hover:rotate-6"></div>
                <div class="absolute inset-0 bg-tkd-red/10 rounded-2xl transform -rotate-3 transition-transform group-hover:-rotate-6"></div>
                <img src="<?= asset('img/visual/alumnos-1024x768.jpeg') ?>" alt="Historia del Taekwondo" class="rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl w-full h-auto object-cover transform hover:scale-[1.02] transition-transform duration-500 transition-colors">
            </div>
        </div>

        <!-- Section 2: Participation (Full Width Cards) -->
        <div class="mb-24">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 transition-colors">
                    Participación del Club <span class="text-tkd-red">Jinhwa</span>
                </h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto font-light transition-colors">
                    Hoy podemos mostrar resultados deportivos con alumnos en diversos niveles de competencia.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-xl border-t-2 border-t-tkd-blue/50 group shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full flex items-center justify-center mb-4 text-tkd-blue group-hover:bg-tkd-blue group-hover:text-white transition-all duration-300">
                        <span class="material-icons-outlined">location_city</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 transition-colors">Nivel Local</h3>
                    <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 font-light transition-colors">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-blue rounded-full"></span>Selección Medellín</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-blue rounded-full"></span>Campeones juegos ciudad de Medellín</li>
                    </ul>
                </div>
                <!-- Card 2 -->
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-xl border-t-2 border-t-tkd-red/50 group shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full flex items-center justify-center mb-4 text-tkd-red group-hover:bg-tkd-red group-hover:text-white transition-all duration-300">
                        <span class="material-icons-outlined">map</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 transition-colors">Nivel Regional</h3>
                    <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 font-light transition-colors">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-red rounded-full"></span>Procesos de Selección Antioquia</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-red rounded-full"></span>Campeones departamentales</li>
                    </ul>
                </div>
                <!-- Card 3 -->
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-xl border-t-2 border-t-tkd-gold/50 group shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full flex items-center justify-center mb-4 text-tkd-gold group-hover:bg-tkd-gold group-hover:text-white transition-all duration-300">
                        <span class="material-icons-outlined">emoji_events</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 transition-colors">Nivel Nacional</h3>
                    <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 font-light transition-colors">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-gold rounded-full"></span>Campeones nacionales</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-tkd-gold rounded-full"></span>Campeones juegos universitarios</li>
                    </ul>
                </div>
                <!-- Card 4 -->
                <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-xl border-t-2 border-t-slate-300 dark:border-t-slate-700 group shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-full flex items-center justify-center mb-4 text-slate-500 dark:text-slate-300 group-hover:bg-slate-200 dark:group-hover:bg-slate-800 group-hover:text-slate-800 dark:group-hover:text-white transition-all duration-300">
                        <span class="material-icons-outlined">public</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 transition-colors">Nivel Internacional</h3>
                    <ul class="text-sm text-slate-500 dark:text-slate-400 space-y-2 font-light transition-colors">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 dark:bg-slate-505 rounded-full"></span>Campeonatos internacionales</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 3: Structure & Staff -->
        <div class="space-y-16">
            <!-- Header & Intro -->
            <div class="text-center max-w-4xl mx-auto">
                 <div class="flex items-center justify-center gap-4 mb-6">
                    <div class="p-3 bg-tkd-gold text-white rounded-lg shadow-lg">
                        <span class="material-icons-outlined text-2xl">groups</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-slate-900 dark:text-white uppercase tracking-wider transition-colors">
                        Estructura de <span class="text-tkd-gold">Funcionamiento</span>
                    </h2>
                </div>
                <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed font-light transition-colors">
                    La corporación ha venido construyendo con los participantes y algunos padres de familia fortalecer las relaciones deportivas, competitivas y académicas para gestionar procesos de becas que nos permitan dar continuidad a los alumnos en sus procesos de formación profesional.
                </p>
            </div>

            <!-- Identity Image -->
            <div class="nosotros-identity-img relative rounded-3xl overflow-hidden shadow-xl group max-w-6xl mx-auto h-[400px] md:h-[400px]">
                <img src="<?= asset('img/visual/uniforme-e1758154541512-1536x605.jpeg') ?>" alt="Uniforme Jinwhan" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19]/90 via-[#0b0f19]/25 to-transparent flex items-end p-8 md:p-12">
                    <div class="max-w-3xl">
                        <div class="inline-block px-3 py-1 bg-tkd-gold text-white text-xs font-bold uppercase tracking-widest rounded mb-3">Identidad</div>
                        <h3 class="text-white text-4xl md:text-5xl font-display font-bold mb-4">Orgullo y Disciplina</h3>
                        <p class="text-slate-200 text-lg md:text-xl font-light border-l-4 border-tkd-gold pl-4">Portar nuestro uniforme es llevar con honor los valores de nuestra institución.</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="max-w-5xl mx-auto relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 dark:before:via-slate-800 before:to-transparent pb-12 transition-colors">
                
                <!-- Items (Reusing existing content structure) -->
                <!-- Item 0: Personal Administrativo -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-12">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-slate-50 dark:border-[#0b0f19] bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-white shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors duration-300">
                        <span class="material-icons-outlined text-sm">business_center</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-2xl shadow-sm transition-colors">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-3 transition-colors">Personal Administrativo</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 font-light transition-colors">La organización proponente cuenta con una serie de profesionales en las diferentes áreas:</p>
                        <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2 font-light transition-colors">
                            <li class="flex items-start gap-2">
                                <span class="material-icons-outlined text-tkd-gold text-base mt-0.5">check_circle</span>
                                <span><strong>Director financiero:</strong> Contador público</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-icons-outlined text-tkd-gold text-base mt-0.5">check_circle</span>
                                <span><strong>Coordinador pedagógico:</strong> Sociólogo</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-icons-outlined text-tkd-gold text-base mt-0.5">check_circle</span>
                                <span><strong>Presidente y representante legal:</strong> Administrador de empresas énfasis en procesos sociales</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Item 1: Coordinador General -->
                 <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-12">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-slate-50 dark:border-[#0b0f19] bg-tkd-red text-white shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors duration-300">
                        <span class="material-icons-outlined text-sm">badge</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-2xl border-t-2 border-t-tkd-red/50 shadow-sm transition-colors">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white transition-colors">Coordinador General</h3>
                        <p class="text-sm text-tkd-red font-bold uppercase mb-3 tracking-wide">Maestro Nelson Restrepo Montaña</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed text-justify font-light transition-colors">
                            MAESTRO EN TAEKWONDO 6 DAN, inscrito a liga y deportista de alto rendimiento, con más de 40 años de experiencia. Formación en Gestión de desarrollo comunitario, Capacitación técnica de Taekwondo, arbitraje de combate, y principios de optimización de desempeño en combate olímpico.
                        </p>
                    </div>
                </div>

                <!-- Item 3: Docentes -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-12">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-slate-50 dark:border-[#0b0f19] bg-tkd-gold text-white shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors duration-300">
                        <span class="material-icons-outlined text-sm">sports_martial_arts</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-2xl border-t-2 border-t-tkd-gold/50 shadow-sm transition-colors">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-2 transition-colors">Docentes de Taekwondo</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light transition-colors">Practicantes de TAEKWONDO con más de 8 años de experiencia, inscritos a clubes deportivos y liga municipal, con capacidad de formación a niños y niñas, excelente trato, aplicación de técnicas de disciplina sin ser violentos. Con calidez humana.</p>
                    </div>
                </div>

                <!-- Item 4: Equipo de Apoyo -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-slate-50 dark:border-[#0b0f19] bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-white shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-colors duration-300">
                        <span class="material-icons-outlined text-sm">health_and_safety</span>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-6 rounded-2xl border-t-2 border-t-slate-300 dark:border-t-slate-700 shadow-sm transition-colors">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-4 transition-colors">Equipo de Apoyo</h3>
                        <div class="space-y-4">
                            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-3 rounded-lg transition-colors">
                                <h4 class="text-sm font-bold text-tkd-blue mb-1">Profesional Psicólogo</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-light transition-colors">Profesionales con énfasis en trabajo social y grupal, ética profesional. Una persona con actitud entusiasta y positiva.</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 p-3 rounded-lg transition-colors">
                                <h4 class="text-sm font-bold text-tkd-blue mb-1">Profesional Nutricionista</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-light transition-colors">Guía y apoya el plan de alimentación para que el deportista pueda sostener un programa de entrenamiento adecuado.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

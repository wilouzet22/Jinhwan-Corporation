    
    <footer class="bg-white dark:bg-[#060913] backdrop-blur-md dark:backdrop-blur-none text-slate-600 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800/80 relative overflow-hidden mt-auto transition-colors duration-300">
        <!-- Barra de acento con gradiente marcial -->
        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-transparent via-tkd-red via-tkd-blue to-transparent opacity-90"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 items-start">
                
                <!-- Columna 1: Identidad Corporativa -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="<?= asset('img/visual/logo.svg') ?>" alt="Logo Jinhwan" class="h-11 w-auto object-contain">
                        <div>
                            <span class="font-display font-black text-xl text-slate-900 dark:text-white uppercase tracking-wider block leading-none">Jinhwan</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-tkd-red dark:text-rose-500 font-mono">Corporation</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Academia de formación integral en Taekwondo Olímpico WT. Disciplina marcial, superación deportiva y educación en valores fundamentales.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Afiliados WT
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            Medellín, Col
                        </span>
                    </div>
                </div>

                <!-- Columna 2: Contacto -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider border-l-4 border-tkd-red pl-3 transition-colors">Contacto</h3>
                    <div class="space-y-3">
                        <a class="flex items-center gap-3 group text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-tkd-red dark:hover:text-rose-400 transition-colors" href="tel:+573246336836">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-tkd-red group-hover:bg-tkd-red group-hover:text-white transition-all duration-300 shrink-0 shadow-xs">
                                <span class="material-icons-outlined text-lg">phone</span>
                            </div>
                            <span>+57 324 633 68 36</span>
                        </a>
                        <a class="flex items-center gap-3 group text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-tkd-blue dark:hover:text-blue-400 transition-colors" href="mailto:jinhwancorporation@gmail.com">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-tkd-blue group-hover:bg-tkd-blue group-hover:text-white transition-all duration-300 shrink-0 shadow-xs">
                                <span class="material-icons-outlined text-lg">email</span>
                            </div>
                            <span class="truncate">jinhwancorporation@gmail.com</span>
                        </a>
                        <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 pt-1">
                            <span class="material-icons-outlined text-sm text-amber-500">schedule</span>
                            <span>Lunes a Sábado: 7:00 AM - 8:30 PM</span>
                        </div>
                    </div>
                </div>

                <!-- Columna 3: Ubicación -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider border-l-4 border-tkd-blue pl-3 transition-colors">Ubicación</h3>
                    <div class="flex items-start gap-3 group">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-tkd-gold group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shrink-0 shadow-xs mt-0.5">
                            <span class="material-icons-outlined text-lg">location_on</span>
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold text-sm text-slate-900 dark:text-white transition-colors">Sede Principal</p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 transition-colors leading-relaxed">Carrera 110 #62Ac-81</p>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Medellín, Antioquia, Colombia</p>
                            <div class="pt-1.5">
                                <a href="<?= base_url('/sedes') ?>" class="text-[11px] font-bold text-tkd-blue dark:text-blue-400 hover:underline inline-flex items-center gap-1">
                                    <span>Ver todas las sedes</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna 4: Síguenos -->
                <div class="space-y-4">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white uppercase tracking-wider border-l-4 border-tkd-gold pl-3 transition-colors">Síguenos</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Conéctate en redes para transmisiones y noticias oficiales.</p>
                    <div class="flex gap-2.5 flex-wrap">
                        <!-- Facebook -->
                        <a class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-[#1877F2] hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] hover:shadow-md hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1" href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <!-- Instagram -->
                        <a class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-[#E1306C] hover:bg-gradient-to-tr hover:from-amber-500 hover:via-pink-600 hover:to-purple-600 hover:text-white hover:border-pink-500 hover:shadow-md hover:shadow-pink-500/30 transition-all duration-300 transform hover:-translate-y-1" href="https://www.instagram.com/corporacion_jinhwan/" target="_blank" rel="noopener noreferrer" title="Instagram (@corporacion_jinhwan)">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <!-- TikTok -->
                        <a class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-800 dark:text-slate-100 hover:bg-black hover:text-white hover:border-cyan-400 hover:shadow-md hover:shadow-cyan-400/20 transition-all duration-300 transform hover:-translate-y-1" href="https://www.tiktok.com" target="_blank" rel="noopener noreferrer" title="TikTok">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg>
                        </a>
                        <!-- WhatsApp -->
                        <a class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-[#25D366] hover:bg-[#25D366] hover:text-white hover:border-[#25D366] hover:shadow-md hover:shadow-emerald-500/30 transition-all duration-300 transform hover:-translate-y-1" href="https://wa.me/573246336836" target="_blank" rel="noopener noreferrer" title="WhatsApp (+57 324 633 68 36)">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

            </div>
            
            <!-- Pie inferior y Derechos de autor -->
            <div class="mt-12 pt-6 border-t border-slate-200 dark:border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left transition-colors">
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                    &copy; <?= date('Y') ?> <strong class="text-slate-800 dark:text-slate-200">Jinhwan Corporation</strong>. Todos los derechos reservados.
                </p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                    Taekwondo Olímpico WT &bull; Disciplina, Pasión y Formación Deportiva.
                </p>
            </div>
        </div>
    </footer>
    
    </main> 
</div> 
</div> 


<script src="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.10.3/simple-lightbox.min.js" integrity="sha512-Xth9Av_2znR5vALi4x2D2ksM2sC0o962KzQEVeP1xlG6TqGCI2pLflf3+S2G2bL4K0+2A1Q7F/AuYp1z0ll2EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        if(document.querySelector('.gallery a')) {
            var lightbox = new SimpleLightbox('.gallery a', {
                overlay: true,
                nav: true,
                navText: ['&larr;', '&rarr;'],
                close: true,
                closeText: '×',
                animationSpeed: 250,
                fadeSpeed: 300,
            });
        }
    });
</script>
<script type="module" src="<?= asset('dist/assets/main.js') ?>"></script>
<script src="<?= asset('js/modules/navigation.js') ?>"></script>
</body>
</html>

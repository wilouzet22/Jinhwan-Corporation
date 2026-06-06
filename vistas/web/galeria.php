<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="py-20 bg-[#0b0f19] min-h-screen relative overflow-hidden">
    <!-- Abstract Ambient Glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-tkd-blue/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tkd-red/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-4 uppercase text-center tracking-wider">
            Galería
        </h1>
        <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full shadow-[0_0_10px_rgba(220,38,38,0.5)]"></div>

        <div class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-16">
            <?php foreach ($imagenes as $imagen): 
                 // Fix paths for web display
                 // $imagen is something like "img/galeria/foto.jpg" or "public/img/galeria/foto.jpg"
                 // Web path should be relative to server root e.g. using asset('img/...')
                 $imagenUrl = asset(str_replace('public/', '', $imagen));
            ?>
                 <a href="<?= htmlspecialchars($imagenUrl) ?>" class="overflow-hidden rounded-xl border border-slate-800/80 shadow-xl group transition-all duration-300 hover:border-tkd-red/40 hover:shadow-[0_0_20px_rgba(220,38,38,0.25)]">
                    <img src="<?= htmlspecialchars($imagenUrl) ?>" alt="Imagen de la galería" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500"/>
                 </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

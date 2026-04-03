<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="py-20 bg-tkd-gray dark:bg-tkd-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase text-center">
            Galería
        </h1>
        <div class="w-24 h-1 bg-tkd-blue mx-auto rounded-full"></div>

        <div class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-12">
            <?php foreach ($imagenes as $imagen): 
                 // Fix paths for web display
                 // $imagen is something like "img/galeria/foto.jpg" or "public/img/galeria/foto.jpg"
                 // Web path should be relative to server root e.g. /jinwha/img/...
                 $imagenUrl = asset(str_replace('public/', '', $imagen));
            ?>
                 <a href="<?= htmlspecialchars($imagenUrl) ?>" class="overflow-hidden rounded-lg shadow-lg group">
                    <img src="<?= htmlspecialchars($imagenUrl) ?>" alt="Imagen de la galería" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                 </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

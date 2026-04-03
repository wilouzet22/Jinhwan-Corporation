<?php include '../header.php'; ?>

<section class="py-20 bg-tkd-gray dark:bg-tkd-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase text-center">
            Galería
        </h1>
        <div class="w-24 h-1 bg-tkd-blue mx-auto rounded-full"></div>

        <div class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-12">
            <?php
            $imagenes = glob('../../img/galeria/*.jpg');
            foreach ($imagenes as $imagen) {
                // Prepend base path for absolute URL in browser
                $webPath = str_replace('../../', '/jinwha/', $imagen);
                echo '<a href="' . $webPath . '" class="overflow-hidden rounded-lg shadow-lg group">';
                echo '<img src="' . $webPath . '" alt="Imagen de la galería" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>';
                echo '</a>';
            }
            ?>
        </div>
    </div>
</section>

<?php include '../footer.php'; ?>

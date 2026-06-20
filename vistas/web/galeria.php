<?php include __DIR__ . '/../layout/sitio_cabecera.php'; ?>

<section class="py-20 relative min-h-screen overflow-hidden transition-colors duration-300" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)">
    <div class="dark:hidden absolute inset-0" style="background:linear-gradient(180deg,#eff6ff 0%,#ffffff 40%,#fff5f5 100%)"></div>
    <div class="hidden dark:block absolute inset-0 bg-[#0b0f19]"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-slate-900 dark:text-white mb-4 uppercase text-center tracking-wider transition-colors">
            Galería
        </h1>
        <div class="w-24 h-[3px] bg-gradient-to-r from-tkd-red to-tkd-blue mx-auto rounded-full"></div>

        <div class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-16">
            <?php foreach ($imagenes as $imagen): 
                 $imagenUrl = asset(str_replace('public/', '', $imagen));
            ?>
                 <a href="<?= htmlspecialchars($imagenUrl) ?>" class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800/80 shadow-md hover:shadow-xl group transition-all duration-300 hover:border-tkd-red/40">
                    <img src="<?= htmlspecialchars($imagenUrl) ?>" alt="Imagen de la galería" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500"/>
                 </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/sitio_pie.php'; ?>

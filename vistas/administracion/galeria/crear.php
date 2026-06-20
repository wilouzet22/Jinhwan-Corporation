<?php include __DIR__ . '/../../layout/administracion_cabecera.php'; ?>

<main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-8">
            <a href="<?= base_url('/admin/galeria') ?>" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-tkd-blue mb-4 transition-colors">
                <span class="material-icons-outlined text-[18px] mr-1">arrow_back</span>
                Volver a la Galería
            </a>
            <h2 class="font-display font-bold text-2xl md:text-3xl text-slate-900 dark:text-white">Añadir Publicación a la Galería</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Ingresa el enlace de Instagram y una descripción para mostrar en la galería.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <form action="<?= base_url('/admin/galeria/store') ?>" method="POST" class="p-6 md:p-8">
                
                <div class="mb-6">
                    <label for="url_instagram" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Enlace de Instagram (URL)</label>
                    <input type="url" id="url_instagram" name="url_instagram" required
                        placeholder="https://www.instagram.com/p/..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue transition-colors">
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Pega aquí el enlace directo a la publicación de Instagram (ej. Reel, Foto o Carrusel).</p>
                </div>

                <div class="mb-8">
                    <label for="descripcion" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Descripción (Opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="4"
                        placeholder="Escribe una breve descripción de la publicación..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-tkd-blue/50 focus:border-tkd-blue transition-colors"></textarea>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <button type="submit" class="px-6 py-2.5 bg-tkd-blue hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                        Guardar Publicación
                    </button>
                    <a href="<?= base_url('/admin/galeria') ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-lg transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?php include __DIR__ . '/../../layout/administracion_pie.php'; ?>

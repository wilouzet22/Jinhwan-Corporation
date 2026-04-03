<?php
include '../conexion.php';

// Fetch all cedes
// Fetch all cedes with student count
$sql = "SELECT c.*, COUNT(us.usuario_id) as numero_estudiantes 
        FROM cedes c 
        LEFT JOIN usuario_sede us ON c.id = us.sede_id
        GROUP BY c.id";
$result = $conn->query($sql);
$cedes = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cedes[] = $row;
    }
}
$conn->close();

include '../header.php';
?>

<!-- Page Header -->
<section class="bg-tkd-black text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-tkd-blue/20 to-transparent"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up">
            Nuestras <span class="text-tkd-blue">Sedes</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up" style="animation-delay: 0.2s;">
            Encuentra el dojang más cercano y comienza tu camino hacia la excelencia.
        </p>
    </div>
    <!-- Decorative Shape -->
    <div class="absolute bottom-0 right-0 w-full h-16 bg-tkd-gray dark:bg-tkd-black" style="clip-path: polygon(100% 0, 0 100%, 100% 100%);"></div>
</section>

<section class="py-16 bg-tkd-gray dark:bg-tkd-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($cedes as $index => $cede): ?>
                <div class="glass-card rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 group animate-fade-in-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <!-- Map Container -->
                    <div class="aspect-video relative overflow-hidden">
                        <div class="absolute inset-0 bg-tkd-black/10 group-hover:bg-transparent transition-colors z-10 pointer-events-none"></div>
                        <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?= urlencode($cede['direccion']) ?>&output=embed" class="grayscale group-hover:grayscale-0 transition-all duration-500"></iframe>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 relative">
                        <div class="absolute top-0 right-6 transform -translate-y-1/2 w-12 h-12 bg-tkd-red rounded-full flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <span class="material-icons-outlined">place</span>
                        </div>
                        
                        <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-2 group-hover:text-tkd-blue transition-colors">
                            <?= htmlspecialchars($cede['nombre']) ?>
                        </h3>
                        
                        <div class="space-y-3 mt-4">
                            <!-- Profesor removed as not in DB, replaced with Phone -->
                            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400">
                                <span class="material-icons-outlined text-tkd-blue">phone</span>
                                <span class="font-medium"><?= htmlspecialchars($cede['telefono']) ?></span>
                            </div>
                            
                            <div class="flex items-start gap-3 text-slate-600 dark:text-slate-400">
                                <span class="material-icons-outlined text-tkd-blue mt-1">location_on</span>
                                <span class="text-sm"><?= htmlspecialchars($cede['direccion']) ?></span>
                            </div>
                            
                            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400">
                                <span class="material-icons-outlined text-tkd-blue">groups</span>
                                <span class="text-sm"><?= htmlspecialchars($cede['numero_estudiantes']) ?> Estudiantes Activos</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <a href="https://maps.google.com/maps?q=<?= urlencode($cede['direccion']) ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold uppercase text-sm hover:bg-tkd-blue hover:text-white transition-all">
                                <span>Cómo llegar</span>
                                <span class="material-icons-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../footer.php'; ?>

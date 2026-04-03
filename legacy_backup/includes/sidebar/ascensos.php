<?php
require_once '../../session_security.php';
// Verificar sesión segura (timeout, session fixation, hijacking)
verificarSesionSegura();

include '../conexion.php';
require_once '../roles.php';

$usuario_id = $_SESSION['id'] ?? null;
$rol_id = $_SESSION['rol_id'] ?? null;
$is_student = ($rol_id == ROL_ESTUDIANTE);

// Fetch Levels (Belts)
$niveles = [];
$res = $conn->query("SELECT * FROM niveles ORDER BY orden ASC");
while($row = $res->fetch_assoc()) {
    $niveles[$row['id']] = $row;
}

// Fetch All Theory Content (TEORÍA Tab)
$teorias = [];
$sql = "SELECT t.*, n.nombre as nivel_nombre, n.orden 
        FROM teoria_galeria t 
        LEFT JOIN niveles n ON t.nivel_id = n.id 
        ORDER BY n.orden ASC, t.id ASC";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $row['recursos'] = [];
        if (!empty($row['url_video'])) {
            $row['recursos'][] = [
                'titulo' => 'Video de Apoyo',
                'url' => $row['url_video'],
                'tipo' => 'Video'
            ];
        }
        $teorias[] = $row;
    }
}

// Fetch Personal Theory (MI TEORÍA Tab)
$mi_teoria_ids = [];
if ($usuario_id) {
    $personal_res = $conn->query("SELECT teoria_id FROM usuario_teoria_personal WHERE usuario_id = $usuario_id");
    while($p_row = $personal_res->fetch_assoc()) {
        $mi_teoria_ids[] = (int)$p_row['teoria_id'];
    }
}

$conn->close();

include '../header.php';

// Helper function for belt colors
function getBeltColor($levelName) {
    $levelName = strtolower($levelName);
    if (strpos($levelName, 'blanco') !== false) return 'border-slate-200 bg-slate-50 text-slate-800';
    if (strpos($levelName, 'amarillo') !== false) return 'border-yellow-400 bg-yellow-50 text-yellow-800';
    if (strpos($levelName, 'verde') !== false) return 'border-green-500 bg-green-50 text-green-800';
    if (strpos($levelName, 'azul') !== false) return 'border-blue-600 bg-blue-50 text-blue-800';
    if (strpos($levelName, 'rojo') !== false) return 'border-red-600 bg-red-50 text-red-800';
    if (strpos($levelName, 'negro') !== false) return 'border-slate-900 bg-slate-900 text-white';
    return 'border-tkd-blue bg-blue-50 text-blue-800'; // Default
}
?>

<!-- Page Header -->
<section class="bg-tkd-black text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-l from-tkd-red/20 to-transparent"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold uppercase tracking-wider mb-4 animate-fade-in-up">
            Material de <span class="text-tkd-red">Ascenso</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light animate-fade-in-up" style="animation-delay: 0.2s;">
            Recursos teóricos y técnicos para tu próximo grado. Estudia con disciplina.
        </p>
    </div>
    <!-- Decorative Shape -->
    <div class="absolute bottom-0 left-0 w-full h-16 bg-tkd-gray dark:bg-tkd-black" style="clip-path: polygon(0 0, 0 100%, 100% 100%);"></div>
</section>

<section class="py-12 bg-tkd-gray dark:bg-tkd-black min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Tabs Navigation -->
        <div class="flex justify-center mb-12">
            <div class="inline-flex p-1 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700">
                <button onclick="switchTab('all')" id="tab-all-btn" class="px-8 py-3 rounded-xl font-display font-bold uppercase tracking-widest transition-all duration-300 bg-tkd-red text-white shadow-md">
                    Teoría
                </button>
                <button onclick="switchTab('mine')" id="tab-mine-btn" class="px-8 py-3 rounded-xl font-display font-bold uppercase tracking-widest transition-all duration-300 text-slate-500 hover:text-tkd-red">
                    Mi Teoría
                </button>
            </div>
        </div>

        <div id="teoria-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($teorias as $index => $teoria): 
                $beltClass = getBeltColor($teoria['nivel_nombre'] ?? '');
                $is_personal = in_array($teoria['id'], $mi_teoria_ids);
            ?>
                <div data-teoria-id="<?= $teoria['id'] ?>" class="teoria-card glass-card rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col animate-fade-in-up cursor-pointer" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <!-- Belt Indicator Top Bar -->
                    <div class="h-2 w-full <?= strpos($beltClass, 'bg-') !== false ? str_replace('text-', 'bg-', explode(' ', $beltClass)[0]) : 'bg-tkd-blue' ?>"></div>
                    
                    <div class="p-6 flex-grow flex flex-col relative">
                        <!-- My Theory Toggle (Absolute) -->
                        <button onclick="event.stopPropagation(); toggleMiTeoria(<?= $teoria['id'] ?>)" class="absolute top-4 right-4 p-2 rounded-full bg-white/80 dark:bg-slate-700/80 shadow-sm hover:scale-110 transition-transform z-10 mi-teoria-toggle <?= $is_personal ? 'text-tkd-gold' : 'text-slate-300' ?>" title="Añadir a mi teoría">
                            <span class="material-icons-outlined"><?= $is_personal ? 'star' : 'star_border' ?></span>
                        </button>

                        <div onclick='openAscensoModal(<?= json_encode($teoria) ?>)' class="flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full <?= $beltClass ?>">
                                    <?= htmlspecialchars($teoria['nivel_nombre'] ?? 'General') ?>
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-display font-bold text-slate-800 dark:text-white mb-3 group-hover:text-tkd-red transition-colors">
                                <?= htmlspecialchars($teoria['titulo']) ?>
                            </h3>
                            
                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-6 line-clamp-3 flex-grow">
                                <?= nl2br(htmlspecialchars($teoria['descripcion'])) ?>
                            </div>

                            <!-- Resources Count -->
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4 mt-auto flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-400">
                                <span class="flex items-center gap-2">
                                    <span class="material-icons-outlined text-sm">folder_open</span>
                                    <?= count($teoria['recursos']) ?> Recursos
                                </span>
                                <span class="group-hover:text-tkd-red transition-colors flex items-center gap-1">
                                    Ver más <span class="material-icons-outlined text-sm">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State for My Theory -->
        <div id="mi-teoria-empty" class="hidden text-center py-20 animate-fade-in-up">
            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-icons-outlined text-5xl text-slate-300">star_outline</span>
            </div>
            <h3 class="text-2xl font-display font-bold text-slate-800 dark:text-white mb-2 uppercase">Tu lista está vacía</h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">Marca con una estrella el material teórico que desees guardar para estudiarlo más tarde.</p>
        </div>
    </div>
</section>

<!-- Ascenso Detail Modal -->
<div id="ascenso-modal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4 md:p-10 backdrop-blur-md transition-opacity duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-4xl h-fit max-h-[90vh] md:h-[85vh] overflow-hidden flex flex-col transform scale-95 transition-transform duration-300" id="modal-content">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
            <div>
                <span id="modal-category" class="text-xs font-bold uppercase tracking-widest text-tkd-blue mb-1 block">Teoría</span>
                <h2 id="modal-title" class="text-2xl md:text-3xl font-display font-bold text-slate-900 dark:text-white">Título de la Teoría</h2>
            </div>
            <button onclick="closeAscensoModal()" class="p-2 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                <span class="material-icons-outlined text-slate-500 dark:text-slate-400">close</span>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-grow space-y-8 custom-scrollbar">
            <!-- Description -->
            <div class="prose dark:prose-invert max-w-none">
                <p id="modal-description" class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
                    Descripción detallada...
                </p>
            </div>

            <!-- Resources Section -->
            <div>
                <h3 class="text-xl font-display font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
                    <span class="material-icons-outlined text-tkd-red">play_circle</span>
                    Material Multimedia
                </h3>
                
                <div id="modal-resources" class="grid grid-cols-1 gap-6">
                    <!-- Resources will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data passed from PHP
    const miTeoriaIds = <?= json_encode($mi_teoria_ids) ?>;
</script>
<script src="/jinwha/js/modules/student-ascensos.js" defer></script>

<?php include '../footer.php'; ?>

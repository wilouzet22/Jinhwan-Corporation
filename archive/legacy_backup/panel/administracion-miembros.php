<?php
session_start();
require_once '../session_security.php';
// Verificar sesión segura (timeout, session fixation, hijacking)
verificarSesionSegura();
verificarAccesoAdmin();

// Configurar headers de seguridad
configurarHeadersSeguridad();

include '../includes/conexion.php';
require_once '../includes/roles.php';

// Handle Create, Update, Delete Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Insertar en usuarios
                // Recuperar rol del formulario o default a Estudiante
                $rol_id = isset($_POST['rol_id']) ? intval($_POST['rol_id']) : ROL_ESTUDIANTE;
                
                // Contraseña por defecto: número de documento
                $clave = password_hash($_POST['numero_documento'], PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, tipo_documento, numero_documento, fecha_nacimiento, nivel_id, telefono, correo, rol_id, clave, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                // sssssissi
                $stmt->bind_param("sssssissis", $_POST['nombre'], $_POST['apellido'], $_POST['tipo_documento'], $_POST['numero_documento'], $_POST['fecha_nacimiento'], $_POST['nivel_id'], $_POST['telefono'], $_POST['correo'], $rol_id, $clave);
                
                if ($stmt->execute()) {
                    $usuario_id = $conn->insert_id;
                    $stmt->close();
                    
                    // Insertar relación sede
                    if (!empty($_POST['cede_id'])) {
                        $stmt_sede = $conn->prepare("INSERT INTO usuario_sede (usuario_id, sede_id) VALUES (?, ?)");
                        $stmt_sede->bind_param("ii", $usuario_id, $_POST['cede_id']);
                        $stmt_sede->execute();
                        $stmt_sede->close();
                    }
                } else {
                    // Manejar error (duplicado, etc) logicamente se deberia pasar a la vista pero por ahora cerramos
                    $stmt->close();
                }
                break;
                
            case 'edit':
                // Actualizar usuarios (sin clave)
                $usuario_id = $_POST['id'];
                $rol_id = isset($_POST['rol_id']) ? intval($_POST['rol_id']) : ROL_ESTUDIANTE;
                
                $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, tipo_documento = ?, numero_documento = ?, fecha_nacimiento = ?, nivel_id = ?, telefono = ?, correo = ?, rol_id = ? WHERE id = ?");
                $stmt->bind_param("sssssissii", $_POST['nombre'], $_POST['apellido'], $_POST['tipo_documento'], $_POST['numero_documento'], $_POST['fecha_nacimiento'], $_POST['nivel_id'], $_POST['telefono'], $_POST['correo'], $rol_id, $usuario_id);
                $stmt->execute();
                $stmt->close();
                
                // Actualizar sede (Borrar y crear nueva para simplificar)
                if (isset($_POST['cede_id'])) {
                    $conn->query("DELETE FROM usuario_sede WHERE usuario_id = $usuario_id");
                    $stmt_sede = $conn->prepare("INSERT INTO usuario_sede (usuario_id, sede_id) VALUES (?, ?)");
                    $stmt_sede->bind_param("ii", $usuario_id, $_POST['cede_id']);
                    $stmt_sede->execute();
                    $stmt_sede->close();
                }
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->bind_param("i", $_POST['id']);
                $stmt->execute();
                $stmt->close();
                // usuario_sede se borra por cascade
                break;
        }
        // Redirect to prevent resubmission
        header("Location: administracion-miembros.php");
        exit;
    }
}


// Roles included at the top


// Fetch helper data for dropdowns
$cedes_list = [];
// Cedes usa 'nombre' no 'cede' en tkd.sql
$res = $conn->query("SELECT id, nombre, direccion FROM cedes");
while($row = $res->fetch_assoc()) $cedes_list[] = $row;

$niveles_list = [];
$res = $conn->query("SELECT * FROM niveles ORDER BY orden ASC");
while($row = $res->fetch_assoc()) $niveles_list[] = $row;

// Fetch all members (Students) with joins
// Usuarios rol = 5 (Estudiante)
$sql = "SELECT u.*, c.nombre as nombre_cede, c.id as cede_id, n.nombre as nombre_nivel 
        FROM usuarios u 
        LEFT JOIN usuario_sede us ON u.id = us.usuario_id
        LEFT JOIN cedes c ON us.sede_id = c.id 
        LEFT JOIN niveles n ON u.nivel_id = n.id 
        ORDER BY u.rol_id ASC, u.nombre ASC";
$result = $conn->query($sql);
$miembros = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $miembros[] = $row;
    }
}
$conn->close();

$page_title = "Administración de Miembros";
$current_page = "miembros";
include '../includes/admin_header.php';
?>

    <main class="flex-grow container mx-auto p-6 lg:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h1 class="text-2xl font-bold mb-4 md:mb-0">Gestión de Miembros</h1>
            <button onclick="openModal('add')" class="bg-primary text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-primary/90 flex items-center gap-2">
                <span class="material-icons-outlined">person_add</span>
                Añadir Nuevo Miembro
            </button>
        </div>
        <div class="bg-surface-light dark:bg-surface-dark rounded-xl border overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-700/50 text-text-light-secondary dark:text-dark-secondary">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Documento</th>
                        <th scope="col" class="px-6 py-3">Cinturón</th>
                        <th scope="col" class="px-6 py-3">Sede</th>
                        <th scope="col" class="px-6 py-3">Rol</th>
                        <th scope="col" class="px-6 py-3">Contacto</th>
                        <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-border-dark">
                    <?php foreach ($miembros as $miembro): ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center mr-3 font-bold">
                                        <?= strtoupper(substr($miembro['nombre'], 0, 1)) ?>
                                    </div>
                                    <?= htmlspecialchars($miembro['nombre']) . ' ' . htmlspecialchars($miembro['apellido']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-text-light-secondary dark:text-dark-secondary">
                                <span class="font-mono text-xs bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded"><?= htmlspecialchars($miembro['tipo_documento']) ?></span>
                                <?= htmlspecialchars($miembro['numero_documento']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <?= htmlspecialchars($miembro['nombre_nivel'] ?? 'Sin Asignar') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-light-secondary dark:text-dark-secondary">
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-outlined text-sm">place</span>
                                    <?= htmlspecialchars($miembro['nombre_cede'] ?? 'Sin Asignar') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php switch($miembro['rol_id']): 
                                    case ROL_SUPERUSUARIO: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Superusuario</span>
                                    <?php break; case ROL_ADMINISTRADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">Admin</span>
                                    <?php break; case ROL_MAESTRO: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Maestro</span>
                                    <?php break; case ROL_CONTADOR: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">Contador</span>
                                    <?php break; default: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Estudiante</span>
                                <?php endswitch; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-text-light-secondary dark:text-dark-secondary">
                                <div><?= htmlspecialchars($miembro['telefono'] ?? '') ?></div>
                                <div class="truncate max-w-[150px]" title="<?= htmlspecialchars($miembro['correo'] ?? '') ?>"><?= htmlspecialchars($miembro['correo'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='openModal("edit", <?= json_encode($miembro) ?>)' class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3" title="Editar">
                                    <span class="material-icons-outlined">edit</span>
                                </button>
                                <form action="administracion-miembros.php" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este miembro?');">
                                    <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Borrar">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<?php include '../includes/admin_footer.php'; ?>

<!-- Modal -->
<div id="member-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-border-light dark:border-border-dark">
            <h2 id="modal-title" class="text-xl font-bold"></h2>
        </div>
        <form id="member-form" action="administracion-miembros.php" method="POST" class="p-6">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="action" id="action">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Información Personal</h3>
                </div>
                
                <div>
                    <label for="nombre" class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                <div>
                    <label for="apellido" class="block text-sm font-medium mb-1">Apellido</label>
                    <input type="text" name="apellido" id="apellido" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium mb-1">Tipo Documento</label>
                    <select name="tipo_documento" id="tipo_documento" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <option value="TI">T.I</option>
                        <option value="CC">C.C</option>
                        <option value="CE">C.E</option>
                        <option value="PAS">Pasaporte</option>
                    </select>
                </div>
                <div>
                    <label for="numero_documento" class="block text-sm font-medium mb-1">Numero Documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                
                <div>
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium mb-1">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                <!-- Edad calculada automaticamente o no requerida en BD -->

                <!-- Contact Info -->
                <div class="col-span-1 md:col-span-2 mt-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Contacto</h3>
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium mb-1">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>
                <div>
                    <label for="correo" class="block text-sm font-medium mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                </div>

                <!-- Academic Info -->
                <div class="col-span-1 md:col-span-2 mt-2">
                    <h3 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">Información Académica</h3>
                </div>

                <div>
                    <label for="nivel_id" class="block text-sm font-medium mb-1">Cinturón (Nivel)</label>
                    <select name="nivel_id" id="nivel_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <?php foreach($niveles_list as $nivel): ?>
                            <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="cede_id" class="block text-sm font-medium mb-1">Sede</label>
                    <select name="cede_id" id="cede_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <?php foreach($cedes_list as $cede): ?>
                            <option value="<?= $cede['id'] ?>"><?= htmlspecialchars($cede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="rol_id" class="block text-sm font-medium mb-1">Rol</label>
                    <select name="rol_id" id="rol_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm">
                        <option value="<?= ROL_ESTUDIANTE ?>">Estudiante</option>
                        <option value="<?= ROL_MAESTRO ?>">Maestro</option>
                        <option value="<?= ROL_ADMINISTRADOR ?>">Administrador</option>
                        <option value="<?= ROL_CONTADOR ?>">Contador</option>
                        <option value="<?= ROL_SUPERUSUARIO ?>">Superusuario</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-border-light dark:border-border-dark">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-blue-600 transition">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="/jinwha/js/modules/admin-miembros.js" defer></script>



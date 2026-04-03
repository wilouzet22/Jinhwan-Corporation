<?php
session_start();
require_once '../session_security.php';
verificarSesionSegura();
configurarHeadersSeguridad();

include_once '../includes/conexion.php';
include_once '../includes/roles.php';

$message = '';
$messageType = ''; // 'success' or 'error'

// Fetch full user data
$usuario_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Helper para convertir rol_id a texto
function getRoleName($id) {
    switch($id) {
        case ROL_SUPERUSUARIO: return 'Superusuario';
        case ROL_ADMINISTRADOR: return 'Administrador';
        case ROL_CONTADOR: return 'Contador';
        case ROL_MAESTRO: return 'Maestro';
        case ROL_ESTUDIANTE: return 'Estudiante';
        default: return 'Usuario';
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    
    if (password_verify($current_pass, $usuario['clave'])) {
        if ($new_pass === $confirm_pass) {
            if (strlen($new_pass) >= 6) {
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
                $stmt->bind_param("si", $new_hash, $usuario_id);
                if ($stmt->execute()) {
                    $message = "Contraseña actualizada correctamente.";
                    $messageType = "success";
                    // Update current user data in case we need it
                    $usuario['clave'] = $new_hash; 
                } else {
                    $message = "Error al actualizar la contraseña.";
                    $messageType = "error";
                }
                $stmt->close();
            } else {
                $message = "La nueva contraseña debe tener al menos 6 caracteres.";
                $messageType = "error";
            }
        } else {
            $message = "Las contraseñas nuevas no coinciden.";
            $messageType = "error";
        }
    } else {
        $message = "La contraseña actual es incorrecta.";
        $messageType = "error";
    }
}

$page_title = "Mi Perfil";
$current_page = "perfil";
include '../includes/admin_header.php';
?>

<main class="flex-grow container mx-auto p-6 lg:p-8">
    <h1 class="text-2xl font-bold mb-6">Mi Perfil</h1>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark overflow-hidden shadow-sm p-6 text-center">
                <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h2>
                <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    <?= getRoleName($usuario['rol_id']) ?>
                </span>
                
                <div class="mt-6 text-left space-y-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Correo Electrónico</span>
                        <span class="block text-sm font-medium break-all"><?= htmlspecialchars($usuario['correo']) ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Documento</span>
                        <span class="block text-sm font-medium"><?= htmlspecialchars($usuario['tipo_documento'] . ' ' . $usuario['numero_documento']) ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Teléfono</span>
                        <span class="block text-sm font-medium"><?= htmlspecialchars($usuario['telefono'] ?? 'No registrado') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings / Change Password -->
        <div class="md:col-span-2">
            <div class="bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark overflow-hidden shadow-sm p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2 border-gray-100 dark:border-gray-700">Seguridad</h3>
                
                <form action="perfil.php" method="POST" class="space-y-4 max-w-md">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Contraseña Actual</label>
                        <input type="password" name="current_password" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:ring-primary focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Nueva Contraseña</label>
                        <input type="password" name="new_password" required minlength="6" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Confirmar Nueva Contraseña</label>
                        <input type="password" name="confirm_password" required minlength="6" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-primary text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 transition flex items-center gap-2">
                            <span class="material-icons-outlined">lock_reset</span>
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
                

            </div>
        </div>
    </div>
</main>

<?php include '../includes/admin_footer.php'; ?>

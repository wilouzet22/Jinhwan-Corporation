<?php
session_start();
require_once '../session_security.php';
verificarSesionSegura();
configurarHeadersSeguridad();

include_once '../includes/conexion.php';
include_once '../includes/roles.php';

// Verificar que sea admin (aunque verificarSesionSegura ya valida sesión, aseguramos rol si es necesario)
// if (!esAdmin($_SESSION['rol_id'])) { header("Location: index.php"); exit; }

$page_title = "Registrar Nuevo Usuario";
$current_page = "registrar_usuario";
include '../includes/admin_header.php';

// Fetch Sedes for dropdown
$cedes = [];
$res = $conn->query("SELECT id, nombre FROM cedes ORDER BY nombre ASC");
if ($res) {
    while($row = $res->fetch_assoc()) $cedes[] = $row;
}
?>

<main class="flex-grow container mx-auto p-6 lg:p-8">
    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 rounded-lg shadow-md border border-slate-200 dark:border-slate-700 p-8">
        <div class="mb-6 border-b border-slate-200 dark:border-slate-700 pb-4">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Registrar Nuevo Usuario</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Complete los datos para dar de alta un nuevo usuario en el sistema.</p>
        </div>

        <form class="space-y-6" action="/jinwha/procregistro.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="rol_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Rol de Usuario</label>
                    <select id="rol_id" name="rol_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="<?= ROL_ESTUDIANTE ?>">Estudiante</option>
                        <option value="<?= ROL_MAESTRO ?>">Maestro</option>
                        <option value="<?= ROL_ADMINISTRADOR ?>">Administrador</option>
                        <option value="<?= ROL_CONTADOR ?>">Contador</option>
                        <option value="<?= ROL_SUPERUSUARIO ?>">Superusuario</option>
                    </select>
                </div>
                <div>
                    <label for="sede_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sede (Opcional)</label>
                    <select id="sede_id" name="sede_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">-- Seleccionar Sede --</option>
                        <?php foreach($cedes as $cede): ?>
                            <option value="<?= $cede['id'] ?>"><?= htmlspecialchars($cede['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre</label>
                    <input id="nombre" name="nombre" type="text" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label for="apellido" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Apellido</label>
                    <input id="apellido" name="apellido" type="text" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div>
                <label for="numero_documento" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Número de Documento</label>
                <input id="numero_documento" name="numero_documento" type="text" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Correo Electrónico</label>
                <input id="email" name="email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contraseña</label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 shadow-sm focus:border-primary focus:ring-primary">
                <p class="mt-1 text-xs text-slate-500">La contraseña inicial para el usuario.</p>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="flex justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <span class="material-icons-outlined mr-2">save</span>
                    Registrar Usuario
                </button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/admin_footer.php'; ?>
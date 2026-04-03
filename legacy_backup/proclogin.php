<?php
session_start();
require_once 'session_security.php';
include_once("includes/conexion.php");

// CORREGIDO: Verificar que el formulario se ha enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $email = $_POST['email'];
    $clave = $_POST['password'];

    include_once 'includes/roles.php';

    // CORREGIDO: Prevenir inyección SQL usando consultas preparadas
    // Buscamos en la tabla usuarios y verificamos que sea un rol administrativo
    $stmt = $conn->prepare("SELECT id, nombre, apellido, correo, clave, rol_id FROM usuarios WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $registro = $resultado->fetch_assoc();
        $claveBd = $registro['clave'];

        // La verificación de la contraseña es correcta
        if (password_verify($clave, $claveBd)) {
            // SECURE: Inicializar sesión segura (previene session fixation)
            $usuario_data = [
                'id'     => $registro['id'],
                'nombre' => $registro['nombre'] . ' ' . $registro['apellido'],
                'correo' => $registro['correo'],
                'rol_id' => $registro['rol_id']
            ];
            
            inicializarSesionSegura($usuario_data);
            
            // REDIRECCIÓN DINÁMICA SEGÚN ROL
            if (esAdmin($registro['rol_id'])) {
                // Admins van a la gestión de sedes por defecto
                header("Location: panel/administracion-cedes.php");
            } elseif ($registro['rol_id'] == ROL_ESTUDIANTE) {
                // Estudiantes van a su panel de ascensos
                header("Location: includes/sidebar/ascensos.php");
            } else {
                // Otros roles (ej. Maestro, Contador) pueden ir al index por ahora o a una página específica
                header("Location: index.php");
            }
            exit();
        } else {
            // CORREGIDO: Redirección de vuelta al login con un mensaje de error
            header("Location: panel/administracion-login.php?error=1");
            exit();
        }
    } else {
        // CORREGIDO: Redirección de vuelta al login con un mensaje de error
        header("Location: panel/administracion-login.php?error=2");
        exit();
    }
    $stmt->close();
} else {
    // Si no es POST, redirigir al login
    header("Location: panel/administracion-login.php");
    exit();
}
?>

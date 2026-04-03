<?php
session_start();
require_once 'session_security.php';
verificarSesionSegura(); // Proteger el endpoint

include_once("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'includes/roles.php';

    // Verificar permisos si es necesario (ej: solo admin puede crear admin)
    // if (!esAdmin($_SESSION['rol_id'])) ...

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $numero_documento = $_POST['numero_documento'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = isset($_POST['rol_id']) ? intval($_POST['rol_id']) : ROL_ESTUDIANTE; // Default a Estudiante si no se envía
    $sede_id = isset($_POST['sede_id']) ? intval($_POST['sede_id']) : 0;

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($numero_documento) || empty($email) || empty($password)) {
        header("Location: panel/administrador-registrar.php?error=empty");
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default tipo_documento = 'CC'
    $tipo_doc = 'CC';

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, tipo_documento, numero_documento, correo, clave, rol_id, activo) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
    
    if ($stmt) {
        $stmt->bind_param("ssssssi", $nombre, $apellido, $tipo_doc, $numero_documento, $email, $hashed_password, $rol);

        if ($stmt->execute()) {
            $usuario_id = $conn->insert_id;
            
            // Si se seleccionó una sede, crear la relación
            if ($sede_id > 0) {
                // Verificar si existe tabla usuario_sede y tiene las columnas correctas
                // Asumimos estructura (usuario_id, sede_id)
                $stmt_sede = $conn->prepare("INSERT INTO usuario_sede (usuario_id, sede_id) VALUES (?, ?)");
                if ($stmt_sede) {
                     $stmt_sede->bind_param("ii", $usuario_id, $sede_id);
                     $stmt_sede->execute();
                     $stmt_sede->close();
                }
            }

            // Registro exitoso, redirigir a la lista de miembros
            header("Location: panel/administracion-miembros.php?msg=usuario_creado");
            exit();
        } else {
            // Error (duplicado, etc)
            header("Location: panel/administrador-registrar.php?error=db_error");
            exit();
        }
        $stmt->close();
    } else {
        header("Location: panel/administrador-registrar.php?error=stmt_error");
        exit();
    }
    
    $conn->close();
} else {
    // Si no es POST, redirigir al formulario de registro
    header("Location: panel/administrador-registrar.php");
    exit();
}
?>

<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/core/Roles.php';

use App\Config\Database;
use App\Config\Roles;

$nombre = "Administrador";
$apellido = "General";
$documento = "99999999";
$correo = "admin@jinhwan.com";
$password = "admin2026";

$db = Database::getInstance()->getConnection();

$stmtCheck = $db->prepare("SELECT id_userlog FROM userlog WHERE correo = ? LIMIT 1");
$stmtCheck->bind_param("s", $correo);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result();

if ($resCheck && $resCheck->num_rows > 0) {
    die("Error: El correo '$correo' ya está registrado en el sistema.\n");
}

$stmtDoc = $db->prepare("SELECT id_miembro FROM miembros WHERE num_doc = ? LIMIT 1");
$stmtDoc->bind_param("s", $documento);
$stmtDoc->execute();
$resDoc = $stmtDoc->get_result();

if ($resDoc && $resDoc->num_rows > 0) {
    die("Error: El número de documento '$documento' ya existe.\n");
}

$db->begin_transaction();

try {
    $rol = Roles::ADMINISTRADOR;
    $id_sede = 2;
    $id_grado = 20;
    $activo = 1;
    
    $sql = "INSERT INTO miembros (nombre, apellido, num_doc, rol, activo, id_sede, id_grado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssssiii", $nombre, $apellido, $documento, $rol, $activo, $id_sede, $id_grado);
    $stmt->execute();
    
    $id_miembro = $stmt->insert_id;
    
    $clave_hash = password_hash($password, PASSWORD_DEFAULT);
    $sqlLog = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)";
    $stmtLog = $db->prepare($sqlLog);
    $stmtLog->bind_param("iss", $id_miembro, $correo, $clave_hash);
    $stmtLog->execute();
    
    $db->commit();
    
    echo "¡Administrador creado con éxito!\n";
    echo "---------------------------------\n";
    echo "Usuario/Correo: $correo\n";
    echo "Contraseña:     $password\n";
    echo "Documento:      $documento\n";
    echo "Rol:            $rol\n";
    echo "---------------------------------\n";
    echo "Ya puedes iniciar sesión en /login\n";
    
} catch (Exception $e) {
    $db->rollback();
    echo "Error al crear el administrador: " . $e->getMessage() . "\n";
}


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

$stmtCheck = $db->prepare("SELECT id_credencial FROM credenciales WHERE correo = ? LIMIT 1");
if (!$stmtCheck) {
    $stmtCheck = $db->prepare("SELECT id_userlog FROM userlog WHERE correo = ? LIMIT 1");
}
$stmtCheck->bind_param("s", $correo);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result();

if ($resCheck && $resCheck->num_rows > 0) {
    die("Error: El correo '$correo' ya está registrado en el sistema.\n");
}

$stmtDoc = $db->prepare("SELECT id_persona FROM personas WHERE num_doc = ? LIMIT 1");
if (!$stmtDoc) {
    $stmtDoc = $db->prepare("SELECT id_miembro FROM miembros WHERE num_doc = ? LIMIT 1");
}
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
    $permisos = json_encode([
        'sedes' => true,
        'registros' => true,
        'ascensos' => true,
        'calendario' => true,
        'galeria' => true,
        'reportes' => true
    ]);
    
    // Insertar en personas
    $sql = "INSERT INTO personas (nombre, apellido, num_doc, tipo_documento, id_sede, activo) VALUES (?, ?, ?, 'CC', ?, 1)";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        $sqlLegacy = "INSERT INTO miembros (nombre, apellido, num_doc, rol, activo, id_sede) VALUES (?, ?, ?, ?, 1, ?)";
        $stmt = $db->prepare($sqlLegacy);
        $stmt->bind_param("ssssi", $nombre, $apellido, $documento, $rol, $id_sede);
    } else {
        $stmt->bind_param("sssi", $nombre, $apellido, $documento, $id_sede);
    }
    $stmt->execute();
    $id_persona = $stmt->insert_id;
    $stmt->close();
    
    // Insertar en credenciales
    $clave_hash = password_hash($password, PASSWORD_DEFAULT);
    $sqlCred = "INSERT INTO credenciales (id_persona, correo, clave, rol, permisos_extra) VALUES (?, ?, ?, ?, ?)";
    $stmtCred = $db->prepare($sqlCred);
    if (!$stmtCred) {
        $sqlLogLegacy = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)";
        $stmtCred = $db->prepare($sqlLogLegacy);
        $stmtCred->bind_param("iss", $id_persona, $correo, $clave_hash);
    } else {
        $stmtCred->bind_param("issss", $id_persona, $correo, $clave_hash, $rol, $permisos);
    }
    $stmtCred->execute();
    $stmtCred->close();
    
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

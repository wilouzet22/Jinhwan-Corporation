<?php
require_once __DIR__ . '/../app/Config/Database.php';

try {
    $db = App\Config\Database::getInstance()->getConnection();
    
    $email = 'admin@jinhwa.com';
    $res = $db->query("SELECT * FROM userlog WHERE correo = '$email'");
    
    if ($res && $res->num_rows > 0) {
        echo "<div style='font-family: sans-serif; padding: 20px; text-align: center;'>";
        echo "<h1 style='color: #2563eb;'>El usuario administrador ya existe.</h1>";
        echo "<p><b>Correo:</b> $email</p>";
        echo "<p><a href='/jinwha/login'>Ir al Login</a></p>";
        echo "</div>";
    } else {
        // Insertar en miembros (rol 1 = Administrador, activo = 1)
        $db->query("INSERT INTO miembros (nombre, apellido, num_doc, rol, activo) VALUES ('Super', 'Administrador', '000000000', '1', 1)");
        $id_miembro = $db->insert_id;
        
        // Insertar en userlog
        $clave_plana = 'admin123';
        $clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);
        $db->query("INSERT INTO userlog (id_miembro, correo, clave) VALUES ($id_miembro, '$email', '$clave_hash')");
        
        echo "<div style='font-family: sans-serif; padding: 20px; text-align: center; border: 2px solid #10b981; border-radius: 10px; max-width: 500px; margin: 0 auto; margin-top: 50px;'>";
        echo "<h1 style='color: #10b981;'>¡Usuario administrador creado con éxito!</h1>";
        echo "<p><b>Correo:</b> $email</p>";
        echo "<p><b>Contraseña:</b> $clave_plana</p>";
        echo "<br>";
        echo "<a href='/jinwha/login' style='background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Ir al Login</a>";
        echo "<p style='margin-top: 20px; color: #ef4444; font-size: 12px;'><i>Recuerda eliminar este archivo (crear_admin.php) por seguridad después de iniciar sesión.</i></p>";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

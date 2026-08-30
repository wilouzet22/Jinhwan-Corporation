<?php
// Script de verificación rápida
$host = 'localhost';
$user = 'root';
$pass = '';
$name = 'jinhwa_corporation';

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$result = $conn->query("SHOW TABLES LIKE 'certificados_ascenso'");
if ($result->num_rows > 0) {
    echo "✅ Tabla 'certificados_ascenso' existe.<br>";
    
    // Mostrar estructura
    $columns = $conn->query("DESCRIBE certificados_ascenso");
    echo "<br>Estructura de la tabla:<br>";
    while ($row = $columns->fetch_assoc()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
    }
} else {
    echo "❌ Tabla 'certificados_ascenso' NO existe.<br>";
}

$conn->close();
?>

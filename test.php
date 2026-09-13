<?php
echo "<h2>Diagnóstico del servidor</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";

// Verificar si mysqli está habilitado
echo "<p><strong>mysqli disponible:</strong> ";
if (extension_loaded('mysqli')) {
    echo "✅ Sí";
} else {
    echo "❌ NO - mysqli no está habilitado";
}
echo "</p>";

// Verificar si PDO MySQL está disponible
echo "<p><strong>PDO MySQL disponible:</strong> ";
if (extension_loaded('pdo_mysql')) {
    echo "✅ Sí";
} else {
    echo "❌ No";
}
echo "</p>";

// Test conexión con @ para suprimir warnings
$host = 'sql113.infinityfree.com';
$user = 'if0_42216592';
$pass = 'IfK0M0n94NKpHQq';
$name = 'if0_42216592_jinhwa';

echo "<h3>Test de conexión BD:</h3>";

if (!extension_loaded('mysqli')) {
    echo "<p style='color:red'>❌ No se puede conectar: mysqli no está cargado.</p>";
} else {
    mysqli_report(MYSQLI_REPORT_OFF); // No lanzar excepciones
    $conn = @new mysqli($host, $user, $pass, $name);

    if ($conn->connect_errno) {
        echo "<p style='color:red'>❌ Error " . $conn->connect_errno . ": " . $conn->connect_error . "</p>";
        echo "<p>Intentando con 'localhost'...</p>";

        // Intentar con localhost
        $conn2 = @new mysqli('localhost', $user, $pass, $name);
        if ($conn2->connect_errno) {
            echo "<p style='color:red'>❌ localhost también falló: " . $conn2->connect_error . "</p>";
        } else {
            echo "<p style='color:green'>✅ ¡Conectado con <strong>localhost</strong>! Usa ese host.</p>";
            $conn2->close();
        }
    } else {
        echo "<p style='color:green'>✅ BD Conectada correctamente con <strong>$host</strong></p>";

        // Verificar tablas
        $tablas = ['administrador', 'maestro', 'estudiante', 'grupos', 'grados'];
        foreach ($tablas as $tabla) {
            $res = $conn->query("SHOW TABLES LIKE '$tabla'");
            $existe = ($res && $res->num_rows > 0) ? "✅" : "❌ FALTA";
            echo "<p>$existe Tabla <strong>$tabla</strong></p>";
        }
        $conn->close();
    }
}

echo "<hr><p style='color:orange'><strong>⚠️ Elimina este archivo cuando termines.</strong></p>";

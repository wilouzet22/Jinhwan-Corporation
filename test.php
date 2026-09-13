<?php
// Archivo de diagnóstico temporal - ELIMINAR después de usarlo

echo "<h2>Diagnóstico del servidor</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "</p>";

// Test conexión BD
$host = 'sql113.infinityfree.com';
$user = 'if0_42216592';
$pass = 'IfK0M0n94NKpHQq';
$name = 'if0_42216592_jinhwa';

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
    echo "<p style='color:red'><strong>❌ BD Error:</strong> " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green'><strong>✅ BD Conectada correctamente</strong></p>";

    // Verificar tablas
    $tablas = ['administrador', 'maestro', 'estudiante', 'grupos', 'grados'];
    foreach ($tablas as $tabla) {
        $res = $conn->query("SHOW TABLES LIKE '$tabla'");
        $existe = ($res && $res->num_rows > 0) ? "✅" : "❌";
        echo "<p>$existe Tabla <strong>$tabla</strong></p>";
    }

    $conn->close();
}

// Test mod_rewrite
echo "<p><strong>mod_rewrite:</strong> ";
if (function_exists('apache_get_modules')) {
    echo in_array('mod_rewrite', apache_get_modules()) ? "✅ Activo" : "❌ No activo";
} else {
    echo "No se puede verificar (no Apache)";
}
echo "</p>";

echo "<p style='color:orange'><strong>⚠️ ELIMINA ESTE ARCHIVO DEL SERVIDOR DESPUÉS DE REVISAR</strong></p>";

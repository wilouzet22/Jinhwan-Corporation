<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test completo</h2>";
echo "<b>PHP:</b> " . phpversion() . "<br>";

// Test mysqli
echo "<b>mysqli:</b> " . (extension_loaded('mysqli') ? '✅' : '❌') . "<br>";

// Test conexión
if (extension_loaded('mysqli')) {
    mysqli_report(MYSQLI_REPORT_OFF);

    // Probar 127.0.0.1
    $c = @new mysqli('127.0.0.1', 'if0_42216592', 'IfK0M0n94NKpHQq', 'if0_42216592_jinhwa');
    if ($c->connect_errno) {
        echo "<b style='color:red'>❌ 127.0.0.1 falla:</b> " . $c->connect_error . "<br>";
    } else {
        echo "<b style='color:green'>✅ 127.0.0.1 funciona!</b><br>";
        // Tablas
        foreach (['administrador','maestro','estudiante','grupos','grados'] as $t) {
            $r = $c->query("SHOW TABLES LIKE '$t'");
            echo ($r && $r->num_rows > 0 ? "✅" : "❌") . " $t<br>";
        }
        $c->close();
    }

    // Probar sql113
    $c2 = @new mysqli('sql113.infinityfree.com', 'if0_42216592', 'IfK0M0n94NKpHQq', 'if0_42216592_jinhwa');
    if ($c2->connect_errno) {
        echo "<b style='color:red'>❌ sql113 falla:</b> " . $c2->connect_error . "<br>";
    } else {
        echo "<b style='color:green'>✅ sql113 funciona!</b><br>";
        $c2->close();
    }
}

// Test include del proyecto
echo "<hr><b>Test de includes:</b><br>";
$archivos = [
    'config/conexion.php',
    'core/Roles.php',
    'core/Security.php',
    'core/Model.php',
    'core/Controller.php',
    'core/Router.php',
];
foreach ($archivos as $f) {
    echo file_exists(__DIR__ . '/' . $f) ? "✅ $f<br>" : "❌ FALTA: $f<br>";
}

echo "<hr><p style='color:orange'><b>Elimina este archivo después.</b></p>";

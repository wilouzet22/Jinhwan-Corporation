<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'jinhwa_corporation');
if ($conn->connect_error) {
    // Intentar con if0_42216592_jinhwa_corporation
    $conn = @new mysqli('127.0.0.1', 'root', '', 'if0_42216592_jinhwa_corporation');
}
if ($conn->connect_error) {
    die("Error local: " . $conn->connect_error . "\n");
}
echo "Conectado a BD local: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . "\n";

foreach (['maestro', 'estudiante', 'administrador', 'galeria_multimedia'] as $table) {
    echo "=== {$table} ===\n";
    $res = $conn->query("DESCRIBE {$table}");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "  Error: " . $conn->error . "\n";
    }
}
c
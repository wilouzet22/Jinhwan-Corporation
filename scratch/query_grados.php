<?php
require_once __DIR__ . '/../config/conexion.php';
$db = Database::getInstance()->getConnection();
$r = $db->query('SELECT * FROM grados ORDER BY id_grado ASC');
while ($row = $r->fetch_assoc()) {
    echo "ID: {$row['id_grado']} | Nombre: {$row['nombre']}\n";
}

<?php
require_once 'session_security.php';
// Verificar sesión segura
verificarSesionSegura();

include 'includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teoria_id'])) {
    $usuario_id = (int)$_SESSION['id'];
    $teoria_id = (int)$_POST['teoria_id'];
    
    // Check if it already exists
    $check = $conn->prepare("SELECT 1 FROM usuario_teoria_personal WHERE usuario_id = ? AND teoria_id = ?");
    $check->bind_param("ii", $usuario_id, $teoria_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Remove
        $stmt = $conn->prepare("DELETE FROM usuario_teoria_personal WHERE usuario_id = ? AND teoria_id = ?");
        $stmt->bind_param("ii", $usuario_id, $teoria_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        // Add
        $stmt = $conn->prepare("INSERT INTO usuario_teoria_personal (usuario_id, teoria_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $teoria_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'action' => 'added']);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
    
    $stmt->close();
    $check->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$conn->close();

<?php
session_start();
require_once 'session_security.php';

// Cerrar sesión de forma segura usando el helper
cerrarSesion();

// Redirigir al login
header("Location: panel/administracion-login.php");
exit;
?>

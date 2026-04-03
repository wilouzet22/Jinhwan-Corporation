<?php
/**
 * Sistema de Seguridad de Sesiones
 * Protege contra: Session Fixation, Session Hijacking, Timeout
 * 
 * Uso: require_once 'session_security.php'; verificarSesionSegura();
 */

// Centralizar el inicio de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/**
 * Verifica que la sesión sea segura y válida
 * Implementa múltiples capas de seguridad
 */
function verificarSesionSegura() {
    // 1. Verificar si existe sesión de usuario activas
    if (!isset($_SESSION['id'])) {
        redirigirLogin('no_session');
    }
    
    // 2. Verificar timeout de inactividad (30 minutos)
    $timeout = 1800; // 30 minutos en segundos
    if (isset($_SESSION['last_activity'])) {
        $inactivo = time() - $_SESSION['last_activity'];
        if ($inactivo > $timeout) {
            cerrarSesion();
            redirigirLogin('timeout');
        }
    }
    
    // Actualizar tiempo de última actividad
    $_SESSION['last_activity'] = time();
    
    // 3. Verificar User Agent (previene session hijacking básico)
    if (isset($_SESSION['user_agent'])) {
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            cerrarSesion();
            redirigirLogin('security');
        }
    }
    
    // 4. Regenerar ID de sesión periódicamente (cada 30 minutos)
    // Esto previene session fixation
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } else {
        $tiempo_desde_regeneracion = time() - $_SESSION['last_regeneration'];
        if ($tiempo_desde_regeneracion > 1800) { // 30 minutos
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    // 5. Verificar que la sesión tenga tiempo de login
    if (!isset($_SESSION['login_time'])) {
        cerrarSesion();
        redirigirLogin('invalid');
    }
    
    // 6. Timeout absoluto de sesión (8 horas desde login)
    $max_session_time = 28800; // 8 horas
    if ((time() - $_SESSION['login_time']) > $max_session_time) {
        cerrarSesion();
        redirigirLogin('expired');
    }
}

/**
 * Verifica que el usuario tenga rol de administrador
 * Debe llamarse en páginas de administración después de verificarSesionSegura
 */
function verificarAccesoAdmin() {
    require_once __DIR__ . '/includes/roles.php';
    $rol_id = $_SESSION['rol_id'] ?? null;
    
    if (!esAdmin($rol_id)) {
        // Si no es admin, redirigir a una página segura (ej. index o ascensos)
        header('Location: index.php?msg=access_denied');
        exit;
    }
}


/**
 * Inicializa una sesión segura después del login exitoso
 * Debe llamarse después de verificar credenciales
 */
function inicializarSesionSegura($usuario) {
    // Regenerar ID de sesión para prevenir session fixation
    session_regenerate_id(true);
    
    // Guardar datos de usuario (Flat Structure)
    if (is_array($usuario)) {
        foreach ($usuario as $key => $value) {
            $_SESSION[$key] = $value;
        }
    } else {
        // Fallback for unexpected data
         $_SESSION['usuario_data'] = $usuario;
    }
    
    // Guardar timestamps
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
    $_SESSION['last_regeneration'] = time();
    
    // Guardar datos de seguridad
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    
    // Opcional: Guardar IP (comentado porque puede causar problemas con IPs dinámicas)
    // $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
}

/**
 * Cierra la sesión de forma segura
 */
function cerrarSesion() {
    // Limpiar todas las variables de sesión
    $_SESSION = array();
    
    // Destruir la cookie de sesión
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destruir la sesión
    session_destroy();
}

/**
 * Redirige a la página de login con mensaje
 */
function redirigirLogin($motivo = '') {
    $url = 'panel/administracion-login.php';
    if ($motivo) {
        $url .= '?msg=' . urlencode($motivo);
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Configurar headers de seguridad para sesiones
 * Llamar al inicio de cada página de administración
 */
function configurarHeadersSeguridad() {
    // Prevenir clickjacking
    header('X-Frame-Options: DENY');
    
    // Prevenir MIME sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Habilitar protección XSS del navegador
    header('X-XSS-Protection: 1; mode=block');
    
    // Política de referrer
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy (básico)
    header("Content-Security-Policy: default-src 'self' https://cdn.tailwindcss.com https://fonts.googleapis.com https://fonts.gstatic.com; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data:;");
}

/**
 * Generar token CSRF
 */
function generarTokenCSRF() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verificarTokenCSRF($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtener HTML del campo CSRF para formularios
 */
function campoCSRF() {
    $token = generarTokenCSRF();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
?>

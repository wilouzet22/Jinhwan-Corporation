<?php
namespace App\Core;

use App\Config\Roles;

class Security {
    
    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function verifySession() {
        self::initSession();

        // 1. Verify if session exists
        if (!isset($_SESSION['id'])) {
            $log = date('Y-m-d H:i:s') . " - Session Fail: Missing ID. Session=" . session_id() . "\n";
            file_put_contents('debug_login.txt', $log, FILE_APPEND);
            self::redirectLogin('no_session');
        }
        
        // 2. Timeout (30 mins)
        $timeout = 1800; 
        if (isset($_SESSION['last_activity'])) {
            $inactivo = time() - $_SESSION['last_activity'];
            if ($inactivo > $timeout) {
                self::logout();
                self::redirectLogin('timeout');
            }
        }
        
        $_SESSION['last_activity'] = time();
        
        // 3. User Agent
        if (isset($_SESSION['user_agent'])) {
            if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                self::logout();
                self::redirectLogin('security');
            }
        }
        
        // 4. Regenerate ID
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } else {
            $tiempo_desde_regeneracion = time() - $_SESSION['last_regeneration'];
            if ($tiempo_desde_regeneracion > 1800) { 
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
        }
        
        // 5. Login Time
        if (!isset($_SESSION['login_time'])) {
            self::logout();
            self::redirectLogin('invalid');
        }
        
        // 6. Absolute Timeout (8 hours)
        $max_session_time = 28800; 
        if ((time() - $_SESSION['login_time']) > $max_session_time) {
            self::logout();
            self::redirectLogin('expired');
        }
    }

    public static function verifyAdmin() {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;
        
        if (!Roles::esAdmin($rol_id)) {
            $log = date('Y-m-d H:i:s') . " - Admin Fail: Invalid Rol=" . var_export($rol_id, true) . "\n";
            file_put_contents('debug_login.txt', $log, FILE_APPEND);
            $base = self::getBasePath();
            header("Location: " . $base . "/index.php?msg=access_denied");
            exit;
        }
        
        $log = date('Y-m-d H:i:s') . " - Admin Success: Rol=" . $rol_id . "\n";
        file_put_contents('debug_login.txt', $log, FILE_APPEND);
    }

    private static function getBasePath() {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base === DIRECTORY_SEPARATOR || $base === '/' || $base === '\\') {
            return '';
        }
        return $base;
    }

    public static function startSecureSession($usuario) {
        self::initSession();
        session_regenerate_id(true);
        
        if (is_array($usuario)) {
            foreach ($usuario as $key => $value) {
                $_SESSION[$key] = $value;
            }
        } else {
             $_SESSION['usuario_data'] = $usuario;
        }
        
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['last_regeneration'] = time();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }

    public static function logout() {
        self::initSession();
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    public static function redirectLogin($motivo = '') {
        $base = self::getBasePath();
        $url = $base . '/login';
        if ($motivo) {
            $url .= '?error=' . urlencode($motivo);
        }
        header('Location: ' . $url);
        exit;
    }
}

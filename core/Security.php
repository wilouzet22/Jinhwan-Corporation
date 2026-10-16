<?php

class Security {

    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Asegurar parámetros de cookie persistentes en todo el sitio
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_lifetime', '604800'); // 7 días
            ini_set('session.gc_maxlifetime', '604800');

            session_set_cookie_params([
                'lifetime' => 604800,
                'path'     => '/',
                'domain'   => '',
                'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }
    }

    public static function verifySession() {
        self::initSession();

        if (!isset($_SESSION['id'])) {
            self::redirectLogin('no_session');
        }

        // Timeout generoso de 8 horas de inactividad
        $timeout = 28800;
        if (isset($_SESSION['last_activity'])) {
            $inactivo = time() - $_SESSION['last_activity'];
            if ($inactivo > $timeout) {
                self::logout();
                self::redirectLogin('timeout');
            }
        }
        $_SESSION['last_activity'] = time();

        // Si login_time no fue inicializado, fijarlo ahora sin invalidar la sesión
        if (!isset($_SESSION['login_time'])) {
            $_SESSION['login_time'] = time();
        }

        // Tiempo máximo de validez de la sesión (7 días)
        $max_session_time = 604800;
        if ((time() - $_SESSION['login_time']) > $max_session_time) {
            self::logout();
            self::redirectLogin('expired');
        }
    }

    public static function verifyAdmin() {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;

        if (!Roles::esAdmin($rol_id)) {
            $base = self::getBasePath();
            header("Location: " . $base . "/?msg=access_denied");
            exit;
        }
    }

    public static function hasPermission($permiso) {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;

        if (Roles::esAdmin($rol_id)) {
            return true;
        }

        if (isset($_SESSION['permisos_extra']) && !empty($_SESSION['permisos_extra'])) {
            $permisos = json_decode($_SESSION['permisos_extra'], true);
            if (is_array($permisos) && isset($permisos[$permiso]) && $permisos[$permiso] === true) {
                return true;
            }
        }

        return false;
    }

    public static function verifyPermission($permiso) {
        self::initSession();
        if (!self::hasPermission($permiso)) {
            $base = self::getBasePath();
            header("Location: " . $base . "/?msg=access_denied");
            exit;
        }
    }

    public static function verifyMaestro() {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;

        $tieneAcceso = Roles::esMaestro($rol_id)
                    || $rol_id === Roles::PROFESOR
                    || $rol_id === Roles::MONITOR;

        if (!$tieneAcceso) {
            $base = self::getBasePath();
            header("Location: " . $base . "/?msg=access_denied");
            exit;
        }
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

        // Regenerar ID de sesión de forma segura sin borrar la anterior inmediatamente
        session_regenerate_id(false);

        if (is_array($usuario)) {
            foreach ($usuario as $key => $value) {
                $_SESSION[$key] = $value;
            }
        } else {
            $_SESSION['usuario_data'] = $usuario;
        }

        $_SESSION['login_time']        = time();
        $_SESSION['last_activity']     = time();
        $_SESSION['last_regeneration'] = time();
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
        $url  = $base . '/login';

        if ($motivo) {
            $url .= '?error=' . urlencode($motivo);
        }

        header('Location: ' . $url);
        exit;
    }
}


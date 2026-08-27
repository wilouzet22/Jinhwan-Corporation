<?php

class Security {

    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function verifySession() {
        self::initSession();

        if (!isset($_SESSION['id'])) {
            self::redirectLogin('no_session');
        }

        $timeout = 1800;
        if (isset($_SESSION['last_activity'])) {
            $inactivo = time() - $_SESSION['last_activity'];
            if ($inactivo > $timeout) {
                self::logout();
                self::redirectLogin('timeout');
            }
        }

        $_SESSION['last_activity'] = time();

        if (isset($_SESSION['user_agent'])) {
            if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                self::logout();
                self::redirectLogin('security');
            }
        }

        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } else {
            $tiempo_desde_regeneracion = time() - $_SESSION['last_regeneration'];
            if ($tiempo_desde_regeneracion > 1800) {
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
        }

        if (!isset($_SESSION['login_time'])) {
            self::logout();
            self::redirectLogin('invalid');
        }

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

        session_regenerate_id(true);

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
        $_SESSION['user_agent']        = $_SERVER['HTTP_USER_AGENT'];
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


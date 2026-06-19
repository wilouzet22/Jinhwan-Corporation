<?php
/**
 * ============================================================
 * CLASE DE SEGURIDAD (Security)
 * ============================================================
 * Centraliza toda la lógica de autenticación y protección de
 * sesiones de la aplicación. Sus responsabilidades son:
 *
 *   1. Iniciar sesiones PHP de forma segura.
 *   2. Verificar que la sesión sea válida antes de acceder a
 *      rutas protegidas (timeout, User-Agent, regeneración de ID).
 *   3. Verificar que el usuario autenticado tenga rol de Administrador.
 *   4. Crear sesiones seguras al hacer login.
 *   5. Destruir la sesión al hacer logout.
 *   6. Redirigir al login con motivo cuando la sesión es inválida.
 * ============================================================
 */
namespace App\Core;

use App\Config\Roles;

class Security {

    /**
     * Inicia la sesión PHP solo si aún no está activa.
     * Llamado internamente antes de cualquier operación sobre $_SESSION.
     */
    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica que la sesión actual sea legítima y esté activa.
     * Si cualquier validación falla, cierra la sesión y redirige al login.
     *
     * Validaciones que realiza (en orden):
     *
     * 1. EXISTENCIA DE SESIÓN: comprueba que $_SESSION['id'] esté definido.
     *    Si no existe, el usuario no ha iniciado sesión → redirigir.
     *
     * 2. TIMEOUT DE INACTIVIDAD (30 minutos):
     *    Si han pasado más de 1800 segundos desde la última actividad,
     *    la sesión expira → logout y redirigir.
     *    Se actualiza $_SESSION['last_activity'] en cada petición válida.
     *
     * 3. VALIDACIÓN DE USER-AGENT:
     *    Compara el navegador actual con el que se guardó al hacer login.
     *    Si difieren (posible robo de sesión) → logout y redirigir.
     *
     * 4. REGENERACIÓN DEL ID DE SESIÓN (cada 30 minutos):
     *    Cambia el session_id() para mitigar ataques de fijación de sesión.
     *
     * 5. EXISTENCIA DE TIEMPO DE LOGIN:
     *    Si $_SESSION['login_time'] no está definido, la sesión es inválida.
     *
     * 6. TIMEOUT ABSOLUTO (8 horas):
     *    Sin importar la actividad, la sesión expira a las 28800 segundos
     *    de haber iniciado sesión.
     */
    public static function verifySession() {
        self::initSession();

        // 1. Verificar que exista un ID de sesión válido
        if (!isset($_SESSION['id'])) {
            // Registrar el fallo en el archivo de debug
            $log = date('Y-m-d H:i:s') . " - Session Fail: Missing ID. Session=" . session_id() . "\n";
            file_put_contents('debug_login.txt', $log, FILE_APPEND);
            self::redirectLogin('no_session');
        }

        // 2. Timeout de inactividad: 30 minutos = 1800 segundos
        $timeout = 1800;
        if (isset($_SESSION['last_activity'])) {
            $inactivo = time() - $_SESSION['last_activity'];
            if ($inactivo > $timeout) {
                self::logout();
                self::redirectLogin('timeout');
            }
        }

        // Actualizar el timestamp de última actividad en cada petición válida
        $_SESSION['last_activity'] = time();

        // 3. Verificar que el User-Agent no haya cambiado (anti-session hijacking)
        if (isset($_SESSION['user_agent'])) {
            if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                self::logout();
                self::redirectLogin('security');
            }
        }

        // 4. Regenerar el ID de sesión cada 30 minutos (anti-session fixation)
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } else {
            $tiempo_desde_regeneracion = time() - $_SESSION['last_regeneration'];
            if ($tiempo_desde_regeneracion > 1800) {
                session_regenerate_id(true); // true = eliminar la sesión antigua
                $_SESSION['last_regeneration'] = time();
            }
        }

        // 5. Verificar que exista el tiempo de inicio de sesión
        if (!isset($_SESSION['login_time'])) {
            self::logout();
            self::redirectLogin('invalid');
        }

        // 6. Timeout absoluto: 8 horas = 28800 segundos
        $max_session_time = 28800;
        if ((time() - $_SESSION['login_time']) > $max_session_time) {
            self::logout();
            self::redirectLogin('expired');
        }
    }

    /**
     * Verifica que el usuario autenticado tenga rol de Administrador.
     * Debe llamarse DESPUÉS de verifySession().
     *
     * Lee $_SESSION['rol_id'] y lo compara con Roles::ADMINISTRADOR (1).
     * Si el rol no es administrador, redirige a la portada con error 'access_denied'.
     * Registra el resultado (éxito o fallo) en debug_login.txt.
     */
    public static function verifyAdmin() {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;

        if (!Roles::esAdmin($rol_id)) {
            // Registrar el intento no autorizado
            $log = date('Y-m-d H:i:s') . " - Admin Fail: Invalid Rol=" . var_export($rol_id, true) . "\n";
            file_put_contents('debug_login.txt', $log, FILE_APPEND);

            // Redirigir a la portada con mensaje de acceso denegado
            $base = self::getBasePath();
            header("Location: " . $base . "/?msg=access_denied");
            exit;
        }

        // Registrar acceso administrativo exitoso
        $log = date('Y-m-d H:i:s') . " - Admin Success: Rol=" . $rol_id . "\n";
        file_put_contents('debug_login.txt', $log, FILE_APPEND);
    }

    /**
     * Verifica que el usuario autenticado tenga rol de Maestro.
     * Debe llamarse DESPUÉS de verifySession().
     */
    public static function verifyMaestro() {
        self::initSession();
        $rol_id = $_SESSION['rol_id'] ?? null;

        // Permite acceso a Maestros, Profesores y Monitores
        $tieneAcceso = Roles::esMaestro($rol_id)
                    || $rol_id === Roles::PROFESOR
                    || $rol_id === Roles::MONITOR;

        if (!$tieneAcceso) {
            // Registrar el intento no autorizado
            $log = date('Y-m-d H:i:s') . " - Maestro Fail: Invalid Rol=" . var_export($rol_id, true) . "\n";
            file_put_contents('debug_login.txt', $log, FILE_APPEND);

            // Redirigir a la portada con mensaje de acceso denegado
            $base = self::getBasePath();
            header("Location: " . $base . "/?msg=access_denied");
            exit;
        }

        // Registrar acceso de maestro exitoso
        $log = date('Y-m-d H:i:s') . " - Maestro Success: Rol=" . $rol_id . "\n";
        file_put_contents('debug_login.txt', $log, FILE_APPEND);
    }

    /**
     * Calcula el prefijo base del proyecto (subcarpeta del servidor).
     * Devuelve '' si la app está en la raíz, o la ruta de subcarpeta si no.
     *
     * Ejemplo: si la app está en /Jinhwan-Corporation-main/ devuelve
     *          '/Jinhwan-Corporation-main'
     *
     * @return string Prefijo base (sin barra final)
     */
    private static function getBasePath() {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        // En Windows o en raíz, dirname devuelve '\' o '/', tratar ambos
        if ($base === DIRECTORY_SEPARATOR || $base === '/' || $base === '\\') {
            return '';
        }
        return $base;
    }

    /**
     * Crea una sesión segura para el usuario que acaba de autenticarse.
     *
     * Acciones que realiza:
     *   - Regenera el session_id para prevenir session fixation.
     *   - Almacena todos los datos del usuario en $_SESSION.
     *   - Registra timestamps de login, última actividad y última regeneración.
     *   - Guarda el User-Agent para validación futura.
     *
     * @param array $usuario Array con los datos del usuario, tipicamente:
     *                       ['id', 'nombre', 'correo', 'rol_id']
     */
    public static function startSecureSession($usuario) {
        self::initSession();

        // Regenerar ID de sesión para evitar session fixation attacks
        session_regenerate_id(true);

        // Guardar cada dato del usuario como variable de sesión
        if (is_array($usuario)) {
            foreach ($usuario as $key => $value) {
                $_SESSION[$key] = $value;
            }
        } else {
            // Compatibilidad si se pasa un objeto en lugar de array
            $_SESSION['usuario_data'] = $usuario;
        }

        // Timestamps de seguridad
        $_SESSION['login_time']        = time(); // Momento exacto del login
        $_SESSION['last_activity']     = time(); // Para el timeout de inactividad
        $_SESSION['last_regeneration'] = time(); // Para la regeneración periódica del ID
        $_SESSION['user_agent']        = $_SERVER['HTTP_USER_AGENT']; // Navegador del cliente
    }

    /**
     * Cierra la sesión actual de forma completa y segura.
     *
     * 1. Vacía el array $_SESSION.
     * 2. Elimina la cookie de sesión del navegador del cliente.
     * 3. Destruye la sesión en el servidor.
     */
    public static function logout() {
        self::initSession();

        // Vaciar todas las variables de sesión
        $_SESSION = array();

        // Eliminar la cookie de sesión del navegador (si existe)
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destruir la sesión en el servidor
        session_destroy();
    }

    /**
     * Redirige al usuario a la página de login con un parámetro
     * de error para mostrar el mensaje adecuado en el formulario.
     *
     * @param string $motivo Código del error (p. ej. 'timeout', 'no_session',
     *                       'security', 'expired', 'invalid')
     */
    public static function redirectLogin($motivo = '') {
        $base = self::getBasePath();
        $url  = $base . '/login';

        // Agregar el motivo como query string si se proporcionó
        if ($motivo) {
            $url .= '?error=' . urlencode($motivo);
        }

        header('Location: ' . $url);
        exit;
    }
}

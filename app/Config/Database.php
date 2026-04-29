<?php
/**
 * ============================================================
 * CONFIGURACIÓN DE BASE DE DATOS (Database)
 * ============================================================
 * Implementa el patrón de diseño Singleton para garantizar que
 * solo exista UNA única conexión a la base de datos MySQL
 * durante todo el ciclo de vida de cada petición HTTP.
 *
 * Ventajas del Singleton aquí:
 *   - Evita abrir múltiples conexiones innecesarias a MySQL.
 *   - Todos los modelos comparten la misma instancia mysqli.
 *
 * Base de datos usada: jinhwa_corporation (MySQL vía Laragon)
 * ============================================================
 */
namespace App\Config;

use mysqli;

class Database {

    /**
     * Instancia única de esta clase (patrón Singleton).
     * Solo existirá un objeto Database por petición.
     */
    private static $instance = null;

    /**
     * Objeto de conexión mysqli activo.
     */
    private $connection;

    // ── Credenciales de conexión ──────────────────────────────
    private $host = 'localhost';       // Servidor MySQL (Laragon usa localhost)
    private $user = 'root';            // Usuario de MySQL (por defecto en Laragon)
    private $pass = '';                // Contraseña (vacía en Laragon por defecto)
    private $name = 'jinhwa_corporation'; // Nombre de la base de datos

    /**
     * Constructor privado: impide instanciar la clase directamente con 'new'.
     * Solo se puede obtener la instancia mediante Database::getInstance().
     *
     * Acciones al construir:
     *   1. Abre la conexión mysqli con las credenciales definidas.
     *   2. Si falla la conexión, detiene la aplicación con mensaje de error.
     *   3. Establece el charset a utf8mb4 para soporte de caracteres especiales
     *      (incluye emojis y caracteres latinos con tilde).
     */
    private function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->name);

        // Verificar error de conexión
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        // Forzar codificación utf8mb4 para correcta lectura/escritura de caracteres
        $this->connection->set_charset("utf8mb4");
    }

    /**
     * Obtiene la instancia única de Database (Singleton).
     *
     * Si aún no existe ninguna instancia, la crea.
     * Si ya existe, devuelve la misma instancia creada anteriormente.
     *
     * @return Database La única instancia de esta clase
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Devuelve el objeto mysqli de conexión activa.
     * Los modelos llaman a este método para ejecutar consultas SQL.
     *
     * @return mysqli Conexión activa a MySQL
     */
    public function getConnection() {
        return $this->connection;
    }
}

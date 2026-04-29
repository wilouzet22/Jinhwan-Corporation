<?php
/**
 * ============================================================
 * MODELO BASE (Model)
 * ============================================================
 * Todos los modelos de la aplicación extienden esta clase.
 * Su única responsabilidad es obtener la conexión a la base
 * de datos (mysqli) y dejarla disponible en la propiedad $db
 * para que los modelos hijos puedan ejecutar consultas SQL.
 *
 * Utiliza el patrón Singleton de Database para asegurarse de
 * que solo exista UNA conexión activa durante todo el ciclo
 * de vida de la petición.
 * ============================================================
 */
namespace App\Core;

use App\Config\Database;

class Model {

    /**
     * Instancia de la conexión mysqli activa.
     * Todos los modelos que extiendan esta clase tendrán acceso
     * a $this->db para ejecutar consultas preparadas o directas.
     */
    protected $db;

    /**
     * Constructor: obtiene la conexión de la base de datos
     * al instanciar cualquier modelo hijo.
     *
     * Database::getInstance() garantiza que solo se abra
     * una conexión MySQL durante la vida de la petición (Singleton).
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}

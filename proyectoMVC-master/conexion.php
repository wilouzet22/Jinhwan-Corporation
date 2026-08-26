<?php

class baseDatos {
    // Variable estática para almacenar la única instancia de la conexión (Patrón Singleton)
    private static $instancia = null;

    /**
     * Obtiene o crea la instancia única de la conexión a la base de datos con PDO.
     * @return PDO
     */
    public static function crearInstancia() {
        // Si aún no existe una conexión activa, la creamos
        if (!isset(self::$instancia)) {
            
            // 1. Parámetros de configuración de la base de datos
            $servidor = 'localhost';
            $baseDatos = 'basemvc';
            $usuario = 'root';
            $password = '';
            $charset = 'utf8mb4';

            // 2. Cadena de conexión (DSN: Data Source Name)
            $dsn = "mysql:host={$servidor};dbname={$baseDatos};charset={$charset}";

            // 3. Opciones de configuración para PDO
            $opcionesPDO = [
                // Configurar para que lance excepciones cuando ocurra un error SQL
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Devolver los resultados como arrays asociativos por defecto
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Usar consultas preparadas reales en el motor de base de datos
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                // 4. Intentar establecer la conexión
                self::$instancia = new PDO($dsn, $usuario, $password, $opcionesPDO);
            } catch (PDOException $error) {
                // Si ocurre un fallo en la conexión, se captura el error y se muestra un mensaje claro
                die("Error al conectar a la base de datos: " . $error->getMessage());
            }
        }

        // Retornamos la conexión activa
        return self::$instancia;
    }
}
?>
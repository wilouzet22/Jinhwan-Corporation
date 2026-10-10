<?php

class Notificacion extends Model {

    public function __construct() {
        parent::__construct();
        $this->ensureTable();
    }

    /**
     * Asegura que la tabla de notificaciones exista en la base de datos.
     */
    private function ensureTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `notificaciones` (
            `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
            `tipo` varchar(50) NOT NULL DEFAULT 'sistema',
            `titulo` varchar(150) NOT NULL,
            `mensaje` text NOT NULL,
            `enlace` varchar(255) DEFAULT NULL,
            `leida` tinyint(1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id_notificacion`),
            KEY `idx_leida` (`leida`),
            KEY `idx_tipo` (`tipo`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }

    /**
     * Helper estático para registrar una notificación rápidamente desde cualquier controlador.
     */
    public static function registrar(string $tipo, string $titulo, string $mensaje, ?string $enlace = null): bool {
        $instance = new self();
        return $instance->crear($tipo, $titulo, $mensaje, $enlace);
    }

    /**
     * Inserta una nueva notificación.
     */
    public function crear(string $tipo, string $titulo, string $mensaje, ?string $enlace = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO notificaciones (tipo, titulo, mensaje, enlace, leida, created_at)
             VALUES (?, ?, ?, ?, 0, NOW())"
        );
        if (!$stmt) return false;

        $stmt->bind_param("ssss", $tipo, $titulo, $mensaje, $enlace);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Retorna el número de notificaciones no leídas.
     */
    public function getNoLeidasCount(): int {
        $res = $this->db->query("SELECT COUNT(*) as total FROM notificaciones WHERE leida = 0");
        if ($res && $row = $res->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }

    /**
     * Obtiene las notificaciones recientes con opción de filtrar por tipo.
     */
    public function getRecientes(int $limit = 50, ?string $tipo = null): array {
        if ($tipo && $tipo !== 'todas') {
            $stmt = $this->db->prepare(
                "SELECT * FROM notificaciones 
                 WHERE tipo = ? 
                 ORDER BY id_notificacion DESC 
                 LIMIT ?"
            );
            $stmt->bind_param("si", $tipo, $limit);
            $stmt->execute();
            $res = $stmt->get_result();
            $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
            $stmt->close();
            return $rows;
        }

        $res = $this->db->query(
            "SELECT * FROM notificaciones 
             ORDER BY id_notificacion DESC 
             LIMIT " . (int)$limit
        );
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Marca una notificación como leída.
     */
    public function marcarLeida(int $id): bool {
        $stmt = $this->db->prepare("UPDATE notificaciones SET leida = 1 WHERE id_notificacion = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    /**
     * Marca todas las notificaciones como leídas.
     */
    public function marcarTodasLeidas(): bool {
        return (bool)$this->db->query("UPDATE notificaciones SET leida = 1 WHERE leida = 0");
    }

    /**
     * Elimina una notificación específica.
     */
    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM notificaciones WHERE id_notificacion = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

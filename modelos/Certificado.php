<?php

class Certificado {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Crear un nuevo certificado de ascenso */
    public function create(array $data): bool {
        $sql = "INSERT INTO certificados_ascenso"
             . " (id_solicitud, id_persona, id_maestro, grado_anterior, grado_nuevo, fecha_examen, observaciones, folio)"
             . " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("iiisssss",
            $data["id_solicitud"], $data["id_persona"], $data["id_maestro"],
            $data["grado_anterior"], $data["grado_nuevo"],
            $data["fecha_examen"], $data["observaciones"], $data["folio"]
        );
        $r = $stmt->execute();
        $stmt->close();
        return $r;
    }

    /** Obtener todos los certificados de un estudiante */
    public function getByPersona(int $id_persona): array {
        $sql = "SELECT c.*, CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre"
             . " FROM certificados_ascenso c"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " WHERE c.id_persona = ? ORDER BY c.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_persona);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    /** Certificado completo por id de solicitud */
    public function getBySolicitud(int $id_solicitud): ?array {
        $sql = "SELECT c.*,"
             . " CONCAT(p.nombre, ' ', p.apellido) AS alumno_nombre,"
             . " p.num_doc, p.foto_perfil, p.id_sede,"
             . " CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre,"
             . " se.nombre AS nombre_sede"
             . " FROM certificados_ascenso c"
             . " JOIN personas p ON c.id_persona = p.id_persona"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " LEFT JOIN sedes se ON se.id_sede = p.id_sede"
             . " WHERE c.id_solicitud = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id_solicitud);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Certificado por id propio */
    public function getById(int $id): ?array {
        $sql = "SELECT c.*,"
             . " CONCAT(p.nombre, ' ', p.apellido) AS alumno_nombre,"
             . " p.num_doc, p.foto_perfil, p.id_sede,"
             . " CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre,"
             . " se.nombre AS nombre_sede"
             . " FROM certificados_ascenso c"
             . " JOIN personas p ON c.id_persona = p.id_persona"
             . " LEFT JOIN personas m ON c.id_maestro = m.id_persona"
             . " LEFT JOIN sedes se ON se.id_sede = p.id_sede"
             . " WHERE c.id_certificado = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Generar folio unico JH-YYYY-XXXX */
    public static function generarFolio(): string {
        $year = date("Y");
        $db   = Database::getInstance()->getConnection();
        $res  = $db->query("SELECT COUNT(*) AS total FROM certificados_ascenso WHERE YEAR(creado_en) = $year");
        $total = $res ? (int)$res->fetch_assoc()["total"] : 0;
        return sprintf("JH-%s-%04d", $year, $total + 1);
    }
}

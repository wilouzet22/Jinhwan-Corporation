<?php

class Certificado {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Crear un nuevo certificado de ascenso e insertarlo en historial_grados */
    public function create(array $data): bool {
        $id_estudiante = (int)($data["id_estudiante"] ?? ($data["id_persona"] ?? 0));
        $id_maestro    = (int)($data["id_maestro"] ?? 0);
        $grado_anterior = $data["grado_anterior"] ?? '';
        $grado_nuevo    = $data["grado_nuevo"] ?? '';
        $fecha_examen   = $data["fecha_examen"] ?? date('Y-m-d');
        $observaciones  = $data["observaciones"] ?? null;
        $folio          = $data["folio"] ?? self::generarFolio();

        $sql = "INSERT INTO certificados_ascenso (id_estudiante, id_maestro, grado_anterior, grado_nuevo, fecha_examen, observaciones, folio)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("iisssss", $id_estudiante, $id_maestro, $grado_anterior, $grado_nuevo, $fecha_examen, $observaciones, $folio);
        $r = $stmt->execute();
        $id_certificado = $stmt->insert_id;
        $stmt->close();

        if ($r && $id_certificado) {
            // Si viene el ID del nuevo grado, insertar en historial_grados y actualizar estudiante
            $id_grado_nuevo = !empty($data['id_grado_nuevo']) ? (int)$data['id_grado_nuevo'] : null;
            if (!$id_grado_nuevo) {
                $checkG = $this->db->prepare("SELECT id_grado FROM grados WHERE nombre = ? LIMIT 1");
                if ($checkG) {
                    $checkG->bind_param("s", $grado_nuevo);
                    $checkG->execute();
                    $resG = $checkG->get_result()->fetch_assoc();
                    $checkG->close();
                    if ($resG) $id_grado_nuevo = (int)$resG['id_grado'];
                }
            }

            if ($id_grado_nuevo) {
                // 1. Insertar en historial_grados
                $stmtH = $this->db->prepare("INSERT INTO historial_grados (id_estudiante, id_grado, fecha_obtencion, id_certificado) VALUES (?, ?, ?, ?)");
                if ($stmtH) {
                    $stmtH->bind_param("iisi", $id_estudiante, $id_grado_nuevo, $fecha_examen, $id_certificado);
                    $stmtH->execute();
                    $stmtH->close();
                }

                // 2. Actualizar grado actual del estudiante
                $stmtE = $this->db->prepare("UPDATE estudiante SET id_grado = ? WHERE id_estudiante = ?");
                if ($stmtE) {
                    $stmtE->bind_param("ii", $id_grado_nuevo, $id_estudiante);
                    $stmtE->execute();
                    $stmtE->close();
                }
            }
        }

        return $r;
    }

    /** Obtener todos los certificados de un estudiante */
    public function getByEstudiante(int $id_estudiante): array {
        $sql = "SELECT c.*, CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre
                FROM certificados_ascenso c
                LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                WHERE c.id_estudiante = ? 
                ORDER BY c.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_estudiante);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    /** Compatibilidad con llamadas antiguas que usaban getByPersona */
    public function getByPersona(int $id_persona): array {
        return $this->getByEstudiante($id_persona);
    }

    /** Certificado completo por id propio o solicitud */
    public function getBySolicitud(int $id): ?array {
        return $this->getById($id);
    }

    /** Certificado por id propio */
    public function getById(int $id): ?array {
        $sql = "SELECT c.*,
                       CONCAT(e.nombre, ' ', e.apellido) AS alumno_nombre,
                       e.tipo_documento, e.num_doc, e.foto_perfil, gr.id_sede,
                       CONCAT(m.nombre, ' ', m.apellido) AS maestro_nombre,
                       se.nombre AS nombre_sede,
                       c.id_estudiante as id_persona
                FROM certificados_ascenso c
                JOIN estudiante e ON c.id_estudiante = e.id_estudiante
                LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
                LEFT JOIN grupos gr ON e.id_grupo = gr.id_grupo
                LEFT JOIN sedes se ON gr.id_sede = se.id_sede
                WHERE c.id_certificado = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Generar folio único JH-YYYY-XXXX */
    public static function generarFolio(): string {
        $year = date("Y");
        $db   = Database::getInstance()->getConnection();
        $res  = $db->query("SELECT COUNT(*) AS total FROM certificados_ascenso WHERE YEAR(creado_en) = $year");
        $total = $res ? (int)$res->fetch_assoc()["total"] : 0;
        return sprintf("JH-%s-%04d", $year, $total + 1);
    }
}

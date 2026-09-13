<?php

class Grupo extends Model {

    public function getAll() {
        $sql = "SELECT g.id_grupo as id, g.id_grupo, g.nombre, g.descripcion, g.horario, g.activo,
                       s.id_sede, s.nombre as nombre_sede,
                       m.id_maestro, CONCAT(m.nombre, ' ', m.apellido) as nombre_maestro,
                       COUNT(e.id_estudiante) as total_estudiantes
                FROM grupos g
                JOIN sedes s ON g.id_sede = s.id_sede
                LEFT JOIN maestro m ON g.id_maestro = m.id_maestro
                LEFT JOIN estudiante e ON g.id_grupo = e.id_grupo AND e.activo = 1
                GROUP BY g.id_grupo, g.nombre, g.descripcion, g.horario, g.activo, s.id_sede, s.nombre, m.id_maestro, m.nombre, m.apellido
                ORDER BY g.id_grupo ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getBySede(int $id_sede) {
        $stmt = $this->db->prepare("SELECT id_grupo as id, id_grupo, nombre, descripcion, horario, activo 
                                    FROM grupos 
                                    WHERE id_sede = ? AND activo = 1 
                                    ORDER BY id_grupo ASC");
        $stmt->bind_param("i", $id_sede);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT g.*, s.nombre as nombre_sede, CONCAT(m.nombre, ' ', m.apellido) as nombre_maestro
                                    FROM grupos g
                                    JOIN sedes s ON g.id_sede = s.id_sede
                                    LEFT JOIN maestro m ON g.id_maestro = m.id_maestro
                                    WHERE g.id_grupo = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function create(array $data) {
        $stmt = $this->db->prepare("INSERT INTO grupos (id_sede, id_maestro, nombre, descripcion, horario, activo) VALUES (?, ?, ?, ?, ?, ?)");
        $id_sede = (int)$data['id_sede'];
        $id_maestro = !empty($data['id_maestro']) ? (int)$data['id_maestro'] : null;
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'] ?? null;
        $horario = $data['horario'] ?? null;
        $activo = isset($data['activo']) ? (int)$data['activo'] : 1;

        $stmt->bind_param("iisssi", $id_sede, $id_maestro, $nombre, $descripcion, $horario, $activo);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update(int $id, array $data) {
        $stmt = $this->db->prepare("UPDATE grupos SET id_sede = ?, id_maestro = ?, nombre = ?, descripcion = ?, horario = ?, activo = ? WHERE id_grupo = ?");
        $id_sede = (int)$data['id_sede'];
        $id_maestro = !empty($data['id_maestro']) ? (int)$data['id_maestro'] : null;
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'] ?? null;
        $horario = $data['horario'] ?? null;
        $activo = isset($data['activo']) ? (int)$data['activo'] : 1;

        $stmt->bind_param("iisssii", $id_sede, $id_maestro, $nombre, $descripcion, $horario, $activo, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare("DELETE FROM grupos WHERE id_grupo = ?");
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

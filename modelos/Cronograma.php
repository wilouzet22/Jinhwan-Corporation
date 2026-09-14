<?php

require_once __DIR__ . '/../core/Model.php';

class Cronograma extends Model
{
    public function getAll()
    {
        $sql = "
            SELECT c.*, g.nombre as grupo_nombre, s.nombre as grupo_sede, 
                   CONCAT(m.nombre, ' ', m.apellido) as maestro_nombre
            FROM cronogramas_clase c
            LEFT JOIN grupos g ON c.id_grupo = g.id_grupo
            LEFT JOIN sedes s ON g.id_sede = s.id_sede
            LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
            ORDER BY c.fecha DESC, c.id_cronograma DESC
        ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "
            SELECT c.*, g.nombre as grupo_nombre, s.nombre as grupo_sede, 
                   CONCAT(m.nombre, ' ', m.apellido) as maestro_nombre
            FROM cronogramas_clase c
            LEFT JOIN grupos g ON c.id_grupo = g.id_grupo
            LEFT JOIN sedes s ON g.id_sede = s.id_sede
            LEFT JOIN maestro m ON c.id_maestro = m.id_maestro
            WHERE c.id_cronograma = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO cronogramas_clase (id_grupo, id_maestro, fecha, objetivo, observaciones)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) return false;

        $id_grupo = (int)$data['id_grupo'];
        $id_maestro = (int)$data['id_maestro'];
        $fecha = $data['fecha'];
        $objetivo = $data['objetivo'] ?? null;
        $observaciones = $data['observaciones'] ?? null;

        $stmt->bind_param("iisss", $id_grupo, $id_maestro, $fecha, $objetivo, $observaciones);
        $res = $stmt->execute();
        $newId = $stmt->insert_id;
        $stmt->close();

        return $res ? $newId : false;
    }

    public function delete($id)
    {
        $id = (int)$id;
        // Primero eliminar ejercicios vinculados
        $stmtEj = $this->db->prepare("DELETE FROM clase_ejercicios WHERE id_cronograma = ?");
        if ($stmtEj) {
            $stmtEj->bind_param("i", $id);
            $stmtEj->execute();
            $stmtEj->close();
        }

        $stmt = $this->db->prepare("DELETE FROM cronogramas_clase WHERE id_cronograma = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // --- Métodos para clase_ejercicios ---

    public function getEjerciciosByCronograma($id_cronograma)
    {
        $id_cronograma = (int)$id_cronograma;
        $sql = "
            SELECT ce.*, e.nombre as ejercicio_nombre, e.tipo as ejercicio_tipo, e.explicacion as ejercicio_explicacion
            FROM clase_ejercicios ce
            INNER JOIN ejercicios e ON ce.id_ejercicio = e.id_ejercicio
            WHERE ce.id_cronograma = ?
            ORDER BY ce.id_clase_ejercicio ASC
        ";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_cronograma);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function addEjercicio($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO clase_ejercicios (id_cronograma, id_ejercicio, fase, series_o_tiempo, observaciones_especificas)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) return false;

        $id_cronograma = (int)$data['id_cronograma'];
        $id_ejercicio = (int)$data['id_ejercicio'];
        $fase = $data['fase'];
        $series_o_tiempo = $data['series_o_tiempo'] ?? null;
        $observaciones_especificas = $data['observaciones_especificas'] ?? null;

        $stmt->bind_param("iisss", $id_cronograma, $id_ejercicio, $fase, $series_o_tiempo, $observaciones_especificas);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function removeEjercicio($id_clase_ejercicio)
    {
        $id_clase_ejercicio = (int)$id_clase_ejercicio;
        $stmt = $this->db->prepare("DELETE FROM clase_ejercicios WHERE id_clase_ejercicio = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id_clase_ejercicio);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

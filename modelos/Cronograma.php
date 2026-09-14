<?php

require_once __DIR__ . '/../core/Model.php';

class Cronograma extends Model
{
    public function getAll()
    {
        $sql = "
            SELECT c.*, g.nombre as grupo_nombre, g.sede as grupo_sede, u.nombre as maestro_nombre
            FROM cronogramas_clase c
            LEFT JOIN grupos g ON c.id_grupo = g.id_grupo
            LEFT JOIN usuarios u ON c.id_maestro = u.id_usuario
            ORDER BY c.fecha DESC, c.id_cronograma DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
            SELECT c.*, g.nombre as grupo_nombre, g.sede as grupo_sede, u.nombre as maestro_nombre
            FROM cronogramas_clase c
            LEFT JOIN grupos g ON c.id_grupo = g.id_grupo
            LEFT JOIN usuarios u ON c.id_maestro = u.id_usuario
            WHERE c.id_cronograma = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO cronogramas_clase (id_grupo, id_maestro, fecha, objetivo, observaciones)
            VALUES (:id_grupo, :id_maestro, :fecha, :objetivo, :observaciones)
        ");
        $success = $stmt->execute([
            ':id_grupo' => $data['id_grupo'],
            ':id_maestro' => $data['id_maestro'],
            ':fecha' => $data['fecha'],
            ':objetivo' => $data['objetivo'] ?? null,
            ':observaciones' => $data['observaciones'] ?? null
        ]);

        return $success ? $this->db->lastInsertId() : false;
    }

    public function delete($id)
    {
        // Primero eliminar ejercicios asociados
        $stmtEj = $this->db->prepare("DELETE FROM clase_ejercicios WHERE id_cronograma = :id");
        $stmtEj->execute([':id' => $id]);

        $stmt = $this->db->prepare("DELETE FROM cronogramas_clase WHERE id_cronograma = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --- Métodos para clase_ejercicios ---

    public function getEjerciciosByCronograma($id_cronograma)
    {
        $sql = "
            SELECT ce.*, e.nombre as ejercicio_nombre, e.tipo as ejercicio_tipo, e.explicacion as ejercicio_explicacion
            FROM clase_ejercicios ce
            INNER JOIN ejercicios e ON ce.id_ejercicio = e.id_ejercicio
            WHERE ce.id_cronograma = :id_cronograma
            ORDER BY ce.id_clase_ejercicio ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_cronograma' => $id_cronograma]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addEjercicio($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO clase_ejercicios (id_cronograma, id_ejercicio, fase, series_o_tiempo, observaciones_especificas)
            VALUES (:id_cronograma, :id_ejercicio, :fase, :series_o_tiempo, :observaciones_especificas)
        ");
        return $stmt->execute([
            ':id_cronograma' => $data['id_cronograma'],
            ':id_ejercicio' => $data['id_ejercicio'],
            ':fase' => $data['fase'],
            ':series_o_tiempo' => $data['series_o_tiempo'] ?? null,
            ':observaciones_especificas' => $data['observaciones_especificas'] ?? null
        ]);
    }

    public function removeEjercicio($id_clase_ejercicio)
    {
        $stmt = $this->db->prepare("DELETE FROM clase_ejercicios WHERE id_clase_ejercicio = :id");
        return $stmt->execute([':id' => $id_clase_ejercicio]);
    }
}

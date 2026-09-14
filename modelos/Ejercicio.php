<?php

require_once __DIR__ . '/../core/Model.php';

class Ejercicio extends Model
{
    public function getAll()
    {
        $sql = "SELECT * FROM ejercicios ORDER BY tipo ASC, nombre ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById($id)
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM ejercicios WHERE id_ejercicio = ? LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function getByTipo($tipo)
    {
        $stmt = $this->db->prepare("SELECT * FROM ejercicios WHERE tipo = ? ORDER BY nombre ASC");
        if (!$stmt) return [];
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO ejercicios (tipo, nombre, explicacion)
            VALUES (?, ?, ?)
        ");
        if (!$stmt) return false;
        $tipo = $data['tipo'];
        $nombre = $data['nombre'];
        $explicacion = $data['explicacion'] ?? null;
        $stmt->bind_param("sss", $tipo, $nombre, $explicacion);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE ejercicios 
            SET tipo = ?, nombre = ?, explicacion = ?
            WHERE id_ejercicio = ?
        ");
        if (!$stmt) return false;
        $id = (int)$id;
        $tipo = $data['tipo'];
        $nombre = $data['nombre'];
        $explicacion = $data['explicacion'] ?? null;
        $stmt->bind_param("sssi", $tipo, $nombre, $explicacion, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id)
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("DELETE FROM ejercicios WHERE id_ejercicio = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

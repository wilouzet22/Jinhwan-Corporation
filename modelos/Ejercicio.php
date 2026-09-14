<?php

require_once __DIR__ . '/../core/Model.php';

class Ejercicio extends Model
{
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM ejercicios ORDER BY tipo ASC, nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ejercicios WHERE id_ejercicio = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByTipo($tipo)
    {
        $stmt = $this->db->prepare("SELECT * FROM ejercicios WHERE tipo = :tipo ORDER BY nombre ASC");
        $stmt->execute([':tipo' => $tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO ejercicios (tipo, nombre, explicacion)
            VALUES (:tipo, :nombre, :explicacion)
        ");
        return $stmt->execute([
            ':tipo' => $data['tipo'],
            ':nombre' => $data['nombre'],
            ':explicacion' => $data['explicacion'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE ejercicios 
            SET tipo = :tipo, nombre = :nombre, explicacion = :explicacion
            WHERE id_ejercicio = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':tipo' => $data['tipo'],
            ':nombre' => $data['nombre'],
            ':explicacion' => $data['explicacion'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ejercicios WHERE id_ejercicio = :id");
        return $stmt->execute([':id' => $id]);
    }
}

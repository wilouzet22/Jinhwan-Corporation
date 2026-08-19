<?php
namespace App\Models;

use App\Core\Model;

class Sede extends Model {

    public function getAll() {
        $sql = "SELECT s.id_sede as id, s.nombre, s.direccion, s.telefono, s.horario,
                       COUNT(p.id_persona) as numero_estudiantes
                FROM sedes s
                LEFT JOIN personas p ON s.id_sede = p.id_sede
                GROUP BY s.id_sede, s.nombre, s.direccion, s.telefono, s.horario
                ORDER BY s.id_sede ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $horario = $data['horario'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;

        $stmt = $this->db->prepare("INSERT INTO sedes (nombre, direccion, telefono, horario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['nombre'], $direccion, $telefono, $horario);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $horario = $data['horario'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;

        $stmt = $this->db->prepare("UPDATE sedes SET nombre = ?, direccion = ?, telefono = ?, horario = ? WHERE id_sede = ?");
        $stmt->bind_param("ssssi", $data['nombre'], $direccion, $telefono, $horario, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id) {
        $stmtDesvincular = $this->db->prepare("UPDATE personas SET id_sede = NULL WHERE id_sede = ?");
        $stmtDesvincular->bind_param("i", $id);
        $stmtDesvincular->execute();
        $stmtDesvincular->close();

        $stmt = $this->db->prepare("DELETE FROM sedes WHERE id_sede = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}

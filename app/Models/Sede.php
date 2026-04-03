<?php
namespace App\Models;

use App\Core\Model;

class Sede extends Model {
    public function getAll() {
        // Updated to include student count if possible, otherwise simple select
        $sql = "SELECT c.*, COUNT(us.usuario_id) as numero_estudiantes 
                FROM cedes c 
                LEFT JOIN usuario_sede us ON c.id = us.sede_id
                GROUP BY c.id";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO cedes (nombre, direccion, telefono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['nombre'], $data['direccion'], $data['telefono']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE cedes SET nombre = ?, direccion = ?, telefono = ? WHERE id = ?");
        $stmt->bind_param("sssi", $data['nombre'], $data['direccion'], $data['telefono'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM cedes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

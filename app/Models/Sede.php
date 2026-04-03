<?php
namespace App\Models;

use App\Core\Model;

class Sede extends Model {
    public function getAll() {
        // Updated to query 'sedes' instead of 'cedes', generating alias 'numero_estudiantes' and 'direccion', 'telefono'
        $sql = "SELECT s.id_sede as id, s.nombre, s.lugar as direccion, s.horario as telefono, COUNT(m.id_miembro) as numero_estudiantes 
                FROM sedes s 
                LEFT JOIN miembros m ON s.id_sede = m.id_sede
                GROUP BY s.id_sede";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO sedes (nombre, lugar, horario) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['nombre'], $data['direccion'], $data['telefono']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE sedes SET nombre = ?, lugar = ?, horario = ? WHERE id_sede = ?");
        $stmt->bind_param("sssi", $data['nombre'], $data['direccion'], $data['telefono'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM sedes WHERE id_sede = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

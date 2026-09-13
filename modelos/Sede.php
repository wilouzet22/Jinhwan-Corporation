<?php

class Sede extends Model {

    public function getAll() {
        $sql = "SELECT s.id_sede as id, s.id_sede, s.nombre, s.direccion, s.telefono, s.horario, s.activo,
                       COUNT(DISTINCT e.id_estudiante) as numero_estudiantes,
                       COUNT(DISTINCT g.id_grupo) as numero_grupos
                FROM sedes s
                LEFT JOIN grupos g ON s.id_sede = g.id_sede
                LEFT JOIN estudiante e ON g.id_grupo = e.id_grupo AND e.activo = 1
                GROUP BY s.id_sede, s.nombre, s.direccion, s.telefono, s.horario, s.activo
                ORDER BY s.id_sede ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $horario = $data['horario'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;
        $email = $data['email'] ?? null;

        $stmt = $this->db->prepare("INSERT INTO sedes (nombre, direccion, telefono, email, horario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['nombre'], $direccion, $telefono, $email, $horario);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $horario = $data['horario'] ?? null;
        $telefono = $data['telefono'] ?? null;
        $direccion = $data['direccion'] ?? null;
        $email = $data['email'] ?? null;

        $stmt = $this->db->prepare("UPDATE sedes SET nombre = ?, direccion = ?, telefono = ?, email = ?, horario = ? WHERE id_sede = ?");
        $stmt->bind_param("sssssi", $data['nombre'], $direccion, $telefono, $email, $horario, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id) {
        // Desvincular maestros de esta sede
        $stmtM = $this->db->prepare("UPDATE maestro SET id_sede = NULL WHERE id_sede = ?");
        $stmtM->bind_param("i", $id);
        $stmtM->execute();
        $stmtM->close();

        // Eliminar sede
        $stmt = $this->db->prepare("DELETE FROM sedes WHERE id_sede = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}

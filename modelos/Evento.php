<?php

class Evento extends Model {

    public function getAll() {
        $sql = "SELECT id_evento as id, titulo as title, descripcion as description, fecha_inicio as start, fecha_fin as end, id_persona 
                FROM eventos";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getUpcoming($limit = 5) {
        $sql = "SELECT id_evento as id, titulo as title, descripcion as description, fecha_inicio as start, fecha_fin as end, id_persona 
                FROM eventos 
                WHERE fecha_inicio >= CURRENT_DATE() 
                ORDER BY fecha_inicio ASC 
                LIMIT ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function create($data) {
        $fecha_fin = !empty($data['end']) ? $data['end'] : null;
        $id_persona = $data['id_persona'] ?? ($_SESSION['id'] ?? 1);
        
        $stmt = $this->db->prepare("INSERT INTO eventos (titulo, descripcion, fecha_inicio, fecha_fin, id_persona) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['title'], $data['description'], $data['start'], $fecha_fin, $id_persona);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $fecha_fin = !empty($data['end']) ? $data['end'] : null;
        
        $stmt = $this->db->prepare("UPDATE eventos SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_evento = ?");
        $stmt->bind_param("ssssi", $data['title'], $data['description'], $data['start'], $fecha_fin, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}

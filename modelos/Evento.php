<?php

class Evento extends Model {

    public function getAll() {
        $sql = "SELECT e.id_evento as id, e.titulo as title, e.descripcion as description,
                       e.fecha_inicio as start, e.fecha_fin as end,
                       e.tipo, e.color, e.todo_dia as allDay,
                       e.id_maestro, e.id_sede,
                       s.nombre as nombre_sede,
                       CONCAT(m.nombre, ' ', m.apellido) as nombre_maestro
                FROM eventos e
                LEFT JOIN sedes s ON e.id_sede = s.id_sede
                LEFT JOIN maestro m ON e.id_maestro = m.id_maestro
                ORDER BY e.fecha_inicio ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getUpcoming($limit = 5) {
        $sql = "SELECT e.id_evento as id, e.titulo as title, e.descripcion as description,
                       e.fecha_inicio as start, e.fecha_fin as end,
                       e.tipo, e.color, e.todo_dia as allDay,
                       s.nombre as nombre_sede
                FROM eventos e 
                LEFT JOIN sedes s ON e.id_sede = s.id_sede
                WHERE e.fecha_inicio >= CURRENT_DATE() 
                ORDER BY e.fecha_inicio ASC 
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
        $titulo       = $data['title'] ?? ($data['titulo'] ?? '');
        $descripcion  = $data['description'] ?? ($data['descripcion'] ?? null);
        $fecha_inicio = $data['start'] ?? ($data['fecha_inicio'] ?? date('Y-m-d H:i:s'));
        $fecha_fin    = !empty($data['end']) ? $data['end'] : (!empty($data['fecha_fin']) ? $data['fecha_fin'] : null);
        
        $id_maestro   = !empty($data['id_maestro']) ? (int)$data['id_maestro'] : null;
        if (!$id_maestro && !empty($_SESSION['rol_id']) && Roles::esMaestro($_SESSION['rol_id'])) {
            $id_maestro = (int)$_SESSION['id'];
        }

        $id_sede  = !empty($data['id_sede']) ? (int)$data['id_sede'] : null;
        $tipo     = $data['tipo'] ?? 'entrenamiento';
        $color    = $data['color'] ?? '#3b82f6';
        $todo_dia = isset($data['todo_dia']) ? (int)$data['todo_dia'] : 0;
        
        $stmt = $this->db->prepare("INSERT INTO eventos (id_maestro, id_sede, titulo, descripcion, tipo, color, fecha_inicio, fecha_fin, todo_dia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssssi", $id_maestro, $id_sede, $titulo, $descripcion, $tipo, $color, $fecha_inicio, $fecha_fin, $todo_dia);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $id           = (int)$id;
        $titulo       = $data['title'] ?? ($data['titulo'] ?? '');
        $descripcion  = $data['description'] ?? ($data['descripcion'] ?? null);
        $fecha_inicio = $data['start'] ?? ($data['fecha_inicio'] ?? date('Y-m-d H:i:s'));
        $fecha_fin    = !empty($data['end']) ? $data['end'] : (!empty($data['fecha_fin']) ? $data['fecha_fin'] : null);
        $id_sede      = !empty($data['id_sede']) ? (int)$data['id_sede'] : null;
        $tipo         = $data['tipo'] ?? 'entrenamiento';
        $color        = $data['color'] ?? '#3b82f6';
        
        $stmt = $this->db->prepare("UPDATE eventos SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, id_sede = ?, tipo = ?, color = ? WHERE id_evento = ?");
        $stmt->bind_param("ssssissi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $id_sede, $tipo, $color, $id);
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

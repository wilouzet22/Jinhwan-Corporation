<?php
namespace App\Models;

use App\Core\Model;

class Teoria extends Model {
    
    public function getAll() {
        // Updated to include level name
        $sql = "SELECT t.*, n.nombre as nivel_nombre, n.orden 
                FROM teoria_galeria t 
                LEFT JOIN niveles n ON t.nivel_id = n.id 
                ORDER BY n.orden ASC, t.id ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO teoria_galeria (titulo, descripcion, url_video, nivel_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE teoria_galeria SET titulo = ?, descripcion = ?, url_video = ?, nivel_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teoria_galeria WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // New methods for Student Favorites
    public function getFavorites($usuario_id) {
        $ids = [];
        $stmt = $this->db->prepare("SELECT teoria_id FROM usuario_teoria_personal WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $ids[] = $row['teoria_id'];
        }
        return $ids;
    }

    public function toggleFavorite($usuario_id, $teoria_id) {
        // Check if exists
        $stmt = $this->db->prepare("SELECT id FROM usuario_teoria_personal WHERE usuario_id = ? AND teoria_id = ?");
        $stmt->bind_param("ii", $usuario_id, $teoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Remove
            $del = $this->db->prepare("DELETE FROM usuario_teoria_personal WHERE usuario_id = ? AND teoria_id = ?");
            $del->bind_param("ii", $usuario_id, $teoria_id);
            return $del->execute() ? 'removed' : 'error';
        } else {
            // Add
            $add = $this->db->prepare("INSERT INTO usuario_teoria_personal (usuario_id, teoria_id) VALUES (?, ?)");
            $add->bind_param("ii", $usuario_id, $teoria_id);
            return $add->execute() ? 'added' : 'error';
        }
    }
}

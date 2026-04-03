<?php
namespace App\Models;

use App\Core\Model;

class Teoria extends Model {
    
    public function getAll() {
        // Updated to include level name and mapping fields to new schema (teoria)
        $sql = "SELECT t.id_teoria as id, t.nombre as titulo, t.contenido as descripcion, '' as url_video, t.id_grado as nivel_id, 
                       g.nombre as nivel_nombre, g.id_grado as orden 
                FROM teoria t 
                LEFT JOIN grados g ON t.id_grado = g.id_grado 
                ORDER BY g.id_grado ASC, t.id_teoria ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $tipo_defecto = 1;
        $stmt = $this->db->prepare("INSERT INTO teoria (nombre, contenido, id_grado, id_tipo_de_t) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $data['titulo'], $data['descripcion'], $data['nivel_id'], $tipo_defecto);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE teoria SET nombre = ?, contenido = ?, id_grado = ? WHERE id_teoria = ?");
        $stmt->bind_param("ssii", $data['titulo'], $data['descripcion'], $data['nivel_id'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teoria WHERE id_teoria = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // New methods for Student Favorites
    public function getFavorites($usuario_id) {
        // Favorite functionality disabled as the table was removed in new DB schema
        return [];
    }

    public function toggleFavorite($usuario_id, $teoria_id) {
        // Favorite functionality disabled
        return 'removed';
    }
}

<?php
namespace App\Models;

use App\Core\Model;

class Teoria extends Model {

    public function getAll() {
        $sql = "SELECT t.id_teoria as id, t.nombre as titulo, t.contenido as descripcion, t.url_video, t.id_grado as nivel_id,
                       g.nombre as nivel_nombre, g.id_grado as orden
                FROM teorias t
                LEFT JOIN grados g ON t.id_grado = g.id_grado
                ORDER BY g.id_grado ASC, t.id_teoria ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $tipo_defecto = 1; 

        $stmt = $this->db->prepare("INSERT INTO teorias (nombre, contenido, url_video, id_grado, id_tipo_teoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $tipo_defecto);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE teorias SET nombre = ?, contenido = ?, url_video = ?, id_grado = ? WHERE id_teoria = ?");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teorias WHERE id_teoria = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function getFavorites($usuario_id) {
        return [];
    }

    public function toggleFavorite($usuario_id, $teoria_id) {
        return 'removed';
    }
}

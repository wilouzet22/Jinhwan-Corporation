<?php

class Teoria extends Model {

    public function getTipos() {
        $result = $this->db->query("SELECT id_tipo_teoria as id, nombre FROM tipos_teoria ORDER BY id_tipo_teoria ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAll() {
        $sql = "SELECT t.id_teoria as id, t.nombre as titulo, t.contenido as descripcion, t.url_video,
                       t.id_grado as nivel_id, g.nombre as nivel_nombre, g.id_grado as orden,
                       t.id_tipo_teoria as tipo_id, tt.nombre as tipo_nombre
                FROM teorias t
                LEFT JOIN grados g ON t.id_grado = g.id_grado
                LEFT JOIN tipos_teoria tt ON t.id_tipo_teoria = tt.id_tipo_teoria
                ORDER BY t.id_tipo_teoria ASC, g.id_grado ASC, t.id_teoria ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $tipo_id = (int)($data['tipo_id'] ?? 1);

        $stmt = $this->db->prepare("INSERT INTO teorias (nombre, contenido, url_video, id_grado, id_tipo_teoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $tipo_id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $tipo_id = (int)($data['tipo_id'] ?? 1);

        $stmt = $this->db->prepare("UPDATE teorias SET nombre = ?, contenido = ?, url_video = ?, id_grado = ?, id_tipo_teoria = ? WHERE id_teoria = ?");
        $stmt->bind_param("sssiii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $tipo_id, $id);
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

    private function ensureFavoritosTable() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `teoria_favoritos` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `id_estudiante` INT NOT NULL,
                `id_teoria` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY `uq_fav` (`id_estudiante`, `id_teoria`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function getFavorites($usuario_id) {
        $this->ensureFavoritosTable();
        $stmt = $this->db->prepare(
            "SELECT id_teoria FROM teoria_favoritos WHERE id_estudiante = ?"
        );
        if (!$stmt) return [];
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return array_column($rows, 'id_teoria');
    }

    public function toggleFavorite($usuario_id, $teoria_id) {
        $this->ensureFavoritosTable();
        // Verificar si ya existe
        $stmt = $this->db->prepare(
            "SELECT id FROM teoria_favoritos WHERE id_estudiante = ? AND id_teoria = ?"
        );
        $stmt->bind_param('ii', $usuario_id, $teoria_id);
        $stmt->execute();
        $existe = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($existe) {
            $del = $this->db->prepare(
                "DELETE FROM teoria_favoritos WHERE id_estudiante = ? AND id_teoria = ?"
            );
            $del->bind_param('ii', $usuario_id, $teoria_id);
            $del->execute();
            $del->close();
            return 'removed';
        } else {
            $ins = $this->db->prepare(
                "INSERT INTO teoria_favoritos (id_estudiante, id_teoria) VALUES (?, ?)"
            );
            $ins->bind_param('ii', $usuario_id, $teoria_id);
            $ins->execute();
            $ins->close();
            return 'added';
        }
    }
}

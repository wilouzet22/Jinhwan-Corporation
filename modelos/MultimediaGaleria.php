<?php

class MultimediaGaleria extends Model {

    public function getByMaestroId($id_maestro) {
        $stmt = $this->db->prepare("SELECT * FROM galeria_multimedia WHERE id_maestro = ? AND tipo = 'instagram' LIMIT 1");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    /** Compatibilidad con llamadas antiguas que usaban getByMiembroId */
    public function getByMiembroId($id_persona) {
        return $this->getByMaestroId($id_persona);
    }

    public function upsertInstagram($id_maestro, $url) {
        $existente = $this->getByMaestroId($id_maestro);
        
        if ($existente) {
            $stmt = $this->db->prepare("UPDATE galeria_multimedia SET url = ? WHERE id_multimedia = ?");
            $stmt->bind_param("si", $url, $existente['id_multimedia']);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } else {
            $titulo = "Instagram Perfil";
            $tipo = "instagram";
            $stmt = $this->db->prepare("INSERT INTO galeria_multimedia (id_maestro, titulo, url, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_maestro, $titulo, $url, $tipo);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
    }

    public function upsert($id_persona, $url) {
        return $this->upsertInstagram($id_persona, $url);
    }

    public function getAllGeneral() {
        $sql = "SELECT gm.*, CONCAT(m.nombre, ' ', m.apellido) as maestro_nombre
                FROM galeria_multimedia gm
                LEFT JOIN maestro m ON gm.id_maestro = m.id_maestro
                ORDER BY gm.id_multimedia DESC";
        $stmt = $this->db->query($sql);
        return $stmt ? $stmt->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insertGeneral($url, $descripcion, $titulo = 'Publicación en Galería', $tipo = 'instagram', $id_maestro = null) {
        $stmt = $this->db->prepare("INSERT INTO galeria_multimedia (id_maestro, titulo, descripcion, tipo, url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_maestro, $titulo, $descripcion, $tipo, $url);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id_multimedia) {
        $stmt = $this->db->prepare("DELETE FROM galeria_multimedia WHERE id_multimedia = ?");
        $stmt->bind_param("i", $id_multimedia);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

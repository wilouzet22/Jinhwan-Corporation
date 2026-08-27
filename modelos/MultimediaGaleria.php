<?php

class MultimediaGaleria extends Model {

    public function getByMiembroId($id_persona) {
        $sql = "SELECT * FROM galeria_multimedia WHERE id_persona = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_persona);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function upsert($id_persona, $url) {
        $existente = $this->getByMiembroId($id_persona);
        
        if ($existente) {
            $sql = "UPDATE galeria_multimedia SET url = ? WHERE id_persona = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $url, $id_persona);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } else {
            $sql = "INSERT INTO galeria_multimedia (id_persona, url) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("is", $id_persona, $url);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
    }

    public function getAllGeneral() {
        $sql = "SELECT * FROM galeria_multimedia WHERE id_persona IS NULL ORDER BY id_multimedia DESC";
        $stmt = $this->db->query($sql);
        return $stmt ? $stmt->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insertGeneral($url_instagram, $descripcion) {
        $sql = "INSERT INTO galeria_multimedia (url, descripcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $url_instagram, $descripcion);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id_multimedia) {
        $sql = "DELETE FROM galeria_multimedia WHERE id_multimedia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_multimedia);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

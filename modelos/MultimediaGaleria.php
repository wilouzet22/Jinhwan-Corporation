<?php
/**
 * ============================================================
 * MODELO DE MULTIMEDIA GALERIA (MultimediaGaleria)
 * ============================================================
 * Gestiona los enlaces multimedia de los miembros (ej. YouTube)
 * y también la galería general de Instagram del club.
 * ============================================================
 */
namespace App\Models;

use App\Core\Model;

class MultimediaGaleria extends Model {

    /**
     * Obtiene el registro multimedia de un miembro.
     * @param int $id_miembro
     * @return array|null
     */
    public function getByMiembroId($id_miembro) {
        $sql = "SELECT * FROM multimedia_galeria WHERE id_miembro = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Inserta o actualiza el enlace multimedia de un miembro.
     * @param int $id_miembro
     * @param string $url
     * @return bool
     */
    public function upsert($id_miembro, $url) {
        $existente = $this->getByMiembroId($id_miembro);
        
        if ($existente) {
            $sql = "UPDATE multimedia_galeria SET url = ? WHERE id_miembro = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $url, $id_miembro);
            return $stmt->execute();
        } else {
            $sql = "INSERT INTO multimedia_galeria (id_miembro, url) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("is", $id_miembro, $url);
            return $stmt->execute();
        }
    }

    /**
     * ==================================================
     * MÉTODOS PARA GALERÍA GENERAL (INSTAGRAM)
     * ==================================================
     */

    /**
     * Obtiene todas las publicaciones generales (donde id_miembro es NULL).
     * @return array
     */
    public function getAllGeneral() {
        $sql = "SELECT * FROM multimedia_galeria WHERE id_miembro IS NULL ORDER BY id_multimedia DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Inserta una nueva publicación en la galería general.
     * @param string $url_instagram
     * @param string $descripcion
     * @return bool
     */
    public function insertGeneral($url_instagram, $descripcion) {
        $sql = "INSERT INTO multimedia_galeria (url, descripcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $url_instagram, $descripcion);
        return $stmt->execute();
    }

    /**
     * Elimina una publicación multimedia por su ID.
     * @param int $id_multimedia
     * @return bool
     */
    public function delete($id_multimedia) {
        $sql = "DELETE FROM multimedia_galeria WHERE id_multimedia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_multimedia);
        return $stmt->execute();
    }
}

<?php
/**
 * ============================================================
 * MODELO DE MULTIMEDIA GALERIA (MultimediaGaleria)
 * ============================================================
 * Gestiona los enlaces multimedia de los miembros (ej. Instagram)
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
        // Verifica si ya existe
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
}

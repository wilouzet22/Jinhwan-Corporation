<?php
namespace App\Models;

use App\Core\Model;

class Nivel extends Model {


    /**
     * Obtiene todos los niveles/grados disponibles ordenados de menor a mayor.
     *
     * Se usa principalmente para llenar selects (dropdowns) en los formularios
     * de creación/edición de miembros y teorías.
     *
     * @return array Lista de grados con 'id' y 'nombre'
     *               Ejemplo: [['id' => 1, 'nombre' => 'Blanco'], ...]
     */
    public function getAll() {
        // Alias: id_grado → 'id' para uniformidad con el resto de modelos
        $result = $this->db->query("SELECT id_grado AS id, nombre FROM grados ORDER BY id_grado ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

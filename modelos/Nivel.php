<?php
namespace App\Models;

use App\Core\Model;

class Nivel extends Model {

    public function getAll() {
        
        $result = $this->db->query("SELECT id_grado AS id, nombre FROM grados ORDER BY id_grado ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

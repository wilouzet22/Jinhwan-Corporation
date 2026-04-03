<?php
namespace App\Models;

use App\Core\Model;

class Nivel extends Model {
    public function getAll() {
        $result = $this->db->query("SELECT * FROM niveles ORDER BY orden ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

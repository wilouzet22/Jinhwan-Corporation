<?php
namespace App\Models;

use App\Core\Model;

class Categoria extends Model {

    public function getAll() {
        $result = $this->db->query("SELECT id_categoria AS id, nombre, descripcion FROM categorias ORDER BY id_categoria ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}

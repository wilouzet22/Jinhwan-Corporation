<?php

class Nivel extends Model {

    public function getAll() {
        $result = $this->db->query("SELECT id_grado AS id, nombre FROM grados ORDER BY id_grado ASC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}

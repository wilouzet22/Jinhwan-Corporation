<?php
/**
 * ============================================================
 * MODELO DE CATEGORÍA (Categoria)
 * ============================================================
 * Gestiona la tabla 'categoria' de la base de datos.
 * Define la categoría de edad del miembro (Pre-benjamín, Cadete, etc.).
 * ============================================================
 */
namespace App\Models;

use App\Core\Model;

class Categoria extends Model {

    /**
     * Obtiene todas las categorías disponibles ordenadas de menor a mayor edad.
     *
     * @return array Lista de categorías con 'id', 'nombre' y 'descripcion'
     */
    public function getAll() {
        $result = $this->db->query("SELECT id_categoria AS id, nombre, descripcion FROM categoria ORDER BY id_categoria ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

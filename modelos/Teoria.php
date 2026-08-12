<?php
namespace App\Models;

use App\Core\Model;


class Teoria extends Model {

    /**
     * Obtiene todos los temas teóricos con su información de nivel.
     *
     * Realiza JOIN con 'grados' para incluir el nombre del nivel
     * al que pertenece cada tema.
     *
     * Ordenado por: grado ASC (menor nivel primero), luego por id_teoria ASC.
     * Esto permite agrupar el contenido por cinturón de forma ordenada.
     *
     * Alias devueltos por compatibilidad con las vistas:
     *   - t.nombre    → 'titulo'
     *   - t.contenido → 'descripcion'
     *   - '' (vacío)  → 'url_video' (campo desactivado en el nuevo esquema)
     *   - t.id_grado  → 'nivel_id'
     *   - g.nombre    → 'nivel_nombre'
     *   - g.id_grado  → 'orden'
     *
     * @return array Lista de teorías como arrays asociativos
     */
    public function getAll() {
        $sql = "SELECT t.id_teoria as id, t.nombre as titulo, t.contenido as descripcion, t.url_video, t.id_grado as nivel_id,
                       g.nombre as nivel_nombre, g.id_grado as orden
                FROM teoria t
                LEFT JOIN grados g ON t.id_grado = g.id_grado
                ORDER BY g.id_grado ASC, t.id_teoria ASC";

        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo tema teórico en la base de datos.
     *
     * El campo 'id_tipo_de_t' se fija en 1 (tipo por defecto) ya que
     * actualmente no hay selección de tipo en la interfaz.
     *
     * @param  array $data Datos del tema:
     *                     'titulo'      → nombre del tema
     *                     'descripcion' → contenido explicativo
     *                     'nivel_id'    → ID del grado al que pertenece
     * @return bool  true si se insertó correctamente
     */
    public function create($data) {
        $tipo_defecto = 1; // Tipo de teoría por defecto (sin selección en UI)

        $stmt = $this->db->prepare("INSERT INTO teoria (nombre, contenido, url_video, id_grado, id_tipo_de_t) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $tipo_defecto);
        return $stmt->execute();
    }

    /**
     * Actualiza un tema teórico existente.
     *
     * @param  int   $id   ID del tema a modificar
     * @param  array $data Nuevos datos del tema (titulo, descripcion, nivel_id)
     * @return bool  true si se actualizó correctamente
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE teoria SET nombre = ?, contenido = ?, url_video = ?, id_grado = ? WHERE id_teoria = ?");
        $stmt->bind_param("sssii", $data['titulo'], $data['descripcion'], $data['url_video'], $data['nivel_id'], $id);
        return $stmt->execute();
    }

    /**
     * Elimina un tema teórico por su ID.
     *
     * @param  int  $id ID del tema a eliminar
     * @return bool true si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM teoria WHERE id_teoria = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * [DESHABILITADO] Obtiene los IDs de teorías marcadas como favoritas por un usuario.
     *
     * Esta funcionalidad fue desactivada al migrar al nuevo esquema de base de datos
     * (la tabla de favoritos fue eliminada). Se mantiene el método para evitar romper
     * el código que lo llama en EstudioController.
     *
     * @param  int   $usuario_id ID del usuario (no usado)
     * @return array Siempre retorna array vacío []
     */
    public function getFavorites($usuario_id) {
        // Funcionalidad deshabilitada: la tabla de favoritos no existe en el nuevo esquema
        return [];
    }

    /**
     * [DESHABILITADO] Agrega o quita una teoría de los favoritos de un usuario.
     *
     * Idem que getFavorites: deshabilitado por migración de esquema.
     * Se mantiene por compatibilidad con el endpoint AJAX /ascensos/toggle.
     *
     * @param  int    $usuario_id ID del usuario (no usado)
     * @param  int    $teoria_id  ID de la teoría (no usado)
     * @return string Siempre retorna 'removed'
     */
    public function toggleFavorite($usuario_id, $teoria_id) {
        // Funcionalidad deshabilitada
        return 'removed';
    }
}

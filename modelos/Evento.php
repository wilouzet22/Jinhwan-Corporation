<?php
namespace App\Models;

use App\Core\Model;

class Evento extends Model {


    /**
     * Obtiene todos los eventos.
     *
     * @return array Lista de eventos
     */
    public function getAll() {
        $sql = "SELECT id_evento as id, titulo as title, descripcion as description, fecha_inicio as start, fecha_fin as end, id_miembro 
                FROM eventos";

        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los próximos eventos a partir de la fecha actual.
     *
     * @param int $limit Número máximo de eventos a devolver
     * @return array Lista de eventos
     */
    public function getUpcoming($limit = 5) {
        $sql = "SELECT id_evento as id, titulo as title, descripcion as description, fecha_inicio as start, fecha_fin as end, id_miembro 
                FROM eventos 
                WHERE fecha_inicio >= CURRENT_DATE() 
                ORDER BY fecha_inicio ASC 
                LIMIT ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo evento en la base de datos.
     *
     * @param  array $data Datos del evento
     * @return bool  true si se insertó correctamente
     */
    public function create($data) {
        // Asignamos fecha_fin igual a fecha_inicio si no se provee, o null
        $fecha_fin = !empty($data['end']) ? $data['end'] : null;
        
        $stmt = $this->db->prepare("INSERT INTO eventos (titulo, descripcion, fecha_inicio, fecha_fin, id_miembro) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['title'], $data['description'], $data['start'], $fecha_fin, $data['id_miembro']);
        return $stmt->execute();
    }

    /**
     * Actualiza los datos de un evento existente.
     *
     * @param  int   $id   ID del evento a modificar
     * @param  array $data Nuevos datos del evento
     * @return bool  true si se actualizó correctamente
     */
    public function update($id, $data) {
        $fecha_fin = !empty($data['end']) ? $data['end'] : null;
        
        $stmt = $this->db->prepare("UPDATE eventos SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_evento = ?");
        $stmt->bind_param("ssssi", $data['title'], $data['description'], $data['start'], $fecha_fin, $id);
        return $stmt->execute();
    }

    /**
     * Elimina un evento.
     *
     * @param  int  $id ID del evento a eliminar
     * @return bool true si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}

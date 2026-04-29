<?php
/**
 * ============================================================
 * MODELO DE SEDE (Sede)
 * ============================================================
 * Gestiona la tabla 'sedes' de la base de datos.
 * Una sede es una sede física del club de Taekwondo donde
 * se realizan los entrenamientos.
 *
 * Campos relevantes en 'sedes':
 *   - id_sede  → clave primaria
 *   - nombre   → nombre de la sede
 *   - lugar    → dirección física (mapeado como 'direccion')
 *   - horario  → horario de atención (mapeado como 'telefono' por alias)
 * ============================================================
 */
namespace App\Models;

use App\Core\Model;

class Sede extends Model {

    /**
     * Obtiene todas las sedes con el conteo de miembros de cada una.
     *
     * Realiza un LEFT JOIN con 'miembros' para incluir sedes con 0 miembros.
     * Usa GROUP BY para agregar el conteo por sede.
     *
     * Alias usados por compatibilidad con las vistas:
     *   - s.lugar    → 'direccion'
     *   - s.horario  → 'telefono' (se muestra como información de contacto)
     *   - COUNT(...)  → 'numero_estudiantes'
     *
     * @return array Lista de sedes con su conteo de miembros
     */
    public function getAll() {
        // Nota: el campo 'horario' se devuelve como alias 'telefono'
        // por compatibilidad con las vistas del proyecto.
        $sql = "SELECT s.id_sede as id, s.nombre, s.lugar as direccion, s.horario as telefono, COUNT(m.id_miembro) as numero_estudiantes
                FROM sedes s
                LEFT JOIN miembros m ON s.id_sede = m.id_sede
                GROUP BY s.id_sede";

        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea una nueva sede en la base de datos.
     *
     * @param  array $data Datos de la sede:
     *                     'nombre'    → nombre de la sede
     *                     'direccion' → dirección física (campo 'lugar' en BD)
     *                     'telefono'  → horario/info de contacto (campo 'horario' en BD)
     * @return bool  true si se insertó correctamente
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO sedes (nombre, lugar, horario) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['nombre'], $data['direccion'], $data['telefono']);
        return $stmt->execute();
    }

    /**
     * Actualiza los datos de una sede existente.
     *
     * @param  int   $id   ID de la sede a modificar
     * @param  array $data Nuevos datos de la sede (misma estructura que create)
     * @return bool  true si se actualizó correctamente
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE sedes SET nombre = ?, lugar = ?, horario = ? WHERE id_sede = ?");
        $stmt->bind_param("sssi", $data['nombre'], $data['direccion'], $data['telefono'], $id);
        return $stmt->execute();
    }

    /**
     * Elimina una sede de forma segura en DOS pasos:
     *
     * Paso 1 – Desvincular miembros: actualiza todos los miembros
     *          que pertenecían a esta sede, dejando su id_sede = NULL.
     *          Esto evita violar la integridad referencial.
     *
     * Paso 2 – Eliminar sede: con todos los miembros desvinculados,
     *          ahora es seguro borrar el registro de la sede.
     *
     * @param  int  $id ID de la sede a eliminar
     * @return bool true si se eliminó correctamente
     */
    public function delete($id) {
        // Paso 1: Desvincular miembros para evitar errores de FK
        $stmtDesvincular = $this->db->prepare("UPDATE miembros SET id_sede = NULL WHERE id_sede = ?");
        $stmtDesvincular->bind_param("i", $id);
        $stmtDesvincular->execute();
        $stmtDesvincular->close();

        // Paso 2: Eliminar la sede ya que no tiene miembros vinculados
        $stmt = $this->db->prepare("DELETE FROM sedes WHERE id_sede = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }
}

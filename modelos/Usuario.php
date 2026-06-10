<?php
/**
 * ============================================================
 * MODELO DE USUARIO (Usuario)
 * ============================================================
 * Gestiona toda la interacción con la base de datos para el
 * manejo de miembros del club de Taekwondo.
 *
 * Tablas que utiliza:
 *   - miembros  → datos personales del miembro (nombre, grado, sede, etc.)
 *   - userlog   → credenciales de acceso (correo + contraseña hasheada)
 *   - sedes     → sede a la que pertenece el miembro
 *   - grados    → nivel/cinturón del miembro
 *
 * Operaciones: listar todos, buscar por ID, crear, actualizar, eliminar.
 * ============================================================
 */
namespace App\Models;

use App\Core\Model;

class Usuario extends Model {

    /**
     * Obtiene todos los miembros con sus detalles completos.
     *
     * Realiza JOINs para obtener:
     *   - Nombre de la sede (sedes.nombre → nombre_cede)
     *   - Nombre del nivel/grado (grados.nombre → nombre_nivel)
     *   - Correo del usuario (userlog.correo)
     *
     * Ordenado por: rol ASC, nombre ASC
     * (Admins primero, luego maestros, luego estudiantes, y dentro de
     *  cada grupo en orden alfabético)
     *
     * @return array Lista de miembros como arrays asociativos
     */
    public function getAllWithDetails() {
        $sql = "SELECT m.id_miembro as id, m.rol as rol_id, m.id_grado as nivel_id,
                       m.nombre, m.apellido, 'CC' as tipo_documento, m.num_doc as numero_documento,
                       m.fecha_n as fecha_nacimiento, m.peso, NULL as categoria, m.telefono, m.activo,
                       u.correo, u.clave,
                       s.nombre as nombre_cede, s.id_sede as cede_id,
                       g.nombre as nombre_nivel
                FROM miembros m
                LEFT JOIN sedes s ON m.id_sede = s.id_sede
                LEFT JOIN grados g ON m.id_grado = g.id_grado
                LEFT JOIN userlog u ON m.id_miembro = u.id_miembro
                ORDER BY m.rol ASC, m.nombre ASC";

        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Crea un nuevo miembro junto con sus credenciales de acceso.
     *
     * Proceso de dos pasos:
     *   1. Insertar en 'miembros' (activo = 1 por defecto cuando el admin crea el usuario)
     *   2. Si se proporcionó correo, insertar en 'userlog' con la clave
     *      hasheada usando bcrypt (PASSWORD_DEFAULT).
     *      La clave inicial es el número de documento del miembro.
     *   3. Si se especificó una sede, asignarla mediante assignSede().
     *
     * @param  array $data Datos del miembro: nombre, apellido, numero_documento,
     *                     fecha_nacimiento, nivel_id, telefono, rol_id, correo, cede_id
     * @return bool  true si se creó correctamente, false en caso de error
     */
    public function create($data) {
        // La contraseña inicial del miembro es su número de documento hasheado
        $clave = password_hash($data['numero_documento'], PASSWORD_DEFAULT);

        // Insertar el miembro con activo = 1 (activo inmediatamente por el admin)
        $sql = "INSERT INTO miembros (nombre, apellido, num_doc, fecha_n, id_grado, telefono, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssisi",
            $data['nombre'],
            $data['apellido'],
            $data['numero_documento'],
            $data['fecha_nacimiento'],
            $data['nivel_id'],
            $data['telefono'],
            $data['rol_id']
        );

        if ($stmt->execute()) {
            // Obtener el ID generado automáticamente para el nuevo miembro
            $usuario_id = $stmt->insert_id;

            // Si se proporcionó correo, crear las credenciales de acceso en userlog
            if (!empty($data['correo'])) {
                $sqlLog = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)";
                $stmtLog = $this->db->prepare($sqlLog);
                $stmtLog->bind_param("iss", $usuario_id, $data['correo'], $clave);
                $stmtLog->execute();
            }

            // Asignar sede si se especificó
            if (!empty($data['cede_id'])) {
                $this->assignSede($usuario_id, $data['cede_id']);
            }

            return true;
        }

        return false;
    }

    /**
     * Actualiza los datos de un miembro existente.
     *
     * Actualiza la tabla 'miembros' con los nuevos datos personales.
     * Si se proporcionó correo, actualiza o inserta (UPSERT) en 'userlog'.
     * Si se especificó sede, actualiza la asignación de sede.
     *
     * Nota: la contraseña NO se modifica aquí (se mantiene la existente).
     *
     * @param  int   $id   ID del miembro a actualizar
     * @param  array $data Nuevos datos del miembro
     * @return bool  true si se actualizó correctamente
     */
    public function update($id, $data) {
        $sql = "UPDATE miembros SET nombre = ?, apellido = ?, num_doc = ?, fecha_n = ?, id_grado = ?, telefono = ?, rol = ? WHERE id_miembro = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssisii",
            $data['nombre'],
            $data['apellido'],
            $data['numero_documento'],
            $data['fecha_nacimiento'],
            $data['nivel_id'],
            $data['telefono'],
            $data['rol_id'],
            $id
        );

        if ($stmt->execute()) {
            // Actualizar el correo en userlog; si no existe registro, lo crea con clave vacía
            // ON DUPLICATE KEY UPDATE → UPSERT (actualiza si ya existe la clave primaria)
            if (!empty($data['correo'])) {
                $sqlLog = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, '') ON DUPLICATE KEY UPDATE correo = VALUES(correo)";
                $stmtLog = $this->db->prepare($sqlLog);
                $stmtLog->bind_param("is", $id, $data['correo']);
                $stmtLog->execute();
                $stmtLog->close();
            }

            // Actualizar la sede asignada
            if (isset($data['cede_id'])) {
                $this->assignSede($id, $data['cede_id']);
            }

            return true;
        }

        return false;
    }

    /**
     * Elimina un miembro de la base de datos por su ID.
     *
     * Nota: si hay restricciones de clave foránea con 'userlog',
     * puede ser necesario eliminar primero el registro en userlog.
     *
     * @param  int  $id ID del miembro a eliminar
     * @return bool true si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM miembros WHERE id_miembro = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Asigna una sede a un miembro (o la cambia si ya tenía una).
     * Método privado: solo se usa internamente en create() y update().
     *
     * @param int $usuario_id ID del miembro
     * @param int $sede_id    ID de la sede a asignar
     */
    private function assignSede($usuario_id, $sede_id) {
        $stmt = $this->db->prepare("UPDATE miembros SET id_sede = ? WHERE id_miembro = ?");
        $stmt->bind_param("ii", $sede_id, $usuario_id);
        $stmt->execute();
    }

    /**
     * Obtiene los datos completos de un miembro por su ID.
     *
     * Utilizado principalmente en el dashboard del estudiante para
     * mostrar su nombre, nivel, sede y datos personales.
     *
     * @param  int        $id ID del miembro
     * @return array|null Array asociativo con los datos del miembro, o null si no existe
     */
    public function getById($id) {
        $sql = "SELECT m.id_miembro as id, m.rol as rol_id, m.id_grado as nivel_id,
                        m.nombre, m.apellido, 'CC' as tipo_documento, m.num_doc as numero_documento,
                        m.fecha_n as fecha_nacimiento, m.peso, NULL as categoria, m.telefono, m.activo,
                        u.correo, u.clave,
                        s.nombre as nombre_sede, s.id_sede as sede_id,
                        g.nombre as nombre_nivel
                FROM miembros m
                LEFT JOIN sedes s ON m.id_sede = s.id_sede
                LEFT JOIN grados g ON m.id_grado = g.id_grado
                LEFT JOIN userlog u ON m.id_miembro = u.id_miembro
                WHERE m.id_miembro = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Retorna un array o null si no se encontró
    }

    /**
     * Obtiene los miembros que tienen mostrar_en_web = 1, junto con su URL multimedia.
     * Utilizado para la página web pública.
     */
    public function getPublicProfiles() {
        $sql = "SELECT m.id_miembro as id, m.nombre, m.apellido, m.rol as rol_id, m.descripcion_perfil, mg.url as instagram_url
                FROM miembros m
                LEFT JOIN multimedia_galeria mg ON m.id_miembro = mg.id_miembro
                WHERE m.mostrar_en_web = 1
                ORDER BY m.rol ASC, m.nombre ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene todos los miembros con su información de perfil público.
     * Utilizado en el panel de administración de Perfiles Públicos.
     */
    public function getAllWithPublicProfileInfo() {
        $sql = "SELECT m.id_miembro as id, m.nombre, m.apellido, m.rol as rol_id, m.descripcion_perfil, m.mostrar_en_web, mg.url as instagram_url
                FROM miembros m
                LEFT JOIN multimedia_galeria mg ON m.id_miembro = mg.id_miembro
                ORDER BY m.nombre ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Actualiza la información del perfil público de un miembro.
     */
    public function updatePublicProfile($id, $mostrar_en_web, $descripcion_perfil, $rol) {
        $sql = "UPDATE miembros SET mostrar_en_web = ?, descripcion_perfil = ?, rol = ? WHERE id_miembro = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issi", $mostrar_en_web, $descripcion_perfil, $rol, $id);
        return $stmt->execute();
    }
}

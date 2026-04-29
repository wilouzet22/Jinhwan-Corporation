<?php
namespace App\Models;

use App\Core\Model;

class Usuario extends Model {
    
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

    public function create($data) {
        $clave = password_hash($data['numero_documento'], PASSWORD_DEFAULT);
        
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
            $usuario_id = $stmt->insert_id;
            
            if (!empty($data['correo'])) {
                $sqlLog = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, ?)";
                $stmtLog = $this->db->prepare($sqlLog);
                $stmtLog->bind_param("iss", $usuario_id, $data['correo'], $clave);
                $stmtLog->execute();
            }
            
            if (!empty($data['cede_id'])) {
                $this->assignSede($usuario_id, $data['cede_id']);
            }
            return true;
        }
        return false;
    }

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
             if (!empty($data['correo'])) {
                $sqlLog = "INSERT INTO userlog (id_miembro, correo, clave) VALUES (?, ?, '') ON DUPLICATE KEY UPDATE correo = VALUES(correo)";
                $stmtLog = $this->db->prepare($sqlLog);
                $stmtLog->bind_param("is", $id, $data['correo']);
                $stmtLog->execute();
                $stmtLog->close();
             }

             if (isset($data['cede_id'])) {
                $this->assignSede($id, $data['cede_id']);
             }
             return true;
        }
        return false;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM miembros WHERE id_miembro = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function assignSede($usuario_id, $sede_id) {
        $stmt = $this->db->prepare("UPDATE miembros SET id_sede = ? WHERE id_miembro = ?");
        $stmt->bind_param("ii", $sede_id, $usuario_id);
        $stmt->execute();
    }

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
        return $result->fetch_assoc();
    }
}

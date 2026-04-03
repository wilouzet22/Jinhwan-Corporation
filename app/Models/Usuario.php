<?php
namespace App\Models;

use App\Core\Model;

class Usuario extends Model {
    
    public function getAllWithDetails() {
        $sql = "SELECT u.*, c.nombre as nombre_cede, c.id as cede_id, n.nombre as nombre_nivel 
                FROM usuarios u 
                LEFT JOIN usuario_sede us ON u.id = us.usuario_id
                LEFT JOIN cedes c ON us.sede_id = c.id 
                LEFT JOIN niveles n ON u.nivel_id = n.id 
                ORDER BY u.rol_id ASC, u.nombre ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $clave = password_hash($data['numero_documento'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, apellido, tipo_documento, numero_documento, fecha_nacimiento, nivel_id, telefono, correo, rol_id, clave, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssissis", 
            $data['nombre'], 
            $data['apellido'], 
            $data['tipo_documento'], 
            $data['numero_documento'], 
            $data['fecha_nacimiento'], 
            $data['nivel_id'], 
            $data['telefono'], 
            $data['correo'], 
            $data['rol_id'], 
            $clave
        );
        
        if ($stmt->execute()) {
            $usuario_id = $stmt->insert_id;
            
            if (!empty($data['cede_id'])) {
                $this->assignSede($usuario_id, $data['cede_id']);
            }
            return true;
        }
        return false;
    }

    public function update($id, $data) {
        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, tipo_documento = ?, numero_documento = ?, fecha_nacimiento = ?, nivel_id = ?, telefono = ?, correo = ?, rol_id = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssissii", 
            $data['nombre'], 
            $data['apellido'], 
            $data['tipo_documento'], 
            $data['numero_documento'], 
            $data['fecha_nacimiento'], 
            $data['nivel_id'], 
            $data['telefono'], 
            $data['correo'], 
            $data['rol_id'], 
            $id
        );
        
        if ($stmt->execute()) {
             if (isset($data['cede_id'])) {
                // Remove existing
                $this->db->query("DELETE FROM usuario_sede WHERE usuario_id = $id");
                // Assign new
                $this->assignSede($id, $data['cede_id']);
             }
             return true;
        }
        return false;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function assignSede($usuario_id, $sede_id) {
        $stmt = $this->db->prepare("INSERT INTO usuario_sede (usuario_id, sede_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $sede_id);
        $stmt->execute();
    }
}

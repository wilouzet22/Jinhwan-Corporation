<?php

class MultimediaGaleria extends Model {

    private function ensureColumns() {
        $checkE = $this->db->query("SHOW COLUMNS FROM galeria_multimedia LIKE 'id_estudiante'");
        if ($checkE && $checkE->num_rows === 0) {
            $this->db->query("ALTER TABLE galeria_multimedia ADD COLUMN id_estudiante INT(11) DEFAULT NULL AFTER id_maestro");
        }
        $checkA = $this->db->query("SHOW COLUMNS FROM galeria_multimedia LIKE 'id_administrador'");
        if ($checkA && $checkA->num_rows === 0) {
            $this->db->query("ALTER TABLE galeria_multimedia ADD COLUMN id_administrador INT(11) DEFAULT NULL AFTER id_estudiante");
        }
    }

    public function getByMaestroId($id_maestro) {
        $stmt = $this->db->prepare("SELECT * FROM galeria_multimedia WHERE id_maestro = ? AND tipo = 'instagram' LIMIT 1");
        $stmt->bind_param("i", $id_maestro);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function getByPersona($id, $rol = 'Maestros') {
        $this->ensureColumns();
        $col = 'id_maestro';
        if ($rol === 'Deportistas' || $rol === 'Estudiantes' || $rol === Roles::ESTUDIANTE) {
            $col = 'id_estudiante';
        } elseif ($rol === 'Administracion' || $rol === Roles::ADMINISTRADOR) {
            $col = 'id_administrador';
        }

        $stmt = $this->db->prepare("SELECT * FROM galeria_multimedia WHERE {$col} = ? AND tipo = 'instagram' LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    /** Compatibilidad con llamadas antiguas que usaban getByMiembroId */
    public function getByMiembroId($id_persona) {
        return $this->getByMaestroId($id_persona);
    }

    public function upsertInstagram($id_maestro, $url) {
        $existente = $this->getByMaestroId($id_maestro);
        
        if ($existente) {
            $stmt = $this->db->prepare("UPDATE galeria_multimedia SET url = ? WHERE id_multimedia = ?");
            $stmt->bind_param("si", $url, $existente['id_multimedia']);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } else {
            $titulo = "Instagram Perfil";
            $tipo = "instagram";
            $stmt = $this->db->prepare("INSERT INTO galeria_multimedia (id_maestro, titulo, url, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id_maestro, $titulo, $url, $tipo);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
    }

    public function upsertRole($id, $rol, $url) {
        $this->ensureColumns();
        $existente = $this->getByPersona($id, $rol);
        $col = 'id_maestro';
        if ($rol === 'Deportistas' || $rol === 'Estudiantes' || $rol === Roles::ESTUDIANTE) {
            $col = 'id_estudiante';
        } elseif ($rol === 'Administracion' || $rol === Roles::ADMINISTRADOR) {
            $col = 'id_administrador';
        }

        if ($existente) {
            $stmt = $this->db->prepare("UPDATE galeria_multimedia SET url = ? WHERE id_multimedia = ?");
            $stmt->bind_param("si", $url, $existente['id_multimedia']);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        } else {
            $titulo = "Instagram Perfil";
            $tipo = "instagram";
            $stmt = $this->db->prepare("INSERT INTO galeria_multimedia ({$col}, titulo, url, tipo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id, $titulo, $url, $tipo);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
    }

    public function upsert($id_persona, $url) {
        return $this->upsertInstagram($id_persona, $url);
    }

    public function getAllGeneral() {
        $sql = "SELECT gm.*, CONCAT(m.nombre, ' ', m.apellido) as maestro_nombre
                FROM galeria_multimedia gm
                LEFT JOIN maestro m ON gm.id_maestro = m.id_maestro
                ORDER BY gm.id_multimedia DESC";
        $stmt = $this->db->query($sql);
        return $stmt ? $stmt->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insertGeneral($url, $descripcion, $titulo = 'Publicación en Galería', $tipo = 'instagram', $id_maestro = null) {
        $stmt = $this->db->prepare("INSERT INTO galeria_multimedia (id_maestro, titulo, descripcion, tipo, url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_maestro, $titulo, $descripcion, $tipo, $url);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id_multimedia) {
        $stmt = $this->db->prepare("DELETE FROM galeria_multimedia WHERE id_multimedia = ?");
        $stmt->bind_param("i", $id_multimedia);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

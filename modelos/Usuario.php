<?php

class Usuario extends Model {

    public function getAllWithDetails() {
        $sql = "SELECT p.id_persona as id,
                       COALESCE(u.rol, IF(pm.id_persona IS NOT NULL, 'Maestros', 'Deportistas')) as rol_id,
                       COALESCE(pd.id_grado, pm.id_grado) as nivel_id,
                       p.nombre, p.apellido, p.tipo_documento, p.num_doc as numero_documento,
                       p.telefono, p.foto_perfil, p.activo,
                       pd.fecha_n as fecha_nacimiento, pd.peso, pd.division, pd.eps, pd.rh,
                       pd.id_categoria as categoria_id,
                       pm.descripcion_perfil, pm.logros, COALESCE(pm.mostrar_en_web, 0) as mostrar_en_web,
                       u.permisos_extra, u.correo, u.clave,
                       s.nombre as nombre_sede, s.id_sede as sede_id,
                       g.nombre as nombre_nivel,
                       c.nombre as nombre_categoria,
                       gm.url as instagram_url
                FROM personas p
                LEFT JOIN credenciales u ON p.id_persona = u.id_persona
                LEFT JOIN perfil_deportistas pd ON p.id_persona = pd.id_persona
                LEFT JOIN perfil_maestros pm ON p.id_persona = pm.id_persona
                LEFT JOIN sedes s ON p.id_sede = s.id_sede
                LEFT JOIN grados g ON COALESCE(pd.id_grado, pm.id_grado) = g.id_grado
                LEFT JOIN categorias c ON pd.id_categoria = c.id_categoria
                LEFT JOIN galeria_multimedia gm ON p.id_persona = gm.id_persona
                ORDER BY rol_id ASC, p.nombre ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $this->db->begin_transaction();
        try {
            $tipo_documento = $data['tipo_documento'] ?? 'TI';
            $sede_id = !empty($data['sede_id']) ? (int)$data['sede_id'] : null;
            $foto_perfil = !empty($data['foto_perfil']) ? $data['foto_perfil'] : null;
            $telefono = $data['telefono'] ?? '';
            $num_doc = $data['numero_documento'] ?? '';
            $rol = $data['rol_id'] ?? 'Deportistas';
            $nivel_id = !empty($data['nivel_id']) ? (int)$data['nivel_id'] : 1;

            // 1. Insertar en tabla base personas
            $activo = 1;
            $sqlPersona = "INSERT INTO personas (nombre, apellido, num_doc, tipo_documento, telefono, id_sede, foto_perfil, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtPersona = $this->db->prepare($sqlPersona);
            $stmtPersona->bind_param("sssssisi",
                $data['nombre'],
                $data['apellido'],
                $num_doc,
                $tipo_documento,
                $telefono,
                $sede_id,
                $foto_perfil,
                $activo
            );
            $stmtPersona->execute();
            $persona_id = $stmtPersona->insert_id;
            $stmtPersona->close();

            // 2. Insertar en credenciales si tiene correo o es admin/maestro
            $clave = password_hash($num_doc, PASSWORD_DEFAULT);
            $correo = !empty($data['correo']) ? $data['correo'] : null;
            $permisos_extra = $data['permisos_extra'] ?? null;

            if ($correo) {
                $sqlCred = "INSERT INTO credenciales (id_persona, correo, clave, rol, permisos_extra) VALUES (?, ?, ?, ?, ?)";
                $stmtCred = $this->db->prepare($sqlCred);
                $stmtCred->bind_param("issss", $persona_id, $correo, $clave, $rol, $permisos_extra);
                $stmtCred->execute();
                $stmtCred->close();
            }

            // 3. Insertar perfil según corresponda
            if ($rol === 'Maestros' || $rol === 'Profesores' || $rol === 'Monitores') {
                $descripcion = $data['descripcion_perfil'] ?? null;
                $logros = $data['logros'] ?? null;
                $mostrar_en_web = isset($data['mostrar_en_web']) ? (int)$data['mostrar_en_web'] : 0;

                $sqlMaestro = "INSERT INTO perfil_maestros (id_persona, id_grado, descripcion_perfil, logros, mostrar_en_web) VALUES (?, ?, ?, ?, ?)";
                $stmtM = $this->db->prepare($sqlMaestro);
                $stmtM->bind_param("iissi", $persona_id, $nivel_id, $descripcion, $logros, $mostrar_en_web);
                $stmtM->execute();
                $stmtM->close();
            }

            // Si es deportista o tiene información de taekwondo
            if ($rol === 'Deportistas' || !empty($data['peso']) || !empty($data['categoria_id'])) {
                $categoria_id = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : 1;
                $peso = !empty($data['peso']) ? (float)$data['peso'] : null;
                $fecha_n = !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null;
                $division = $data['division'] ?? null;
                $eps = $data['eps'] ?? null;
                $rh = $data['rh'] ?? null;

                $sqlDep = "INSERT INTO perfil_deportistas (id_persona, id_grado, id_categoria, fecha_n, peso, division, eps, rh) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtDep = $this->db->prepare($sqlDep);
                $stmtDep->bind_param("iiisdsss", $persona_id, $nivel_id, $categoria_id, $fecha_n, $peso, $division, $eps, $rh);
                $stmtDep->execute();
                $stmtDep->close();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function update($id, $data) {
        $this->db->begin_transaction();
        try {
            $tipo_documento = $data['tipo_documento'] ?? 'TI';
            $sede_id = !empty($data['sede_id']) ? (int)$data['sede_id'] : null;
            $foto_perfil = !empty($data['foto_perfil']) ? $data['foto_perfil'] : null;
            $telefono = $data['telefono'] ?? '';
            $num_doc = $data['numero_documento'] ?? '';
            $rol = $data['rol_id'] ?? 'Deportistas';
            $nivel_id = !empty($data['nivel_id']) ? (int)$data['nivel_id'] : 1;
            $permisos_extra = $data['permisos_extra'] ?? null;

            // 1. Actualizar tabla base personas
            if ($foto_perfil) {
                $sqlPersona = "UPDATE personas SET nombre = ?, apellido = ?, num_doc = ?, tipo_documento = ?, telefono = ?, id_sede = ?, foto_perfil = ? WHERE id_persona = ?";
                $stmtPersona = $this->db->prepare($sqlPersona);
                $stmtPersona->bind_param("sssssisi", $data['nombre'], $data['apellido'], $num_doc, $tipo_documento, $telefono, $sede_id, $foto_perfil, $id);
            } else {
                $sqlPersona = "UPDATE personas SET nombre = ?, apellido = ?, num_doc = ?, tipo_documento = ?, telefono = ?, id_sede = ? WHERE id_persona = ?";
                $stmtPersona = $this->db->prepare($sqlPersona);
                $stmtPersona->bind_param("sssssii", $data['nombre'], $data['apellido'], $num_doc, $tipo_documento, $telefono, $sede_id, $id);
            }
            $stmtPersona->execute();
            $stmtPersona->close();

            // 2. Actualizar o insertar credenciales
            $correo = !empty($data['correo']) ? $data['correo'] : null;
            if ($correo) {
                $sqlCred = "INSERT INTO credenciales (id_persona, correo, rol, permisos_extra) VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE correo = VALUES(correo), rol = VALUES(rol), permisos_extra = VALUES(permisos_extra)";
                $stmtCred = $this->db->prepare($sqlCred);
                $stmtCred->bind_param("isss", $id, $correo, $rol, $permisos_extra);
                $stmtCred->execute();
                $stmtCred->close();
            }

            // 3. Actualizar o insertar en perfil_maestros
            if ($rol === 'Maestros' || $rol === 'Profesores' || $rol === 'Monitores') {
                $descripcion = $data['descripcion_perfil'] ?? null;
                $logros = $data['logros'] ?? null;
                $mostrar_en_web = isset($data['mostrar_en_web']) ? (int)$data['mostrar_en_web'] : 0;

                $sqlM = "INSERT INTO perfil_maestros (id_persona, id_grado, descripcion_perfil, logros, mostrar_en_web)
                         VALUES (?, ?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE id_grado = VALUES(id_grado), descripcion_perfil = VALUES(descripcion_perfil), logros = VALUES(logros), mostrar_en_web = VALUES(mostrar_en_web)";
                $stmtM = $this->db->prepare($sqlM);
                $stmtM->bind_param("iissi", $id, $nivel_id, $descripcion, $logros, $mostrar_en_web);
                $stmtM->execute();
                $stmtM->close();
            }

            // 4. Actualizar o insertar en perfil_deportistas
            $categoria_id = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : 1;
            $peso = !empty($data['peso']) ? (float)$data['peso'] : null;
            $fecha_n = !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null;
            $division = $data['division'] ?? null;
            $eps = $data['eps'] ?? null;
            $rh = $data['rh'] ?? null;

            $sqlDep = "INSERT INTO perfil_deportistas (id_persona, id_grado, id_categoria, fecha_n, peso, division, eps, rh)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE id_grado = VALUES(id_grado), id_categoria = VALUES(id_categoria), fecha_n = VALUES(fecha_n), peso = VALUES(peso), division = VALUES(division), eps = VALUES(eps), rh = VALUES(rh)";
            $stmtDep = $this->db->prepare($sqlDep);
            $stmtDep->bind_param("iiisdsss", $id, $nivel_id, $categoria_id, $fecha_n, $peso, $division, $eps, $rh);
            $stmtDep->execute();
            $stmtDep->close();

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM personas WHERE id_persona = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function deleteBulk(array $ids): bool {
        if (empty($ids)) return false;

        $count       = count($ids);
        $placeholders = implode(',', array_fill(0, $count, '?'));
        $types        = str_repeat('i', $count);

        $stmt = $this->db->prepare("DELETE FROM personas WHERE id_persona IN ($placeholders)");
        $stmt->bind_param($types, ...$ids);
        return $stmt->execute();
    }

    public function getById($id) {
        $sql = "SELECT p.id_persona as id,
                       COALESCE(u.rol, IF(pm.id_persona IS NOT NULL, 'Maestros', 'Deportistas')) as rol_id,
                       COALESCE(pd.id_grado, pm.id_grado) as nivel_id,
                       p.nombre, p.apellido, p.tipo_documento, p.num_doc as numero_documento,
                       p.telefono, p.foto_perfil, p.activo,
                       pd.fecha_n as fecha_nacimiento, pd.peso, pd.division, pd.eps, pd.rh,
                       pd.id_categoria as categoria_id,
                       pm.descripcion_perfil, pm.logros, COALESCE(pm.mostrar_en_web, 0) as mostrar_en_web,
                       u.permisos_extra, u.correo, u.clave,
                       s.nombre as nombre_sede, s.id_sede as sede_id,
                       g.nombre as nombre_nivel,
                       c.nombre as nombre_categoria,
                       gm.url as instagram_url
                FROM personas p
                LEFT JOIN credenciales u ON p.id_persona = u.id_persona
                LEFT JOIN perfil_deportistas pd ON p.id_persona = pd.id_persona
                LEFT JOIN perfil_maestros pm ON p.id_persona = pm.id_persona
                LEFT JOIN sedes s ON p.id_sede = s.id_sede
                LEFT JOIN grados g ON COALESCE(pd.id_grado, pm.id_grado) = g.id_grado
                LEFT JOIN categorias c ON pd.id_categoria = c.id_categoria
                LEFT JOIN galeria_multimedia gm ON p.id_persona = gm.id_persona
                WHERE p.id_persona = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }

    public function getPublicProfiles() {
        $sql = "SELECT p.id_persona as id, p.nombre, p.apellido,
                       COALESCE(u.rol, IF(pm.id_persona IS NOT NULL, 'Maestros', 'Deportistas')) as rol_id,
                       pm.descripcion_perfil, p.foto_perfil, gm.url as instagram_url
                FROM personas p
                LEFT JOIN perfil_maestros pm ON p.id_persona = pm.id_persona
                LEFT JOIN credenciales u ON p.id_persona = u.id_persona
                LEFT JOIN galeria_multimedia gm ON p.id_persona = gm.id_persona
                WHERE pm.mostrar_en_web = 1 AND p.activo = 1
                ORDER BY p.nombre ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllWithPublicProfileInfo() {
        $sql = "SELECT p.id_persona as id, p.nombre, p.apellido,
                       COALESCE(u.rol, IF(pm.id_persona IS NOT NULL, 'Maestros', 'Deportistas')) as rol_id,
                       pm.descripcion_perfil, COALESCE(pm.mostrar_en_web, 0) as mostrar_en_web,
                       p.foto_perfil, gm.url as instagram_url
                FROM personas p
                LEFT JOIN perfil_maestros pm ON p.id_persona = pm.id_persona
                LEFT JOIN credenciales u ON p.id_persona = u.id_persona
                LEFT JOIN galeria_multimedia gm ON p.id_persona = gm.id_persona
                ORDER BY p.nombre ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updatePublicProfile($id, $mostrar_en_web, $descripcion_perfil, $rol = null) {
        $sql = "INSERT INTO perfil_maestros (id_persona, descripcion_perfil, mostrar_en_web)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE descripcion_perfil = VALUES(descripcion_perfil), mostrar_en_web = VALUES(mostrar_en_web)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isi", $id, $descripcion_perfil, $mostrar_en_web);
        $res = $stmt->execute();
        $stmt->close();

        if (!empty($rol)) {
            $stmtCred = $this->db->prepare("UPDATE credenciales SET rol = ? WHERE id_persona = ?");
            if ($stmtCred) {
                $stmtCred->bind_param("si", $rol, $id);
                $stmtCred->execute();
                $stmtCred->close();
            }
        }
        return $res;
    }

    public function togglePublicVisibility($id) {
        $sql = "INSERT INTO perfil_maestros (id_persona, mostrar_en_web)
                VALUES (?, 1)
                ON DUPLICATE KEY UPDATE mostrar_en_web = IF(mostrar_en_web = 1, 0, 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();

        // Obtener estado actual
        $check = $this->db->query("SELECT mostrar_en_web FROM perfil_maestros WHERE id_persona = " . (int)$id);
        $row = $check ? $check->fetch_assoc() : null;
        return $row ? (int)$row['mostrar_en_web'] : 0;
    }

    public function setBulkPublicVisibility(array $ids, $visible) {
        if (empty($ids)) return false;
        $visible = $visible ? 1 : 0;
        foreach ($ids as $id) {
            $id = (int)$id;
            $sql = "INSERT INTO perfil_maestros (id_persona, mostrar_en_web)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE mostrar_en_web = VALUES(mostrar_en_web)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $id, $visible);
            $stmt->execute();
            $stmt->close();
        }
        return true;
    }
}

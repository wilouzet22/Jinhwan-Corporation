<?php

class Usuario extends Model {

    public function getAllWithDetails() {
        $sql = "
        -- 1. Administradores
        SELECT 
            a.id_administrador as id,
            'Administracion' as rol_id,
            NULL as nivel_id,
            a.nombre, a.apellido,
            'CC' as tipo_documento,
            '' as numero_documento,
            '' as telefono,
            a.foto_perfil,
            a.activo,
            NULL as fecha_nacimiento,
            NULL as peso,
            NULL as division,
            NULL as eps,
            NULL as rh,
            NULL as categoria_id,
            NULL as descripcion_perfil,
            NULL as logros,
            0 as mostrar_en_web,
            a.permisos_extra,
            a.correo,
            a.clave,
            NULL as nombre_sede,
            NULL as sede_id,
            NULL as id_grupo,
            NULL as nombre_grupo,
            NULL as nombre_nivel,
            NULL as nombre_categoria,
            NULL as instagram_url
        FROM administrador a

        UNION ALL

        -- 2. Maestros / Instructores
        SELECT 
            m.id_maestro as id,
            'Maestros' as rol_id,
            m.id_grado as nivel_id,
            m.nombre, m.apellido,
            m.tipo_documento,
            m.num_doc as numero_documento,
            m.telefono,
            m.foto_perfil,
            m.activo,
            NULL as fecha_nacimiento,
            NULL as peso,
            NULL as division,
            NULL as eps,
            NULL as rh,
            NULL as categoria_id,
            m.descripcion_perfil,
            m.logros,
            COALESCE(m.mostrar_en_web, 0) as mostrar_en_web,
            m.permisos_extra,
            m.correo,
            m.clave,
            s.nombre as nombre_sede,
            s.id_sede as sede_id,
            NULL as id_grupo,
            NULL as nombre_grupo,
            g.nombre as nombre_nivel,
            NULL as nombre_categoria,
            gm.url as instagram_url
        FROM maestro m
        LEFT JOIN sedes s ON m.id_sede = s.id_sede
        LEFT JOIN grados g ON m.id_grado = g.id_grado
        LEFT JOIN galeria_multimedia gm ON m.id_maestro = gm.id_maestro AND gm.tipo = 'instagram'

        UNION ALL

        -- 3. Estudiantes / Deportistas
        SELECT 
            e.id_estudiante as id,
            'Deportistas' as rol_id,
            e.id_grado as nivel_id,
            e.nombre, e.apellido,
            e.tipo_documento,
            e.num_doc as numero_documento,
            e.telefono,
            e.foto_perfil,
            e.activo,
            e.fecha_nacimiento,
            e.peso,
            e.division,
            e.eps,
            e.rh,
            e.id_categoria as categoria_id,
            NULL as descripcion_perfil,
            NULL as logros,
            0 as mostrar_en_web,
            NULL as permisos_extra,
            e.correo,
            e.clave,
            s.nombre as nombre_sede,
            s.id_sede as sede_id,
            gr.id_grupo,
            gr.nombre as nombre_grupo,
            g.nombre as nombre_nivel,
            c.nombre as nombre_categoria,
            NULL as instagram_url
        FROM estudiante e
        LEFT JOIN grupos gr ON e.id_grupo = gr.id_grupo
        LEFT JOIN sedes s ON gr.id_sede = s.id_sede
        LEFT JOIN grados g ON e.id_grado = g.id_grado
        LEFT JOIN categorias c ON e.id_categoria = c.id_categoria

        ORDER BY rol_id ASC, nombre ASC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create($data) {
        $rol = $data['rol_id'] ?? Roles::ESTUDIANTE;
        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $correo = !empty($data['correo']) ? strtolower(trim($data['correo'])) : null;
        $num_doc = trim($data['numero_documento'] ?? ($data['num_doc'] ?? ''));
        $tipo_documento = $data['tipo_documento'] ?? 'TI';
        $telefono = $data['telefono'] ?? '';
        $foto_perfil = !empty($data['foto_perfil']) ? $data['foto_perfil'] : null;
        $clave_raw = !empty($data['clave']) ? $data['clave'] : (!empty($num_doc) ? $num_doc : '123456');
        $clave_hash = password_hash($clave_raw, PASSWORD_DEFAULT);
        $activo = isset($data['activo']) ? (int)$data['activo'] : 1;
        $nivel_id = !empty($data['nivel_id']) ? (int)$data['nivel_id'] : 1;

        if ($rol === Roles::ADMINISTRADOR) {
            $permisos_extra = $data['permisos_extra'] ?? null;
            $stmt = $this->db->prepare("INSERT INTO administrador (nombre, apellido, correo, clave, foto_perfil, permisos_extra, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $nombre, $apellido, $correo, $clave_hash, $foto_perfil, $permisos_extra, $activo);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        if ($rol === Roles::MAESTRO || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            $sede_id = !empty($data['sede_id']) ? (int)$data['sede_id'] : null;
            $descripcion = $data['descripcion_perfil'] ?? null;
            $logros = $data['logros'] ?? null;
            $mostrar_en_web = isset($data['mostrar_en_web']) ? (int)$data['mostrar_en_web'] : 0;
            $permisos_extra = $data['permisos_extra'] ?? null;

            $stmt = $this->db->prepare("INSERT INTO maestro (id_grado, nombre, apellido, tipo_documento, num_doc, telefono, id_sede, foto_perfil, descripcion_perfil, logros, mostrar_en_web, correo, clave, permisos_extra, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssisssisssi", 
                $nivel_id, $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $sede_id,
                $foto_perfil, $descripcion, $logros, $mostrar_en_web, $correo, $clave_hash, $permisos_extra, $activo
            );
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        // Estudiante / Deportista
        $categoria_id = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : 1;
        $id_grupo = !empty($data['id_grupo']) ? (int)$data['id_grupo'] : (!empty($data['grupo_id']) ? (int)$data['grupo_id'] : null);

        // Si no se envió grupo directo pero se envió sede_id, asignar el primer grupo disponible de esa sede
        if (!$id_grupo && !empty($data['sede_id'])) {
            $checkG = $this->db->query("SELECT id_grupo FROM grupos WHERE id_sede = " . (int)$data['sede_id'] . " LIMIT 1");
            if ($checkG && $rowG = $checkG->fetch_assoc()) {
                $id_grupo = (int)$rowG['id_grupo'];
            }
        }
        if (!$id_grupo) {
            $id_grupo = 1; // Grupo A por defecto
        }

        $id_maestro = !empty($data['id_maestro']) ? (int)$data['id_maestro'] : null;
        if (!$id_maestro && $id_grupo) {
            $checkM = $this->db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
            if ($checkM && $rowM = $checkM->fetch_assoc()) {
                $id_maestro = !empty($rowM['id_maestro']) ? (int)$rowM['id_maestro'] : null;
            }
        }

        $fecha_nacimiento = !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null;
        $peso = !empty($data['peso']) ? (float)$data['peso'] : null;
        $division = $data['division'] ?? null;
        $eps = $data['eps'] ?? null;
        $rh = $data['rh'] ?? null;

        $stmt = $this->db->prepare("INSERT INTO estudiante (id_grado, id_categoria, id_grupo, id_maestro, nombre, apellido, tipo_documento, num_doc, telefono, foto_perfil, fecha_nacimiento, peso, division, eps, rh, correo, clave, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiissssssdssssssi",
            $nivel_id, $categoria_id, $id_grupo, $id_maestro,
            $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $foto_perfil,
            $fecha_nacimiento, $peso, $division, $eps, $rh, $correo, $clave_hash, $activo
        );
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function update($id, $data) {
        $id = (int)$id;
        $rol = $data['rol_id'] ?? null;

        // Determinar a qué tabla pertenece el usuario si no se envió rol
        if (!$rol) {
            $current = $this->getById($id);
            if (!$current) return false;
            $rol = $current['rol_id'];
        }

        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $correo = !empty($data['correo']) ? strtolower(trim($data['correo'])) : null;
        $num_doc = trim($data['numero_documento'] ?? ($data['num_doc'] ?? ''));
        $tipo_documento = $data['tipo_documento'] ?? 'TI';
        $telefono = $data['telefono'] ?? '';
        $foto_perfil = !empty($data['foto_perfil']) ? $data['foto_perfil'] : null;
        $nivel_id = !empty($data['nivel_id']) ? (int)$data['nivel_id'] : 1;

        if ($rol === Roles::ADMINISTRADOR) {
            $permisos_extra = $data['permisos_extra'] ?? null;
            if ($foto_perfil) {
                $stmt = $this->db->prepare("UPDATE administrador SET nombre = ?, apellido = ?, correo = ?, foto_perfil = ?, permisos_extra = ? WHERE id_administrador = ?");
                $stmt->bind_param("sssssi", $nombre, $apellido, $correo, $foto_perfil, $permisos_extra, $id);
            } else {
                $stmt = $this->db->prepare("UPDATE administrador SET nombre = ?, apellido = ?, correo = ?, permisos_extra = ? WHERE id_administrador = ?");
                $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $permisos_extra, $id);
            }
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        if ($rol === Roles::MAESTRO || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            $sede_id = !empty($data['sede_id']) ? (int)$data['sede_id'] : null;
            $descripcion = $data['descripcion_perfil'] ?? null;
            $logros = $data['logros'] ?? null;
            $mostrar_en_web = isset($data['mostrar_en_web']) ? (int)$data['mostrar_en_web'] : 0;
            $permisos_extra = $data['permisos_extra'] ?? null;

            if ($foto_perfil) {
                $stmt = $this->db->prepare("UPDATE maestro SET id_grado = ?, nombre = ?, apellido = ?, tipo_documento = ?, num_doc = ?, telefono = ?, id_sede = ?, foto_perfil = ?, descripcion_perfil = ?, logros = ?, mostrar_en_web = ?, correo = ?, permisos_extra = ? WHERE id_maestro = ?");
                $stmt->bind_param("isssssisssissi", $nivel_id, $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $sede_id, $foto_perfil, $descripcion, $logros, $mostrar_en_web, $correo, $permisos_extra, $id);
            } else {
                $stmt = $this->db->prepare("UPDATE maestro SET id_grado = ?, nombre = ?, apellido = ?, tipo_documento = ?, num_doc = ?, telefono = ?, id_sede = ?, descripcion_perfil = ?, logros = ?, mostrar_en_web = ?, correo = ?, permisos_extra = ? WHERE id_maestro = ?");
                $stmt->bind_param("isssssisssisi", $nivel_id, $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $sede_id, $descripcion, $logros, $mostrar_en_web, $correo, $permisos_extra, $id);
            }
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        // Estudiante / Deportista
        $categoria_id = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : 1;
        $id_grupo = !empty($data['id_grupo']) ? (int)$data['id_grupo'] : (!empty($data['grupo_id']) ? (int)$data['grupo_id'] : null);
        if (!$id_grupo && !empty($data['sede_id'])) {
            $checkG = $this->db->query("SELECT id_grupo FROM grupos WHERE id_sede = " . (int)$data['sede_id'] . " LIMIT 1");
            if ($checkG && $rowG = $checkG->fetch_assoc()) {
                $id_grupo = (int)$rowG['id_grupo'];
            }
        }
        $fecha_nacimiento = !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null;
        $peso = !empty($data['peso']) ? (float)$data['peso'] : null;
        $division = $data['division'] ?? null;
        $eps = $data['eps'] ?? null;
        $rh = $data['rh'] ?? null;

        $descripcion = $data['descripcion_perfil'] ?? null;
        $logros = $data['logros'] ?? null;
        $mostrar_en_web = isset($data['mostrar_en_web']) ? (int)$data['mostrar_en_web'] : 0;

        $this->ensurePublicProfileColumns();

        if ($foto_perfil) {
            $stmt = $this->db->prepare("UPDATE estudiante SET id_grado = ?, id_categoria = ?, id_grupo = COALESCE(?, id_grupo), nombre = ?, apellido = ?, tipo_documento = ?, num_doc = ?, telefono = ?, foto_perfil = ?, fecha_nacimiento = ?, peso = ?, division = ?, eps = ?, rh = ?, correo = ?, descripcion_perfil = ?, logros = ?, mostrar_en_web = ? WHERE id_estudiante = ?");
            $stmt->bind_param("iiisssssssdssssssii", $nivel_id, $categoria_id, $id_grupo, $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $foto_perfil, $fecha_nacimiento, $peso, $division, $eps, $rh, $correo, $descripcion, $logros, $mostrar_en_web, $id);
        } else {
            $stmt = $this->db->prepare("UPDATE estudiante SET id_grado = ?, id_categoria = ?, id_grupo = COALESCE(?, id_grupo), nombre = ?, apellido = ?, tipo_documento = ?, num_doc = ?, telefono = ?, fecha_nacimiento = ?, peso = ?, division = ?, eps = ?, rh = ?, correo = ?, descripcion_perfil = ?, logros = ?, mostrar_en_web = ? WHERE id_estudiante = ?");
            $stmt->bind_param("iiissssssdssssssii", $nivel_id, $categoria_id, $id_grupo, $nombre, $apellido, $tipo_documento, $num_doc, $telefono, $fecha_nacimiento, $peso, $division, $eps, $rh, $correo, $descripcion, $logros, $mostrar_en_web, $id);
        }
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function delete($id, $rol = null) {
        $id = (int)$id;
        if ($rol === Roles::ADMINISTRADOR) {
            $stmt = $this->db->prepare("DELETE FROM administrador WHERE id_administrador = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        if ($rol === Roles::MAESTRO) {
            $stmt = $this->db->prepare("DELETE FROM maestro WHERE id_maestro = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
        if ($rol === Roles::ESTUDIANTE) {
            $stmt = $this->db->prepare("DELETE FROM estudiante WHERE id_estudiante = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        // Si no se especifica rol, intentar en estudiante, luego maestro, luego admin
        $stmt = $this->db->prepare("DELETE FROM estudiante WHERE id_estudiante = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        }
        $stmt->close();

        $stmt = $this->db->prepare("DELETE FROM maestro WHERE id_maestro = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true;
        }
        $stmt->close();

        $stmt = $this->db->prepare("DELETE FROM administrador WHERE id_administrador = ?");
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function deleteBulk(array $ids): bool {
        if (empty($ids)) return false;
        $idsClean = array_map('intval', $ids);
        $inList = implode(',', $idsClean);

        $this->db->query("DELETE FROM estudiante WHERE id_estudiante IN ($inList)");
        $this->db->query("DELETE FROM maestro WHERE id_maestro IN ($inList)");
        $this->db->query("DELETE FROM administrador WHERE id_administrador IN ($inList)");
        return true;
    }

    public function getById($id) {
        $id = (int)$id;

        // 1. Buscar en Administrador
        $stmt = $this->db->prepare("SELECT id_administrador as id, 'Administracion' as rol_id, nombre, apellido, correo, clave, foto_perfil, activo, permisos_extra, 'CC' as tipo_documento, '' as numero_documento, '' as telefono, NULL as sede_id, NULL as nombre_sede, NULL as nivel_id, NULL as nombre_nivel, NULL as categoria_id, NULL as nombre_categoria, NULL as fecha_nacimiento, NULL as peso, NULL as division, NULL as eps, NULL as rh, NULL as descripcion_perfil, NULL as logros, 0 as mostrar_en_web, NULL as instagram_url FROM administrador WHERE id_administrador = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $stmt->close();
            return $row;
        }
        $stmt->close();

        // 2. Buscar en Maestro
        $stmt = $this->db->prepare("SELECT m.id_maestro as id, 'Maestros' as rol_id, m.nombre, m.apellido, m.tipo_documento, m.num_doc as numero_documento, m.telefono, m.foto_perfil, m.activo, m.descripcion_perfil, m.logros, COALESCE(m.mostrar_en_web, 0) as mostrar_en_web, m.permisos_extra, m.correo, m.clave, s.id_sede as sede_id, s.nombre as nombre_sede, m.id_grado as nivel_id, g.nombre as nombre_nivel, NULL as categoria_id, NULL as nombre_categoria, NULL as fecha_nacimiento, NULL as peso, NULL as division, NULL as eps, NULL as rh, gm.url as instagram_url FROM maestro m LEFT JOIN sedes s ON m.id_sede = s.id_sede LEFT JOIN grados g ON m.id_grado = g.id_grado LEFT JOIN galeria_multimedia gm ON m.id_maestro = gm.id_maestro AND gm.tipo = 'instagram' WHERE m.id_maestro = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $stmt->close();
            return $row;
        }
        $stmt->close();

        // 3. Buscar en Estudiante
        $stmt = $this->db->prepare("SELECT e.id_estudiante as id, 'Deportistas' as rol_id, e.nombre, e.apellido, e.tipo_documento, e.num_doc as numero_documento, e.telefono, e.foto_perfil, e.activo, e.fecha_nacimiento, e.peso, e.division, e.eps, e.rh, e.id_categoria as categoria_id, c.nombre as nombre_categoria, e.id_grado as nivel_id, g.nombre as nombre_nivel, gr.id_grupo, gr.nombre as nombre_grupo, s.id_sede as sede_id, s.nombre as nombre_sede, e.id_maestro, e.correo, e.clave, NULL as permisos_extra, NULL as descripcion_perfil, NULL as logros, 0 as mostrar_en_web, NULL as instagram_url FROM estudiante e LEFT JOIN grupos gr ON e.id_grupo = gr.id_grupo LEFT JOIN sedes s ON gr.id_sede = s.id_sede LEFT JOIN grados g ON e.id_grado = g.id_grado LEFT JOIN categorias c ON e.id_categoria = c.id_categoria WHERE e.id_estudiante = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            $stmt->close();
            return $row;
        }
        $stmt->close();

        return null;
    }

    public function ensurePublicProfileColumns() {
        // Asegurar columnas en estudiante
        $checkE = $this->db->query("SHOW COLUMNS FROM estudiante LIKE 'mostrar_en_web'");
        if ($checkE && $checkE->num_rows === 0) {
            $this->db->query("ALTER TABLE estudiante ADD COLUMN mostrar_en_web TINYINT(1) DEFAULT 0");
            $this->db->query("ALTER TABLE estudiante ADD COLUMN descripcion_perfil TEXT DEFAULT NULL");
            $this->db->query("ALTER TABLE estudiante ADD COLUMN logros TEXT DEFAULT NULL");
        }
        // Asegurar columnas en administrador
        $checkA = $this->db->query("SHOW COLUMNS FROM administrador LIKE 'mostrar_en_web'");
        if ($checkA && $checkA->num_rows === 0) {
            $this->db->query("ALTER TABLE administrador ADD COLUMN mostrar_en_web TINYINT(1) DEFAULT 0");
            $this->db->query("ALTER TABLE administrador ADD COLUMN descripcion_perfil TEXT DEFAULT NULL");
            $this->db->query("ALTER TABLE administrador ADD COLUMN logros TEXT DEFAULT NULL");
        }
        // Asegurar columnas en galeria_multimedia
        $checkGE = $this->db->query("SHOW COLUMNS FROM galeria_multimedia LIKE 'id_estudiante'");
        if ($checkGE && $checkGE->num_rows === 0) {
            $this->db->query("ALTER TABLE galeria_multimedia ADD COLUMN id_estudiante INT(11) DEFAULT NULL AFTER id_maestro");
        }
        $checkGA = $this->db->query("SHOW COLUMNS FROM galeria_multimedia LIKE 'id_administrador'");
        if ($checkGA && $checkGA->num_rows === 0) {
            $this->db->query("ALTER TABLE galeria_multimedia ADD COLUMN id_administrador INT(11) DEFAULT NULL AFTER id_estudiante");
        }

        // Restaurar nombres de estudiantes que se hayan guardado como 0
        $checkZero = $this->db->query("SELECT id_estudiante FROM estudiante WHERE nombre = '0' OR nombre = 0 LIMIT 1");
        if ($checkZero && $checkZero->num_rows > 0) {
            $studentNames = [
                101 => 'Jean Karlo',
                102 => 'Samuel',
                103 => 'Daniel Andrés',
                104 => 'Miguel Ángel',
                105 => 'Daniel',
                106 => 'Juan Camilo',
                107 => 'Salome',
                108 => 'Matías',
                109 => 'Juan Pablo',
                110 => 'Juan Camilo',
                111 => 'Danna Sofia',
                112 => 'Samir Enrique',
                113 => 'Matías',
                114 => 'Maximiliano',
                115 => 'Sofia',
                116 => 'Dylan Andrés',
                117 => 'Santiago Andres',
                118 => 'Smith',
                119 => 'Aaron David',
                120 => 'Anderson Steven',
                121 => 'Nicolás',
                122 => 'Jerónimo',
                123 => 'Juan David',
                124 => 'Sara',
                125 => 'Ana Sofía',
                126 => 'José Ignacio',
                127 => 'Marcelo Gabriel',
                128 => 'Juan Camilo',
                129 => 'Samuel Cano',
                130 => 'Hillary',
                131 => 'Diego Fernando',
                132 => 'Rubiangelys Sofía',
                133 => 'Jeziel Abrahán',
                134 => 'Juan José',
                135 => 'Kevin Andrés',
                136 => 'Samuel',
                137 => 'Emanuel',
                138 => 'Ana Sofía',
                139 => 'Samuel',
                140 => 'Sarah Sofía',
                141 => 'Ana Sofía',
                142 => 'Ismael',
                143 => 'Valeria',
                144 => 'Mariana',
                145 => 'Jimena',
                146 => 'Luciana',
                147 => 'Gabriela',
                148 => 'Ana Sofia',
                153 => 'Mateo',
                154 => 'maria jose'
            ];
            foreach ($studentNames as $sid => $sname) {
                $snameClean = $this->db->real_escape_string($sname);
                $this->db->query("UPDATE estudiante SET nombre = '{$snameClean}' WHERE id_estudiante = {$sid} AND (nombre = '0' OR nombre = 0)");
            }
        }
    }

    public function getPublicProfiles() {
        $this->ensurePublicProfileColumns();
        $sql = "
        -- 1. Maestros e Instructores
        SELECT m.id_maestro as id, m.nombre, m.apellido,
               'Maestros' as rol_id,
               m.descripcion_perfil, m.logros, m.foto_perfil,
               g.nombre as nombre_nivel, s.nombre as nombre_sede,
               gm.url as instagram_url
        FROM maestro m
        LEFT JOIN grados g ON m.id_grado = g.id_grado
        LEFT JOIN sedes s ON m.id_sede = s.id_sede
        LEFT JOIN galeria_multimedia gm ON m.id_maestro = gm.id_maestro AND gm.tipo = 'instagram'
        WHERE COALESCE(m.mostrar_en_web, 0) = 1 AND m.activo = 1

        UNION ALL

        -- 2. Alumnos / Deportistas
        SELECT e.id_estudiante as id, e.nombre, e.apellido,
               'Deportistas' as rol_id,
               e.descripcion_perfil, e.logros, e.foto_perfil,
               g.nombre as nombre_nivel, s.nombre as nombre_sede,
               gm.url as instagram_url
        FROM estudiante e
        LEFT JOIN grados g ON e.id_grado = g.id_grado
        LEFT JOIN grupos gr ON e.id_grupo = gr.id_grupo
        LEFT JOIN sedes s ON gr.id_sede = s.id_sede
        LEFT JOIN galeria_multimedia gm ON e.id_estudiante = gm.id_estudiante AND gm.tipo = 'instagram'
        WHERE COALESCE(e.mostrar_en_web, 0) = 1 AND e.activo = 1

        ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllWithPublicProfileInfo() {
        $this->ensurePublicProfileColumns();
        $sql = "
        -- 1. Administradores
        SELECT a.id_administrador as id, a.nombre, a.apellido,
               'Administracion' as rol_id,
               a.descripcion_perfil, COALESCE(a.mostrar_en_web, 0) as mostrar_en_web,
               a.foto_perfil, gm.url as instagram_url
        FROM administrador a
        LEFT JOIN galeria_multimedia gm ON a.id_administrador = gm.id_administrador AND gm.tipo = 'instagram'

        UNION ALL

        -- 2. Maestros e Instructores
        SELECT m.id_maestro as id, m.nombre, m.apellido,
               'Maestros' as rol_id,
               m.descripcion_perfil, COALESCE(m.mostrar_en_web, 0) as mostrar_en_web,
               m.foto_perfil, gm.url as instagram_url
        FROM maestro m
        LEFT JOIN galeria_multimedia gm ON m.id_maestro = gm.id_maestro AND gm.tipo = 'instagram'

        UNION ALL

        -- 3. Estudiantes / Deportistas
        SELECT e.id_estudiante as id, e.nombre, e.apellido,
               'Deportistas' as rol_id,
               e.descripcion_perfil, COALESCE(e.mostrar_en_web, 0) as mostrar_en_web,
               e.foto_perfil, gm.url as instagram_url
        FROM estudiante e
        LEFT JOIN galeria_multimedia gm ON e.id_estudiante = gm.id_estudiante AND gm.tipo = 'instagram'

        ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function updatePublicProfile($id, $mostrar_en_web, $descripcion_perfil, $rol = null) {
        $this->ensurePublicProfileColumns();
        $id = (int)$id;
        $mostrar_en_web = (int)$mostrar_en_web;

        if ($rol === Roles::ADMINISTRADOR || $rol === 'Administracion') {
            $stmt = $this->db->prepare("UPDATE administrador SET descripcion_perfil = ?, mostrar_en_web = ? WHERE id_administrador = ?");
        } elseif ($rol === Roles::ESTUDIANTE || $rol === 'Deportistas' || $rol === 'Estudiantes') {
            $stmt = $this->db->prepare("UPDATE estudiante SET descripcion_perfil = ?, mostrar_en_web = ? WHERE id_estudiante = ?");
        } else {
            $stmt = $this->db->prepare("UPDATE maestro SET descripcion_perfil = ?, mostrar_en_web = ? WHERE id_maestro = ?");
        }

        $stmt->bind_param("sii", $descripcion_perfil, $mostrar_en_web, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function togglePublicVisibility($id, $rol = null) {
        $this->ensurePublicProfileColumns();
        $id = (int)$id;

        if ($rol === Roles::ADMINISTRADOR || $rol === 'Administracion') {
            $table = 'administrador';
            $pk = 'id_administrador';
        } elseif ($rol === Roles::ESTUDIANTE || $rol === 'Deportistas' || $rol === 'Estudiantes') {
            $table = 'estudiante';
            $pk = 'id_estudiante';
        } else {
            // Intentar detectar si no se envió rol
            $table = 'maestro';
            $pk = 'id_maestro';
            if (!$rol) {
                $checkM = $this->db->query("SELECT id_maestro FROM maestro WHERE id_maestro = $id");
                if (!$checkM || $checkM->num_rows === 0) {
                    $checkE = $this->db->query("SELECT id_estudiante FROM estudiante WHERE id_estudiante = $id");
                    if ($checkE && $checkE->num_rows > 0) {
                        $table = 'estudiante';
                        $pk = 'id_estudiante';
                    } else {
                        $table = 'administrador';
                        $pk = 'id_administrador';
                    }
                }
            }
        }

        $stmt = $this->db->prepare("UPDATE {$table} SET mostrar_en_web = IF(mostrar_en_web = 1, 0, 1) WHERE {$pk} = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $check = $this->db->query("SELECT mostrar_en_web FROM {$table} WHERE {$pk} = " . $id);
        $row = $check ? $check->fetch_assoc() : null;
        return $row ? (int)$row['mostrar_en_web'] : 0;
    }

    public function setBulkPublicVisibility(array $items, $visible) {
        if (empty($items)) return false;
        $this->ensurePublicProfileColumns();
        $visible = $visible ? 1 : 0;

        $maestroIds = [];
        $estudianteIds = [];
        $adminIds = [];

        foreach ($items as $item) {
            if (is_string($item) && str_contains($item, ':')) {
                [$rol, $id] = explode(':', $item, 2);
                $id = (int)$id;
                if ($rol === 'Administracion' || $rol === Roles::ADMINISTRADOR) {
                    $adminIds[] = $id;
                } elseif ($rol === 'Deportistas' || $rol === Roles::ESTUDIANTE) {
                    $estudianteIds[] = $id;
                } else {
                    $maestroIds[] = $id;
                }
            } else {
                $maestroIds[] = (int)$item;
            }
        }

        if (!empty($maestroIds)) {
            $inList = implode(',', $maestroIds);
            $this->db->query("UPDATE maestro SET mostrar_en_web = $visible WHERE id_maestro IN ($inList)");
        }
        if (!empty($estudianteIds)) {
            $inList = implode(',', $estudianteIds);
            $this->db->query("UPDATE estudiante SET mostrar_en_web = $visible WHERE id_estudiante IN ($inList)");
        }
        if (!empty($adminIds)) {
            $inList = implode(',', $adminIds);
            $this->db->query("UPDATE administrador SET mostrar_en_web = $visible WHERE id_administrador IN ($inList)");
        }

        return true;
    }

    public function updatePassword(int $id, string $newPasswordHash, ?string $rol = null): bool {
        if ($rol === Roles::ADMINISTRADOR) {
            $stmt = $this->db->prepare("UPDATE administrador SET clave = ? WHERE id_administrador = ?");
        } elseif ($rol === Roles::MAESTRO || $rol === Roles::PROFESOR || $rol === Roles::MONITOR) {
            $stmt = $this->db->prepare("UPDATE maestro SET clave = ? WHERE id_maestro = ?");
        } else {
            $stmt = $this->db->prepare("UPDATE estudiante SET clave = ? WHERE id_estudiante = ?");
        }
        $stmt->bind_param("si", $newPasswordHash, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}

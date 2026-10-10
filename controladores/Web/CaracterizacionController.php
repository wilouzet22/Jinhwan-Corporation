<?php

class WebCaracterizacionController extends Controller {

    /**
     * Muestra la página de caracterización.
     * Si llega ?doc=... busca el usuario y pasa los datos a la vista.
     */
    public function index() {
        $usuario  = null;
        $rol      = null;
        $error    = null;
        $success  = $_GET['ok'] ?? null;

        $num_doc = trim($_GET['doc'] ?? '');

        if ($num_doc !== '') {
            $resultado = $this->buscarPorDocumento($num_doc);
            if ($resultado) {
                $usuario = $resultado['datos'];
                $rol     = $resultado['rol'];
            } else {
                $error = 'no_encontrado';
            }
        }

        $nuevo    = $_GET['nuevo'] ?? null;

        $this->view('web/caracterizacion', [
            'usuario' => $usuario,
            'rol'     => $rol,
            'num_doc' => $num_doc,
            'error'   => $error,
            'success' => $success,
            'nuevo'   => $nuevo,
        ]);
    }

    /**
     * Busca un usuario (estudiante o maestro) por número de documento.
     */
    private function buscarPorDocumento(string $num_doc): ?array {
        $db = Database::getInstance()->getConnection();

        // Buscar en estudiante
        $stmt = $db->prepare(
            "SELECT id_estudiante as id, nombre, apellido, correo, telefono,
                    tipo_documento, num_doc, fecha_nacimiento, eps, rh,
                    peso, division
             FROM estudiante
             WHERE TRIM(num_doc) = ?
             LIMIT 1"
        );
        $stmt->bind_param("s", $num_doc);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $stmt->close();
            return ['rol' => 'estudiante', 'datos' => $res->fetch_assoc()];
        }
        $stmt->close();

        // Buscar en maestro
        $stmt2 = $db->prepare(
            "SELECT id_maestro as id, nombre, apellido, correo, telefono,
                    tipo_documento, num_doc
             FROM maestro
             WHERE TRIM(num_doc) = ?
             LIMIT 1"
        );
        $stmt2->bind_param("s", $num_doc);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        if ($res2 && $res2->num_rows > 0) {
            $stmt2->close();
            return ['rol' => 'maestro', 'datos' => $res2->fetch_assoc()];
        }
        $stmt2->close();

        return null;
    }

    /**
     * Procesa el formulario de actualización de datos.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/caracterizacion');
            return;
        }

        $id      = (int)($_POST['id'] ?? 0);
        $rol     = $_POST['rol'] ?? '';
        $num_doc = trim($_POST['num_doc'] ?? '');

        if (!$id || !in_array($rol, ['estudiante', 'maestro'])) {
            $this->redirect('/caracterizacion?error=invalido');
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Campos comunes (todos opcionales)
        $nombre    = trim($_POST['nombre'] ?? '');
        $apellido  = trim($_POST['apellido'] ?? '');
        $correo    = strtolower(trim($_POST['correo'] ?? ''));
        $telefono  = trim($_POST['telefono'] ?? '');
        $tipo_doc  = trim($_POST['tipo_documento'] ?? 'TI');

        if ($rol === 'estudiante') {
            $fecha_nac = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
            $eps       = trim($_POST['eps'] ?? '');
            $rh        = trim($_POST['rh'] ?? '');
            $peso      = !empty($_POST['peso']) ? (float)$_POST['peso'] : null;
            $division  = trim($_POST['division'] ?? '');

            $correo_val   = $correo !== '' ? $correo : null;
            $telefono_val = $telefono !== '' ? $telefono : null;
            $eps_val      = $eps !== '' ? $eps : null;
            $rh_val       = $rh !== '' ? $rh : null;
            $division_val = $division !== '' ? $division : null;

            $stmt = $db->prepare(
                "UPDATE estudiante
                 SET nombre = ?, apellido = ?, correo = ?, telefono = ?,
                     tipo_documento = ?, fecha_nacimiento = ?, eps = ?, rh = ?,
                     peso = ?, division = ?
                 WHERE id_estudiante = ?"
            );
            $stmt->bind_param(
                "ssssssssdsi",
                $nombre, $apellido, $correo_val, $telefono_val,
                $tipo_doc, $fecha_nac, $eps_val, $rh_val,
                $peso, $division_val, $id
            );
        } else {
            $correo_val   = $correo !== '' ? $correo : null;
            $telefono_val = $telefono !== '' ? $telefono : null;

            $stmt = $db->prepare(
                "UPDATE maestro
                 SET nombre = ?, apellido = ?, correo = ?, telefono = ?,
                     tipo_documento = ?
                 WHERE id_maestro = ?"
            );
            $stmt->bind_param(
                "sssssi",
                $nombre, $apellido, $correo_val, $telefono_val,
                $tipo_doc, $id
            );
        }

        $stmt->execute();
        $stmt->close();

        // Registrar notificación para el administrador
        $nombreCompleto = trim($nombre . ' ' . $apellido);
        $rolLabel = $rol === 'maestro' ? 'El Maestro' : 'El Estudiante';
        Notificacion::registrar(
            'caracterizacion_update',
            'Caracterización Actualizada',
            "{$rolLabel} {$nombreCompleto} (Doc: {$num_doc}) actualizó sus datos de contacto y médicos.",
            $rol === 'estudiante' ? '/admin/estudiantes' : '/admin/maestros'
        );

        $this->redirect('/caracterizacion?doc=' . urlencode($num_doc) . '&ok=1');
    }

    /**
     * Registra un estudiante nuevo desde la página de caracterización.
     * Queda con activo = 0 (pendiente de revisión/aprobación por el Administrador).
     */
    public function registrarNuevo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/caracterizacion');
            return;
        }

        $num_doc  = trim($_POST['num_doc'] ?? '');
        $tipo_doc = trim($_POST['tipo_documento'] ?? 'TI');
        $nombre   = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');

        if ($num_doc === '' || $nombre === '' || $apellido === '') {
            $this->redirect('/caracterizacion?doc=' . urlencode($num_doc) . '&error=datos_incompletos');
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Evitar duplicados si ya existe
        $check = $db->prepare("SELECT id_estudiante FROM estudiante WHERE TRIM(num_doc) = ? LIMIT 1");
        $check->bind_param("s", $num_doc);
        $check->execute();
        $resCheck = $check->get_result();
        if ($resCheck && $resCheck->num_rows > 0) {
            $check->close();
            $this->redirect('/caracterizacion?doc=' . urlencode($num_doc));
            return;
        }
        $check->close();

        // Obtener maestro del grupo 1 si está configurado
        $id_grupo   = 1;
        $id_maestro = null;
        $checkM = $db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
        if ($checkM && $rM = $checkM->fetch_assoc()) {
            $id_maestro = !empty($rM['id_maestro']) ? (int)$rM['id_maestro'] : null;
        }

        $correo       = strtolower(trim($_POST['correo'] ?? ''));
        $telefono     = trim($_POST['telefono'] ?? '');
        $fecha_nac    = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
        $eps          = trim($_POST['eps'] ?? '');
        $rh           = trim($_POST['rh'] ?? '');
        $peso         = !empty($_POST['peso']) ? (float)$_POST['peso'] : null;
        $division     = trim($_POST['division'] ?? '');

        $correo_val   = $correo !== '' ? $correo : null;
        $telefono_val = $telefono !== '' ? $telefono : null;
        $eps_val      = $eps !== '' ? $eps : null;
        $rh_val       = $rh !== '' ? $rh : null;
        $division_val = $division !== '' ? $division : null;
        $id_grado     = 1; // Blanco por defecto
        $id_categoria = 1; // Inicial por defecto
        $activo       = 0; // Pendiente de revisión por administración

        $stmt = $db->prepare(
            "INSERT INTO estudiante 
             (id_grado, id_categoria, id_grupo, id_maestro, nombre, apellido, tipo_documento, num_doc, telefono, fecha_nacimiento, eps, rh, peso, division, correo, clave, activo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)"
        );
        $stmt->bind_param(
            "iiiissssssssdsi",
            $id_grado, $id_categoria, $id_grupo, $id_maestro,
            $nombre, $apellido, $tipo_doc, $num_doc,
            $telefono_val, $fecha_nac, $eps_val, $rh_val,
            $peso, $division_val, $correo_val, $activo
        );
        $stmt->execute();
        $stmt->close();

        // Registrar notificación para el administrador
        $nombreCompleto = trim($nombre . ' ' . $apellido);
        Notificacion::registrar(
            'caracterizacion_nueva',
            'Nuevo Alumno Registrado',
            "El estudiante {$nombreCompleto} (Doc: {$num_doc}) completó su ficha inicial de caracterización.",
            '/admin/registros'
        );

        $this->redirect('/caracterizacion?doc=' . urlencode($num_doc) . '&nuevo=1');
    }
}


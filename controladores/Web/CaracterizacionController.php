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

        $this->view('web/caracterizacion', [
            'usuario' => $usuario,
            'rol'     => $rol,
            'num_doc' => $num_doc,
            'error'   => $error,
            'success' => $success,
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

        $this->redirect('/caracterizacion?doc=' . urlencode($num_doc) . '&ok=1');
    }
}

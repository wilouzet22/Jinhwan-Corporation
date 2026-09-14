<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Grupo.php';

class AdminMaestrosController extends Controller {

    private $sedeModel;
    private $grupoModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();

        $this->sedeModel  = new Sede();
        $this->grupoModel = new Grupo();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT m.id_maestro as id, m.id_maestro, m.nombre, m.apellido, m.correo, m.telefono,
                       m.num_doc, m.tipo_documento,
                       m.foto_perfil, m.activo, m.permisos_extra,
                       m.descripcion_perfil, m.logros, m.mostrar_en_web,
                       s.nombre as sede_nombre, s.id_sede,
                       GROUP_CONCAT(DISTINCT g.nombre SEPARATOR ', ') as grupos_asignados
                FROM maestro m
                LEFT JOIN sedes s ON m.id_sede = s.id_sede
                LEFT JOIN grupos g ON g.id_maestro = m.id_maestro
                GROUP BY m.id_maestro, s.nombre, s.id_sede
                ORDER BY m.apellido, m.nombre";

        $result = $db->query($sql);
        $maestros = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $sedes  = $this->sedeModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view('administracion/maestros', [
            'maestros'    => $maestros,
            'sedes_list'  => $sedes,
            'grupos_list' => $grupos,
            'page_title'  => 'Gestión de Maestros',
            'current_page'=> 'maestros'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance()->getConnection();

            $foto_perfil = null;
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['foto_perfil']['size'] <= 2*1024*1024) {
                    $newName = md5(time() . $_FILES['foto_perfil']['name']) . '.' . $ext;
                    $dir = __DIR__ . '/../../public/uploads/perfiles/';
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $dir . $newName)) {
                        $foto_perfil = $newName;
                    }
                }
            }

            $permisos = json_encode([
                'sedes'      => isset($_POST['permiso_sedes']),
                'registros'  => isset($_POST['permiso_registros']),
                'ascensos'   => isset($_POST['permiso_ascensos']),
                'calendario' => isset($_POST['permiso_calendario']),
                'galeria'    => isset($_POST['permiso_galeria']),
            ]);

            $nombre   = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $tipo_doc = $_POST['tipo_documento'] ?? 'CC';
            $num_doc  = $_POST['numero_documento'] ?? '';
            $correo   = strtolower(trim($_POST['correo'] ?? ''));
            $telefono = $_POST['telefono'] ?? null;
            $id_sede  = !empty($_POST['id_sede']) ? (int)$_POST['id_sede'] : null;
            $desc     = $_POST['descripcion_perfil'] ?? null;
            $logros   = $_POST['logros'] ?? null;
            $web      = isset($_POST['mostrar_en_web']) ? 1 : 0;
            $activo   = isset($_POST['activo']) ? 1 : 1;
            $clave    = !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : password_hash('jinhwa2024', PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO maestro (id_sede, nombre, apellido, tipo_documento, num_doc, telefono, correo, clave, activo, permisos_extra, descripcion_perfil, logros, mostrar_en_web, foto_perfil)
                                  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("isssssssisssis",
                $id_sede, $nombre, $apellido, $tipo_doc, $num_doc,
                $telefono, $correo, $clave, $activo,
                $permisos, $desc, $logros, $web, $foto_perfil
            );
            $stmt->execute();
            $stmt->close();

            $this->redirect('/admin/maestros');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $db = Database::getInstance()->getConnection();

            $r = $db->query("SELECT foto_perfil FROM maestro WHERE id_maestro = $id LIMIT 1");
            $foto_perfil = ($r && $row = $r->fetch_assoc()) ? $row['foto_perfil'] : null;

            if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] === '1') {
                if (!empty($foto_perfil) && file_exists(__DIR__ . '/../../public/uploads/perfiles/' . $foto_perfil)) {
                    unlink(__DIR__ . '/../../public/uploads/perfiles/' . $foto_perfil);
                }
                $foto_perfil = null;
            }

            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['foto_perfil']['size'] <= 2*1024*1024) {
                    $newName = md5(time() . $_FILES['foto_perfil']['name']) . '.' . $ext;
                    $dir = __DIR__ . '/../../public/uploads/perfiles/';
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $dir . $newName)) {
                        if (!empty($foto_perfil) && file_exists($dir . $foto_perfil)) unlink($dir . $foto_perfil);
                        $foto_perfil = $newName;
                    }
                }
            }

            $permisos = json_encode([
                'sedes'      => isset($_POST['permiso_sedes']),
                'registros'  => isset($_POST['permiso_registros']),
                'ascensos'   => isset($_POST['permiso_ascensos']),
                'calendario' => isset($_POST['permiso_calendario']),
                'galeria'    => isset($_POST['permiso_galeria']),
            ]);

            $nombre   = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $tipo_doc = $_POST['tipo_documento'] ?? 'CC';
            $num_doc  = $_POST['numero_documento'] ?? '';
            $correo   = strtolower(trim($_POST['correo'] ?? ''));
            $telefono = $_POST['telefono'] ?? null;
            $id_sede  = !empty($_POST['id_sede']) ? (int)$_POST['id_sede'] : null;
            $desc     = $_POST['descripcion_perfil'] ?? null;
            $logros   = $_POST['logros'] ?? null;
            $web      = isset($_POST['mostrar_en_web']) ? 1 : 0;
            $activo   = isset($_POST['activo']) ? 1 : 0;

            $stmt = $db->prepare("UPDATE maestro SET id_sede=?, nombre=?, apellido=?, tipo_documento=?, num_doc=?, telefono=?, correo=?, activo=?, permisos_extra=?, descripcion_perfil=?, logros=?, mostrar_en_web=?, foto_perfil=? WHERE id_maestro=?");
            $stmt->bind_param("issssssisssisi",
                $id_sede, $nombre, $apellido, $tipo_doc, $num_doc,
                $telefono, $correo, $activo,
                $permisos, $desc, $logros, $web, $foto_perfil, $id
            );
            $stmt->execute();
            $stmt->close();

            if (!empty($_POST['clave'])) {
                $hash = password_hash($_POST['clave'], PASSWORD_DEFAULT);
                $db->query("UPDATE maestro SET clave='$hash' WHERE id_maestro=$id");
            }

            $this->redirect('/admin/maestros');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $db = Database::getInstance()->getConnection();
            $db->query("DELETE FROM maestro WHERE id_maestro = $id");
            $this->redirect('/admin/maestros');
        }
    }
}

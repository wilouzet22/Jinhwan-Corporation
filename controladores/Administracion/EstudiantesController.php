<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Grupo.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Categoria.php';
include_once __DIR__ . '/../../modelos/MultimediaGaleria.php';

class AdminEstudiantesController extends Controller {

    private $usuarioModel;
    private $sedeModel;
    private $grupoModel;
    private $nivelModel;
    private $categoriaModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();

        $this->usuarioModel   = new Usuario();
        $this->sedeModel      = new Sede();
        $this->grupoModel     = new Grupo();
        $this->nivelModel     = new Nivel();
        $this->categoriaModel = new Categoria();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        // Solo estudiantes
        $sql = "SELECT e.id_estudiante as id, e.nombre, e.apellido, e.correo, e.telefono,
                       e.num_doc as numero_documento, e.tipo_documento, e.fecha_nacimiento,
                       e.foto_perfil, e.activo,
                       e.peso, e.division, e.eps, e.rh,
                       g.nombre as grupo_nombre, g.id_grupo,
                       gr.nombre as grado_nombre, gr.id_grado,
                       c.nombre as categoria_nombre,
                       s.nombre as sede_nombre, s.id_sede
                FROM estudiante e
                LEFT JOIN grupos g ON e.id_grupo = g.id_grupo
                LEFT JOIN grados gr ON e.id_grado = gr.id_grado
                LEFT JOIN categorias c ON e.id_categoria = c.id_categoria
                LEFT JOIN sedes s ON g.id_sede = s.id_sede
                ORDER BY e.apellido, e.nombre";

        $result = $db->query($sql);
        $estudiantes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $sedes      = $this->sedeModel->getAll();
        $grupos     = $this->grupoModel->getAll();
        $niveles    = $this->nivelModel->getAll();
        $categorias = $this->categoriaModel->getAll();

        $this->view('administracion/estudiantes', [
            'estudiantes'    => $estudiantes,
            'sedes_list'     => $sedes,
            'grupos_list'    => $grupos,
            'niveles_list'   => $niveles,
            'categorias_list'=> $categorias,
            'page_title'     => 'Gestión de Estudiantes',
            'current_page'   => 'estudiantes'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $db = Database::getInstance()->getConnection();
            $nombre    = $_POST['nombre'] ?? '';
            $apellido  = $_POST['apellido'] ?? '';
            $tipo_doc  = $_POST['tipo_documento'] ?? 'TI';
            $num_doc   = $_POST['numero_documento'] ?? '';
            $correo    = strtolower(trim($_POST['correo'] ?? ''));
            $telefono  = $_POST['telefono'] ?? null;
            $fnac      = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
            $id_grupo  = !empty($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : 1;
            $id_grado  = !empty($_POST['nivel_id']) ? (int)$_POST['nivel_id'] : 1;
            $id_cat    = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 1;
            $peso      = !empty($_POST['peso']) ? $_POST['peso'] : null;
            $division  = $_POST['division'] ?? null;
            $eps       = $_POST['eps'] ?? null;
            $rh        = $_POST['rh'] ?? null;
            $activo    = isset($_POST['activo']) ? 1 : 0;
            $clave     = !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : password_hash('jinhwa2024', PASSWORD_DEFAULT);

            // Obtener maestro del grupo
            $id_maestro = null;
            $rm = $db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
            if ($rm && $r = $rm->fetch_assoc()) $id_maestro = $r['id_maestro'] ?: null;

            $stmt = $db->prepare("INSERT INTO estudiante (id_grado, id_categoria, id_grupo, id_maestro, nombre, apellido, tipo_documento, num_doc, telefono, fecha_nacimiento, correo, clave, activo, peso, division, eps, rh, foto_perfil)
                                  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("iiiisssssssisssss",
                $id_grado, $id_cat, $id_grupo, $id_maestro,
                $nombre, $apellido, $tipo_doc, $num_doc,
                $telefono, $fnac, $correo, $clave, $activo,
                $peso, $division, $eps, $rh, $foto_perfil
            );
            $stmt->execute();
            $stmt->close();

            if ($newId > 0 && !empty($_POST['url_instagram'])) {
                $multimediaModel = new MultimediaGaleria();
                $multimediaModel->upsert($newId, trim($_POST['url_instagram']));
            }

            $this->redirect('/admin/estudiantes');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $db = Database::getInstance()->getConnection();

            // Foto actual
            $r = $db->query("SELECT foto_perfil FROM estudiante WHERE id_estudiante = $id LIMIT 1");
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

            $nombre   = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $tipo_doc = $_POST['tipo_documento'] ?? 'TI';
            $num_doc  = $_POST['numero_documento'] ?? '';
            $correo   = strtolower(trim($_POST['correo'] ?? ''));
            $telefono = $_POST['telefono'] ?? null;
            $fnac     = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
            $id_grupo = !empty($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : 1;
            $id_grado = !empty($_POST['nivel_id']) ? (int)$_POST['nivel_id'] : 1;
            $id_cat   = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 1;
            $peso     = !empty($_POST['peso']) ? $_POST['peso'] : null;
            $division = $_POST['division'] ?? null;
            $eps      = $_POST['eps'] ?? null;
            $rh       = $_POST['rh'] ?? null;
            $activo   = isset($_POST['activo']) ? 1 : 0;

            // Obtener maestro del grupo
            $id_maestro = null;
            $rm = $db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
            if ($rm && $r2 = $rm->fetch_assoc()) $id_maestro = $r2['id_maestro'] ?: null;

            $stmt = $db->prepare("UPDATE estudiante SET id_grado=?, id_categoria=?, id_grupo=?, id_maestro=?,
                                  nombre=?, apellido=?, tipo_documento=?, num_doc=?, telefono=?, fecha_nacimiento=?,
                                  correo=?, activo=?, peso=?, division=?, eps=?, rh=?, foto_perfil=?
                                  WHERE id_estudiante=?");
            $stmt->bind_param("iiiissssssisssssi",
                $id_grado, $id_cat, $id_grupo, $id_maestro,
                $nombre, $apellido, $tipo_doc, $num_doc,
                $telefono, $fnac, $correo, $activo,
                $peso, $division, $eps, $rh, $foto_perfil, $id
            );
            $stmt->execute();
            $stmt->close();

            if (!empty($_POST['clave'])) {
                $hash = password_hash($_POST['clave'], PASSWORD_DEFAULT);
                $db->query("UPDATE estudiante SET clave='$hash' WHERE id_estudiante=$id");
            }

            if (isset($_POST['url_instagram'])) {
                $multimediaModel = new MultimediaGaleria();
                $multimediaModel->upsert($id, trim($_POST['url_instagram']));
            }

            $this->redirect('/admin/estudiantes');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $db = Database::getInstance()->getConnection();
            $db->query("DELETE FROM estudiante WHERE id_estudiante = $id");
            $this->redirect('/admin/estudiantes');
        }
    }

    public function deleteBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = array_filter(array_map('intval', $_POST['ids'] ?? []), fn($i) => $i > 0);
            if (!empty($ids)) {
                $db = Database::getInstance()->getConnection();
                $in = implode(',', $ids);
                $db->query("DELETE FROM estudiante WHERE id_estudiante IN ($in)");
            }
            $this->redirect('/admin/estudiantes');
        }
    }
}

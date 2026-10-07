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
            try {
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
                $nombre    = trim($_POST['nombre'] ?? '');
                $apellido  = trim($_POST['apellido'] ?? '');
                $tipo_doc  = $_POST['tipo_documento'] ?? 'TI';
                
                $num_doc   = trim($_POST['numero_documento'] ?? '');
                $num_doc   = !empty($num_doc) ? $num_doc : null;

                $correo    = strtolower(trim($_POST['correo'] ?? ''));
                $correo    = !empty($correo) ? $correo : null;

                $telefono  = trim($_POST['telefono'] ?? '');
                $telefono  = !empty($telefono) ? $telefono : null;

                $fnac      = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
                $id_grupo  = !empty($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : null;
                $id_grado  = !empty($_POST['nivel_id']) ? (int)$_POST['nivel_id'] : null;
                $id_cat    = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
                $peso      = !empty($_POST['peso']) ? $_POST['peso'] : null;
                $division  = !empty($_POST['division']) ? trim($_POST['division']) : null;
                $eps       = !empty($_POST['eps']) ? trim($_POST['eps']) : null;
                $rh        = !empty($_POST['rh']) ? trim($_POST['rh']) : null;
                $activo    = isset($_POST['activo']) ? 1 : 0;
                $clave     = !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : password_hash('jinhwa2024', PASSWORD_DEFAULT);

                // Obtener maestro del grupo si existe
                $id_maestro = null;
                if ($id_grupo) {
                    $rm = $db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
                    if ($rm && $r = $rm->fetch_assoc()) $id_maestro = $r['id_maestro'] ?: null;
                }

                $stmt = $db->prepare("INSERT INTO estudiante (id_grado, id_categoria, id_grupo, id_maestro, nombre, apellido, tipo_documento, num_doc, telefono, fecha_nacimiento, correo, clave, activo, peso, division, eps, rh, foto_perfil)
                                      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                
                // 18 parámetros: 4 enteros, 8 strings (nombre..clave), 1 entero (activo), 5 strings (peso..foto_perfil)
                $stmt->bind_param("iiiissssssssisssss",
                    $id_grado, $id_cat, $id_grupo, $id_maestro,
                    $nombre, $apellido, $tipo_doc, $num_doc,
                    $telefono, $fnac, $correo, $clave, $activo,
                    $peso, $division, $eps, $rh, $foto_perfil
                );
                $stmt->execute();
                $newId = $stmt->insert_id ?: $db->insert_id;
                $stmt->close();

                if ($newId > 0 && !empty($_POST['url_instagram'])) {
                    $multimediaModel = new MultimediaGaleria();
                    $multimediaModel->upsert($newId, trim($_POST['url_instagram']));
                }

                $_SESSION['flash_success'] = 'Estudiante creado exitosamente.';
            } catch (\Throwable $e) {
                error_log("Error al crear estudiante: " . $e->getMessage());
                $_SESSION['flash_error'] = "Error al crear el estudiante: " . $e->getMessage();
            }

            $this->redirect('/admin/estudiantes');
        }
        $this->redirect('/admin/estudiantes');
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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

                $nombre   = trim($_POST['nombre'] ?? '');
                $apellido = trim($_POST['apellido'] ?? '');
                $tipo_doc = $_POST['tipo_documento'] ?? 'TI';

                $num_doc  = trim($_POST['numero_documento'] ?? '');
                $num_doc  = !empty($num_doc) ? $num_doc : null;

                $correo   = strtolower(trim($_POST['correo'] ?? ''));
                $correo   = !empty($correo) ? $correo : null;

                $telefono = trim($_POST['telefono'] ?? '');
                $telefono = !empty($telefono) ? $telefono : null;

                $fnac     = !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
                $id_grupo = !empty($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : null;
                $id_grado = !empty($_POST['nivel_id']) ? (int)$_POST['nivel_id'] : null;
                $id_cat   = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
                $peso     = !empty($_POST['peso']) ? $_POST['peso'] : null;
                $division = !empty($_POST['division']) ? trim($_POST['division']) : null;
                $eps      = !empty($_POST['eps']) ? trim($_POST['eps']) : null;
                $rh       = !empty($_POST['rh']) ? trim($_POST['rh']) : null;
                $activo   = isset($_POST['activo']) ? 1 : 0;

                // Obtener maestro del grupo si existe
                $id_maestro = null;
                if ($id_grupo) {
                    $rm = $db->query("SELECT id_maestro FROM grupos WHERE id_grupo = $id_grupo LIMIT 1");
                    if ($rm && $r2 = $rm->fetch_assoc()) $id_maestro = $r2['id_maestro'] ?: null;
                }

                $stmt = $db->prepare("UPDATE estudiante SET id_grado=?, id_categoria=?, id_grupo=?, id_maestro=?,
                                      nombre=?, apellido=?, tipo_documento=?, num_doc=?, telefono=?, fecha_nacimiento=?,
                                      correo=?, activo=?, peso=?, division=?, eps=?, rh=?, foto_perfil=?
                                      WHERE id_estudiante=?");
                
                // 18 parámetros: 4 enteros, 7 strings (nombre..correo), 1 entero (activo), 5 strings (peso..foto_perfil), 1 entero (id)
                $stmt->bind_param("iiiisssssssisssssi",
                    $id_grado, $id_cat, $id_grupo, $id_maestro,
                    $nombre, $apellido, $tipo_doc, $num_doc,
                    $telefono, $fnac, $correo, $activo,
                    $peso, $division, $eps, $rh, $foto_perfil, $id
                );
                $stmt->execute();
                $stmt->close();

                if (!empty($_POST['clave'])) {
                    $hash = password_hash($_POST['clave'], PASSWORD_DEFAULT);
                    $stmtClave = $db->prepare("UPDATE estudiante SET clave=? WHERE id_estudiante=?");
                    $stmtClave->bind_param("si", $hash, $id);
                    $stmtClave->execute();
                    $stmtClave->close();
                }

                if (isset($_POST['url_instagram'])) {
                    $multimediaModel = new MultimediaGaleria();
                    $multimediaModel->upsert($id, trim($_POST['url_instagram']));
                }

                $_SESSION['flash_success'] = 'Estudiante actualizado exitosamente.';
            } catch (\Throwable $e) {
                error_log("Error al actualizar estudiante: " . $e->getMessage());
                $_SESSION['flash_error'] = "Error al actualizar el estudiante: " . $e->getMessage();
            }

            $this->redirect('/admin/estudiantes');
        }
        $this->redirect('/admin/estudiantes');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = (int)$_POST['id'];
                $db = Database::getInstance()->getConnection();
                $db->query("DELETE FROM estudiante WHERE id_estudiante = $id");
                $_SESSION['flash_success'] = 'Estudiante eliminado exitosamente.';
            } catch (\Throwable $e) {
                error_log("Error al eliminar estudiante: " . $e->getMessage());
                $_SESSION['flash_error'] = "Error al eliminar el estudiante: " . $e->getMessage();
            }
            $this->redirect('/admin/estudiantes');
        }
        $this->redirect('/admin/estudiantes');
    }

    public function deleteBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ids = array_filter(array_map('intval', $_POST['ids'] ?? []), fn($i) => $i > 0);
                if (!empty($ids)) {
                    $db = Database::getInstance()->getConnection();
                    $in = implode(',', $ids);
                    $db->query("DELETE FROM estudiante WHERE id_estudiante IN ($in)");
                    $_SESSION['flash_success'] = 'Estudiantes eliminados exitosamente.';
                }
            } catch (\Throwable $e) {
                error_log("Error al eliminar estudiantes: " . $e->getMessage());
                $_SESSION['flash_error'] = "Error al eliminar estudiantes: " . $e->getMessage();
            }
            $this->redirect('/admin/estudiantes');
        }
        $this->redirect('/admin/estudiantes');
    }
}

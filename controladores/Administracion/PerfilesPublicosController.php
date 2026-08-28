<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/MultimediaGaleria.php';

class AdminPerfilesPublicosController extends Controller {

    private $usuarioModel;
    private $multimediaModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();

        $this->usuarioModel = new Usuario();
        $this->multimediaModel = new MultimediaGaleria();
    }

    public function index() {
        $miembros = $this->usuarioModel->getAllWithPublicProfileInfo();

        $stats = [
            'total'    => count($miembros),
            'visibles' => count(array_filter($miembros, fn($m) => (int)($m['mostrar_en_web'] ?? 0) === 1)),
            'ocultos'  => count(array_filter($miembros, fn($m) => (int)($m['mostrar_en_web'] ?? 0) === 0)),
        ];

        $this->view('administracion/perfiles_publicos', [
            'miembros'     => $miembros,
            'stats'        => $stats,
            'page_title'   => 'Control de Perfiles Públicos',
            'current_page' => 'perfiles_publicos'
        ]);
    }

    public function toggleVisibility() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        $nuevoEstado = $this->usuarioModel->togglePublicVisibility($id);
        echo json_encode([
            'success' => true,
            'visible' => $nuevoEstado,
            'message' => $nuevoEstado ? 'Perfil ahora visible en la web' : 'Perfil ocultado de la web'
        ]);
        exit;
    }

    public function bulkVisibility() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? [];
            $action = $_POST['action'] ?? 'show'; // 'show' or 'hide'

            if (!empty($ids) && is_array($ids)) {
                $visible = ($action === 'show');
                $this->usuarioModel->setBulkPublicVisibility($ids, $visible);
            }

            $this->redirect('/admin/perfiles-publicos?msg=bulk_updated');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $mostrar_en_web = isset($_POST['mostrar_en_web']) ? 1 : 0;
            $descripcion = $_POST['descripcion_perfil'] ?? '';
            $url_instagram = $_POST['url_instagram'] ?? '';
            $rol = $_POST['rol'] ?? '';

            $this->usuarioModel->updatePublicProfile($id, $mostrar_en_web, $descripcion, $rol);

            if (!empty($url_instagram)) {
                $this->multimediaModel->upsert($id, $url_instagram);
            } else {
                $this->multimediaModel->upsert($id, '');
            }

            $this->redirect('/admin/perfiles-publicos?msg=profile_saved');
        }
    }
}

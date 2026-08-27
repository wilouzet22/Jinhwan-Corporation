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

        $this->view('administracion/perfiles_publicos', [
            'miembros'     => $miembros,
            'page_title'   => 'Perfiles Públicos - Administración',
            'current_page' => 'perfiles_publicos'
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
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

            $this->redirect('/admin/perfiles-publicos');
        }
    }
}

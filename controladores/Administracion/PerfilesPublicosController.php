<?php
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\MultimediaGaleria;

class PerfilesPublicosController extends Controller {

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
            'miembros'    => $miembros,
            'page_title'  => 'Perfiles Públicos - Administración',
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

            // Update user profile info and role
            $this->usuarioModel->updatePublicProfile($id, $mostrar_en_web, $descripcion, $rol);

            // Upsert multimedia URL
            if (!empty($url_instagram)) {
                $this->multimediaModel->upsert($id, $url_instagram);
            } else {
                // If empty, we might want to clear it, but upsert handles empty as well.
                $this->multimediaModel->upsert($id, '');
            }

            $this->redirect('/admin/perfiles-publicos');
        }
    }
}

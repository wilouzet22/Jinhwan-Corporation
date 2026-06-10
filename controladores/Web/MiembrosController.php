<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Usuario;

class MiembrosController extends Controller {

    public function index() {
        $usuarioModel = new Usuario();
        $miembros = $usuarioModel->getPublicProfiles();

        $this->view('web/miembros', [
            'page_title' => 'Nuestros Miembros',
            'miembros' => $miembros
        ]);
    }
}

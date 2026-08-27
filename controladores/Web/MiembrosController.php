<?php

include_once __DIR__ . '/../../modelos/Usuario.php';

class WebMiembrosController extends Controller {

    public function index() {
        $usuarioModel = new Usuario();
        $miembros = $usuarioModel->getPublicProfiles();

        $this->view('web/miembros', [
            'page_title' => 'Nuestros Miembros',
            'miembros'   => $miembros
        ]);
    }
}

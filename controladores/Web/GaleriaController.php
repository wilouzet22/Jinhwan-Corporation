<?php

include_once __DIR__ . '/../../modelos/MultimediaGaleria.php';

class WebGaleriaController extends Controller {

    public function index() {
        $galeriaModel = new MultimediaGaleria();
        $publicaciones = $galeriaModel->getAllGeneral();

        $this->view('web/galeria', [
            'page_title'    => 'Galería Multimedia',
            'publicaciones' => $publicaciones
        ]);
    }
}

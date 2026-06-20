<?php
namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\MultimediaGaleria;

class GaleriaController extends Controller {

    public function index() {
        $galeriaModel = new MultimediaGaleria();
        $publicaciones = $galeriaModel->getAllGeneral();

        $this->view('web/galeria', [
            'page_title' => 'Galería Multimedia',
            'publicaciones' => $publicaciones
        ]);
    }
}

<?php
namespace App\Controllers\Web;

use App\Core\Controller;

class GaleriaController extends Controller {
    public function index() {
        // Scan for images
        if (is_dir('public/img/galeria')) {
            $path = 'public/img/galeria/*.jpg';
        } else {
             $path = 'img/galeria/*.jpg'; 
        }
        
        $imagenes = glob($path);
        
        $this->view('web/galeria', [
            'page_title' => 'Galería Multimedia', 
            'imagenes' => $imagenes
        ]);
    }
}

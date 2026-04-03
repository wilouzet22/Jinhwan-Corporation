<?php
namespace App\Controllers\Web;

use App\Core\Controller;

class InicioController extends Controller {
    public function index() {
        $this->view('web/inicio', [
            'page_title' => 'Jinnwhan Organization - Taekwondo'
        ]);
    }
}

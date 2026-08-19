<?php

namespace App\Controllers\Web;

use App\Core\Controller;

class PaginaController extends Controller {

    public function nosotros() {
        $this->view('web/nosotros', ['page_title' => 'Nosotros']);
    }
}

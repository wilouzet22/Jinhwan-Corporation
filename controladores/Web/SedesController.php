<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Sede;

class SedesController extends Controller {

    public function index() {
        $sedeModel = new Sede();
        $sedes     = $sedeModel->getAll(); 

        $this->view('web/sedes', [
            'sedes'      => $sedes,
            'page_title' => 'Nuestras Sedes'
        ]);
    }
}

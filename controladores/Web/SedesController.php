<?php

include_once __DIR__ . '/../../modelos/Sede.php';

class WebSedesController extends Controller {

    public function index() {
        $sedeModel = new Sede();
        $sedes     = $sedeModel->getAll(); 

        $this->view('web/sedes', [
            'sedes'      => $sedes,
            'page_title' => 'Nuestras Sedes'
        ]);
    }
}

<?php

class WebInicioController extends Controller {

    public function index() {
        $this->view('web/inicio', [
            'page_title' => 'Jinnwhan Organization - Taekwondo'
        ]);
    }

    public function portal() {
        $this->view('web/portal', [
            'page_title' => 'Bienvenido - Jinhwa Corporation'
        ]);
    }
}

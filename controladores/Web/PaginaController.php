<?php

class WebPaginaController extends Controller {

    public function nosotros() {
        $this->view('web/nosotros', ['page_title' => 'Nosotros']);
    }
}

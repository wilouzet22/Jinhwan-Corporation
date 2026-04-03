<?php
namespace App\Controllers\Web;

use App\Core\Controller;

class InstructoresController extends Controller {
    public function index() {
        $this->view('web/instructores', ['page_title' => 'Nuestros Instructores']);
    }
}

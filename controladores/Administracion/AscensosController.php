<?php

namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Teoria;
use App\Models\Nivel;

class AscensosController extends Controller {

    private $teoriaModel;
    private $nivelModel;

    public function __construct() {
        Security::verifyPermission('ascensos'); 

        $this->teoriaModel = new Teoria();
        $this->nivelModel  = new Nivel();
    }

    public function index() {
        $teorias = $this->teoriaModel->getAll(); 
        $niveles = $this->nivelModel->getAll();  

        $this->view('administracion/ascensos', [
            'teorias'      => $teorias,
            'niveles'      => $niveles,
            'page_title'   => 'Administración de Teoría y Ascensos',
            'current_page' => 'ascensos'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo'      => $_POST['titulo'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'url_video'   => $_POST['url'] ?? '',    
                'nivel_id'    => $_POST['nivel_id'] ?? 1
            ];

            $this->teoriaModel->create($data);
            $this->redirect('/admin/ascensos');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $data = [
                'titulo'      => $_POST['titulo'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'url_video'   => $_POST['url'] ?? '',
                'nivel_id'    => $_POST['nivel_id'] ?? 1
            ];

            $this->teoriaModel->update($id, $data);
            $this->redirect('/admin/ascensos');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $this->teoriaModel->delete($id);
            $this->redirect('/admin/ascensos');
        }
    }
}

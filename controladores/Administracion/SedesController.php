<?php

include_once __DIR__ . '/../../modelos/Sede.php';

class AdminSedesController extends Controller {

    private $sedeModel;

    public function __construct() {
        Security::verifySession(); 
        Security::verifyPermission('sedes');
        $this->sedeModel = new Sede();
    }

    public function index() {
        $sedes = $this->sedeModel->getAll(); 

        $this->view('administracion/sedes', [
            'sedes'        => $sedes,
            'page_title'   => 'Administración de Sedes',
            'current_page' => 'sedes'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'    => $_POST['nombre'],
                'direccion' => $_POST['direccion'],
                'telefono'  => $_POST['telefono']
            ];

            $this->sedeModel->create($data);
            $this->redirect('/admin/sedes');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id   = $_POST['id'];
            $data = [
                'nombre'    => $_POST['nombre'],
                'direccion' => $_POST['direccion'],
                'telefono'  => $_POST['telefono']
            ];

            $this->sedeModel->update($id, $data);
            $this->redirect('/admin/sedes');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->sedeModel->delete($id);
            $this->redirect('/admin/sedes');
        }
    }
}

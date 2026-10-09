<?php

include_once __DIR__ . '/../../modelos/Teoria.php';
include_once __DIR__ . '/../../modelos/Nivel.php';

class AdminTeoriaController extends Controller {

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
        $tipos   = $this->teoriaModel->getTipos();

        $this->view('administracion/teoria', [
            'teorias'      => $teorias,
            'niveles'      => $niveles,
            'tipos'        => $tipos,
            'page_title'   => 'Administración de Teoría y Ascensos',
            'current_page' => 'ascensos'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_id = $_POST['tipo_id'] ?? 1;
            $nuevo_tipo = trim($_POST['nuevo_tipo_nombre'] ?? '');
            if ($tipo_id === 'new' || !empty($nuevo_tipo)) {
                $tipo_id = $this->teoriaModel->findOrCreateTipo($nuevo_tipo);
            }

            $data = [
                'titulo'      => $_POST['titulo'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'url_video'   => $_POST['url'] ?? '',
                'nivel_id'    => $_POST['nivel_id'] ?? 1,
                'tipo_id'     => (int)$tipo_id
            ];

            $this->teoriaModel->create($data);
            $this->redirect('/admin/teoria');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $tipo_id = $_POST['tipo_id'] ?? 1;
            $nuevo_tipo = trim($_POST['nuevo_tipo_nombre'] ?? '');
            if ($tipo_id === 'new' || !empty($nuevo_tipo)) {
                $tipo_id = $this->teoriaModel->findOrCreateTipo($nuevo_tipo);
            }

            $data = [
                'titulo'      => $_POST['titulo'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'url_video'   => $_POST['url'] ?? '',
                'nivel_id'    => $_POST['nivel_id'] ?? 1,
                'tipo_id'     => (int)$tipo_id
            ];

            $this->teoriaModel->update($id, $data);
            $this->redirect('/admin/teoria');
        }
    }

    public function storeTipo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            if (!empty($nombre)) {
                $this->teoriaModel->findOrCreateTipo($nombre, $descripcion);
            }
            $this->redirect('/admin/teoria');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $this->teoriaModel->delete($id);
            $this->redirect('/admin/teoria');
        }
    }
}

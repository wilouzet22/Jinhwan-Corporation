<?php
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\Sede;
use App\Models\Nivel;
use App\Config\Roles;

class MiembrosController extends Controller {
    private $usuarioModel;
    private $sedeModel;
    private $nivelModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();
        
        $this->usuarioModel = new Usuario();
        $this->sedeModel = new Sede();
        $this->nivelModel = new Nivel();
    }

    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails();
        $sedes = $this->sedeModel->getAll();
        $niveles = $this->nivelModel->getAll();

        $this->view('administracion/miembros', [
            'miembros' => $miembros,
            'sedes_list' => $sedes,
            'niveles_list' => $niveles,
            'page_title' => 'Administración de Miembros',
            'current_page' => 'miembros'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'tipo_documento' => $_POST['tipo_documento'],
                'numero_documento' => $_POST['numero_documento'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'nivel_id' => $_POST['nivel_id'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'rol_id' => $_POST['rol_id'] ?? Roles::ESTUDIANTE,
                'sede_id' => $_POST['sede_id']
            ];
            
            $this->usuarioModel->create($data);
            $this->redirect('/admin/miembros');
        }
    }

    public function update() {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'tipo_documento' => $_POST['tipo_documento'],
                'numero_documento' => $_POST['numero_documento'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'nivel_id' => $_POST['nivel_id'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'rol_id' => $_POST['rol_id'],
                'sede_id' => $_POST['sede_id']
            ];
            
            $this->usuarioModel->update($id, $data);
            $this->redirect('/admin/miembros');
        }
    }

    public function delete() {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->usuarioModel->delete($id);
            $this->redirect('/admin/miembros');
        }
    }
}

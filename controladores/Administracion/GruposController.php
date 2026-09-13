<?php

include_once __DIR__ . '/../../modelos/Grupo.php';
include_once __DIR__ . '/../../modelos/Sede.php';

class AdminGruposController extends Controller {

    private $grupoModel;
    private $sedeModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();

        $this->grupoModel = new Grupo();
        $this->sedeModel  = new Sede();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();

        $grupos = $this->grupoModel->getAll();
        $sedes  = $this->sedeModel->getAll();

        // Maestros disponibles para asignar
        $result = $db->query("SELECT id_maestro as id, CONCAT(nombre, ' ', apellido) as nombre_completo FROM maestro WHERE activo = 1 ORDER BY apellido");
        $maestros = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $this->view('administracion/grupos', [
            'grupos'      => $grupos,
            'sedes_list'  => $sedes,
            'maestros_list'=> $maestros,
            'page_title'  => 'Gestión de Grupos',
            'current_page'=> 'grupos'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_sede'    => $_POST['id_sede'] ?? null,
                'id_maestro' => !empty($_POST['id_maestro']) ? (int)$_POST['id_maestro'] : null,
                'nombre'     => $_POST['nombre'] ?? '',
                'descripcion'=> $_POST['descripcion'] ?? null,
                'horario'    => $_POST['horario'] ?? null,
                'activo'     => isset($_POST['activo']) ? 1 : 1,
            ];
            $this->grupoModel->create($data);
            $this->redirect('/admin/grupos');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $data = [
                'id_sede'    => $_POST['id_sede'] ?? null,
                'id_maestro' => !empty($_POST['id_maestro']) ? (int)$_POST['id_maestro'] : null,
                'nombre'     => $_POST['nombre'] ?? '',
                'descripcion'=> $_POST['descripcion'] ?? null,
                'horario'    => $_POST['horario'] ?? null,
                'activo'     => isset($_POST['activo']) ? 1 : 0,
            ];
            $this->grupoModel->update($id, $data);
            $this->redirect('/admin/grupos');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $this->grupoModel->delete($id);
            $this->redirect('/admin/grupos');
        }
    }
}

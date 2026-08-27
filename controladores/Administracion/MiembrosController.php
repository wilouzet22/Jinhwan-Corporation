<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Sede.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Categoria.php';
include_once __DIR__ . '/../../modelos/MultimediaGaleria.php';

class AdminMiembrosController extends Controller {

    private $usuarioModel;
    private $sedeModel;
    private $nivelModel;
    private $categoriaModel;

    public function __construct() {
        Security::verifySession(); 
        Security::verifyAdmin();   

        $this->usuarioModel   = new Usuario();
        $this->sedeModel      = new Sede();
        $this->nivelModel     = new Nivel();
        $this->categoriaModel = new Categoria();
    }

    public function index() {
        $miembros   = $this->usuarioModel->getAllWithDetails(); 
        $sedes      = $this->sedeModel->getAll();              
        $niveles    = $this->nivelModel->getAll();             
        $categorias = $this->categoriaModel->getAll();          
 
        $this->view('administracion/miembros', [
            'miembros'       => $miembros,
            'sedes_list'     => $sedes,
            'niveles_list'   => $niveles,
            'categorias_list'=> $categorias,
            'page_title'     => 'Administración de Miembros',
            'current_page'   => 'miembros'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $foto_perfil = null;
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
                $fileName = $_FILES['foto_perfil']['name'];
                $fileSize = $_FILES['foto_perfil']['size'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 2 * 1024 * 1024) {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                        $foto_perfil = $newFileName;
                    }
                }
            }

            $data = [
                'nombre'            => $_POST['nombre'],
                'apellido'          => $_POST['apellido'],
                'tipo_documento'    => $_POST['tipo_documento'],
                'numero_documento'  => $_POST['numero_documento'],
                'fecha_nacimiento'  => $_POST['fecha_nacimiento'],
                'nivel_id'          => $_POST['nivel_id'],
                'telefono'          => $_POST['telefono'],
                'correo'            => $_POST['correo'],
                'rol_id'            => $_POST['rol_id'] ?? Roles::ESTUDIANTE,
                'sede_id'           => $_POST['sede_id'],
                'categoria_id'      => $_POST['categoria_id'] ?? 1,
                'peso'              => $_POST['peso'] ?? null,
                'division'          => $_POST['division'] ?? null,
                'ctgc'              => $_POST['ctgc'] ?? null,
                'eps'               => $_POST['eps'] ?? null,
                'rh'                => $_POST['rh'] ?? null,
                'descripcion_perfil'=> $_POST['descripcion_perfil'] ?? null,
                'logros'            => $_POST['logros'] ?? null,
                'mostrar_en_web'    => isset($_POST['mostrar_en_web']) ? 1 : 0,
                'foto_perfil'       => $foto_perfil
            ];
 
            if ($data['rol_id'] == Roles::MAESTRO || $data['rol_id'] == Roles::PROFESOR || $data['rol_id'] == Roles::MONITOR) {
                $permisos = [
                    'sedes' => isset($_POST['permiso_sedes']),
                    'registros' => isset($_POST['permiso_registros']),
                    'ascensos' => isset($_POST['permiso_ascensos']),
                    'calendario' => isset($_POST['permiso_calendario']),
                    'galeria' => isset($_POST['permiso_galeria']),
                    'reportes' => isset($_POST['permiso_reportes'])
                ];
                $data['permisos_extra'] = json_encode($permisos);
            } else {
                $data['permisos_extra'] = null;
            }
 
            $this->usuarioModel->create($data);
            $newId = Database::getInstance()->getConnection()->insert_id;
            if ($newId > 0 && isset($_POST['url_instagram'])) {
                $multimediaModel = new MultimediaGaleria();
                $multimediaModel->upsert($newId, trim($_POST['url_instagram']));
            }
            $this->redirect('/admin/miembros');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
 
            $currentMember = $this->usuarioModel->getById($id);
            $foto_perfil = $currentMember['foto_perfil'] ?? null;

            if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] === '1') {
                if (!empty($foto_perfil)) {
                    $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                    $old_file = $uploadFileDir . $foto_perfil;
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $foto_perfil = null;
            }

            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
                $fileName = $_FILES['foto_perfil']['name'];
                $fileSize = $_FILES['foto_perfil']['size'];
                
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 2 * 1024 * 1024) {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../../public/uploads/perfiles/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                        if (!empty($foto_perfil)) {
                            $old_file = $uploadFileDir . $foto_perfil;
                            if (file_exists($old_file)) {
                                unlink($old_file);
                            }
                        }
                        $foto_perfil = $newFileName;
                    }
                }
            }

            $data = [
                'nombre'            => $_POST['nombre'],
                'apellido'          => $_POST['apellido'],
                'tipo_documento'    => $_POST['tipo_documento'],
                'numero_documento'  => $_POST['numero_documento'],
                'fecha_nacimiento'  => $_POST['fecha_nacimiento'],
                'nivel_id'          => $_POST['nivel_id'],
                'telefono'          => $_POST['telefono'],
                'correo'            => $_POST['correo'],
                'rol_id'            => $_POST['rol_id'],
                'sede_id'           => $_POST['sede_id'],
                'categoria_id'      => $_POST['categoria_id'] ?? 1,
                'peso'              => $_POST['peso'] ?? null,
                'division'          => $_POST['division'] ?? null,
                'ctgc'              => $_POST['ctgc'] ?? null,
                'eps'               => $_POST['eps'] ?? null,
                'rh'                => $_POST['rh'] ?? null,
                'descripcion_perfil'=> $_POST['descripcion_perfil'] ?? null,
                'logros'            => $_POST['logros'] ?? null,
                'mostrar_en_web'    => isset($_POST['mostrar_en_web']) ? 1 : 0,
                'foto_perfil'       => $foto_perfil
            ];

            if ($data['rol_id'] == Roles::MAESTRO || $data['rol_id'] == Roles::PROFESOR || $data['rol_id'] == Roles::MONITOR) {
                $permisos = [
                    'sedes' => isset($_POST['permiso_sedes']),
                    'registros' => isset($_POST['permiso_registros']),
                    'ascensos' => isset($_POST['permiso_ascensos']),
                    'calendario' => isset($_POST['permiso_calendario']),
                    'galeria' => isset($_POST['permiso_galeria']),
                    'reportes' => isset($_POST['permiso_reportes'])
                ];
                $data['permisos_extra'] = json_encode($permisos);
            } else {
                $data['permisos_extra'] = null;
            }
 
            $this->usuarioModel->update($id, $data);
            if (isset($_POST['url_instagram'])) {
                $multimediaModel = new MultimediaGaleria();
                $multimediaModel->upsert($id, trim($_POST['url_instagram']));
            }
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

    public function deleteBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? [];

            $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

            if (!empty($ids)) {
                $this->usuarioModel->deleteBulk($ids);
            }

            $this->redirect('/admin/miembros');
        }
    }
}

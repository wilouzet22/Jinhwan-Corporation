<?php
/**
 * ============================================================
 * CONTROLADOR DE MIEMBROS – ADMINISTRACIÓN (MiembrosController)
 * ============================================================
 * Gestiona el CRUD completo de miembros del club desde el panel
 * de administración.
 *
 * Acceso: requiere sesión activa + rol de Administrador.
 * Rutas:
 *   GET  /admin/miembros         → listar todos los miembros
 *   POST /admin/miembros/create  → crear nuevo miembro
 *   POST /admin/miembros/update  → actualizar miembro existente
 *   POST /admin/miembros/delete  → eliminar miembro
 *
 * Vista: administracion/miembros
 * Modelos usados: Usuario, Sede, Nivel
 * ============================================================
 */
namespace App\Controllers\Administracion;
 
use App\Core\Controller;
use App\Core\Security;
use App\Models\Usuario;
use App\Models\Sede;
use App\Models\Nivel;
use App\Models\Categoria;
use App\Config\Roles;
 
class MiembrosController extends Controller {
 
    /** @var Usuario Modelo para operaciones CRUD sobre miembros */
    private $usuarioModel;
 
    /** @var Sede Modelo para obtener la lista de sedes (dropdown del formulario) */
    private $sedeModel;
 
    /** @var Nivel Modelo para obtener la lista de grados (dropdown del formulario) */
    private $nivelModel;

    /** @var Categoria Modelo para obtener la lista de categorias (dropdown del formulario) */
    private $categoriaModel;
 
    /**
     * Constructor: verifica seguridad e instancia los modelos necesarios.
     * Se ejecuta antes de cualquier método del controlador.
     */
    public function __construct() {
        Security::verifySession(); // Verificar sesión activa
        Security::verifyAdmin();   // Verificar rol de administrador
 
        // Instanciar modelos (cada uno obtiene la conexión BD automáticamente)
        $this->usuarioModel   = new Usuario();
        $this->sedeModel      = new Sede();
        $this->nivelModel     = new Nivel();
        $this->categoriaModel = new Categoria();
    }
 
    /**
     * Lista todos los miembros con sus datos completos.
     * Ruta: GET /admin/miembros
     *
     * Pasa a la vista:
     *   - $miembros      → lista completa de miembros (con sede, nivel, correo)
     *   - $sedes_list    → sedes disponibles para el formulario de asignación
     *   - $niveles_list  → grados disponibles para el formulario de asignación
     *   - $categorias_list → categorías deportivas por edad
     */
    public function index() {
        $miembros   = $this->usuarioModel->getAllWithDetails(); // JOIN con sedes, grados, userlog
        $sedes      = $this->sedeModel->getAll();              // Para el <select> de sedes
        $niveles    = $this->nivelModel->getAll();             // Para el <select> de grados
        $categorias = $this->categoriaModel->getAll();          // Para el <select> de categorías
 
        $this->view('administracion/miembros', [
            'miembros'       => $miembros,
            'sedes_list'     => $sedes,
            'niveles_list'   => $niveles,
            'categorias_list'=> $categorias,
            'page_title'     => 'Administración de Miembros',
            'current_page'   => 'miembros'
        ]);
    }
 
    /**
     * Crea un nuevo miembro desde el formulario de administración.
     * Ruta: POST /admin/miembros/create
     *
     * El miembro se crea como activo = 1 (a diferencia del registro público).
     * La contraseña inicial es el número de documento del miembro (hasheada).
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar foto de perfil
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
            $newId = \App\Config\Database::getInstance()->getConnection()->insert_id;
            if ($newId > 0 && isset($_POST['url_instagram'])) {
                $multimediaModel = new \App\Models\MultimediaGaleria();
                $multimediaModel->upsert($newId, trim($_POST['url_instagram']));
            }
            $this->redirect('/admin/miembros');
        }
    }
 
    /**
     * Actualiza los datos de un miembro existente.
     * Ruta: POST /admin/miembros/update
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
 
            $currentMember = $this->usuarioModel->getById($id);
            $foto_perfil = $currentMember['foto_perfil'] ?? null;

            // Eliminar foto si se solicitó
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

            // Procesar subida de nueva foto
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
                        // Eliminar la anterior si existía
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
 
            // Procesar permisos dinámicos para el rol Maestro
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
                $multimediaModel = new \App\Models\MultimediaGaleria();
                $multimediaModel->upsert($id, trim($_POST['url_instagram']));
            }
            $this->redirect('/admin/miembros');
        }
    }

    /**
     * Elimina un miembro de la base de datos.
     * Ruta: POST /admin/miembros/delete
     *
     * Solo espera el campo POST 'id' del miembro a eliminar.
     * Nota: no elimina el registro de 'userlog' automáticamente
     * (puede quedar un registro huérfano si no hay CASCADE en la FK).
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->usuarioModel->delete($id);
            $this->redirect('/admin/miembros');
        }
    }

    /**
     * Elimina múltiples miembros a la vez.
     * Ruta: POST /admin/miembros/delete-bulk
     *
     * Espera un array de IDs en POST['ids'] (array de enteros).
     * Por seguridad se filtran sólo valores enteros válidos.
     */
    public function deleteBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'] ?? [];

            // Sanitizar: sólo enteros positivos
            $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

            if (!empty($ids)) {
                $this->usuarioModel->deleteBulk($ids);
            }

            $this->redirect('/admin/miembros');
        }
    }
}

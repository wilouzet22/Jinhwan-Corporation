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
use App\Config\Roles;

class MiembrosController extends Controller {

    /** @var Usuario Modelo para operaciones CRUD sobre miembros */
    private $usuarioModel;

    /** @var Sede Modelo para obtener la lista de sedes (dropdown del formulario) */
    private $sedeModel;

    /** @var Nivel Modelo para obtener la lista de grados (dropdown del formulario) */
    private $nivelModel;

    /**
     * Constructor: verifica seguridad e instancia los modelos necesarios.
     * Se ejecuta antes de cualquier método del controlador.
     */
    public function __construct() {
        Security::verifySession(); // Verificar sesión activa
        Security::verifyAdmin();   // Verificar rol de administrador

        // Instanciar modelos (cada uno obtiene la conexión BD automáticamente)
        $this->usuarioModel = new Usuario();
        $this->sedeModel    = new Sede();
        $this->nivelModel   = new Nivel();
    }

    /**
     * Lista todos los miembros con sus datos completos.
     * Ruta: GET /admin/miembros
     *
     * Pasa a la vista:
     *   - $miembros      → lista completa de miembros (con sede, nivel, correo)
     *   - $sedes_list    → sedes disponibles para el formulario de asignación
     *   - $niveles_list  → grados disponibles para el formulario de asignación
     */
    public function index() {
        $miembros = $this->usuarioModel->getAllWithDetails(); // JOIN con sedes, grados, userlog
        $sedes    = $this->sedeModel->getAll();              // Para el <select> de sedes
        $niveles  = $this->nivelModel->getAll();             // Para el <select> de grados

        $this->view('administracion/miembros', [
            'miembros'    => $miembros,
            'sedes_list'  => $sedes,
            'niveles_list' => $niveles,
            'page_title'  => 'Administración de Miembros',
            'current_page' => 'miembros'
        ]);
    }

    /**
     * Crea un nuevo miembro desde el formulario de administración.
     * Ruta: POST /admin/miembros/create
     *
     * El miembro se crea como activo = 1 (a diferencia del registro público).
     * La contraseña inicial es el número de documento del miembro (hasheada).
     *
     * Campos POST esperados:
     *   nombre, apellido, tipo_documento, numero_documento, fecha_nacimiento,
     *   nivel_id, telefono, correo, rol_id (opcional, defecto=ESTUDIANTE), sede_id
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'            => $_POST['nombre'],
                'apellido'          => $_POST['apellido'],
                'tipo_documento'    => $_POST['tipo_documento'],
                'numero_documento'  => $_POST['numero_documento'],
                'fecha_nacimiento'  => $_POST['fecha_nacimiento'],
                'nivel_id'          => $_POST['nivel_id'],
                'telefono'          => $_POST['telefono'],
                'correo'            => $_POST['correo'],
                'rol_id'            => $_POST['rol_id'] ?? Roles::ESTUDIANTE, // Por defecto estudiante si no se especifica
                'sede_id'           => $_POST['sede_id']
            ];

            $this->usuarioModel->create($data);
            $this->redirect('/admin/miembros'); // Redirigir al listado tras crear
        }
    }

    /**
     * Actualiza los datos de un miembro existente.
     * Ruta: POST /admin/miembros/update
     *
     * Campos POST esperados:
     *   id (del miembro a actualizar) + mismo conjunto de campos que store()
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // ID del miembro a modificar

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
                'sede_id'           => $_POST['sede_id']
            ];

            $this->usuarioModel->update($id, $data);
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
}

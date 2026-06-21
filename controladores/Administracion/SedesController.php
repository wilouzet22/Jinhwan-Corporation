<?php
/**
 * ============================================================
 * CONTROLADOR DE SEDES – ADMINISTRACIÓN (SedesController)
 * ============================================================
 * Gestiona el CRUD completo de sedes del club de Taekwondo
 * desde el panel de administración.
 *
 * Acceso: requiere sesión activa + rol de Administrador.
 * Rutas:
 *   GET  /admin/sedes         → listar todas las sedes
 *   POST /admin/sedes/create  → crear nueva sede
 *   POST /admin/sedes/update  → actualizar sede existente
 *   POST /admin/sedes/delete  → eliminar sede (con desvinculación de miembros)
 *
 * Vista: administracion/sedes
 * Modelo usado: Sede
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Sede;

class SedesController extends Controller {

    /** @var Sede Modelo para operaciones CRUD sobre la tabla 'sedes' */
    private $sedeModel;

    /**
     * Constructor: verifica sesión y rol, luego instancia el modelo de sede.
     */
    public function __construct() {
        Security::verifySession(); // Verificar sesión activa con todos los checks
        Security::verifyPermission('sedes');
        $this->sedeModel = new Sede();
    }

    /**
     * Lista todas las sedes con el conteo de miembros de cada una.
     * Ruta: GET /admin/sedes
     *
     * El modelo Sede::getAll() incluye JOIN con miembros para contar
     * cuántos practicantes están asignados a cada sede.
     */
    public function index() {
        $sedes = $this->sedeModel->getAll(); // Incluye COUNT de miembros por sede

        $this->view('administracion/sedes', [
            'sedes'        => $sedes,
            'page_title'   => 'Administración de Sedes',
            'current_page' => 'sedes'
        ]);
    }

    /**
     * Crea una nueva sede.
     * Ruta: POST /admin/sedes/create
     *
     * Campos POST esperados:
     *   - nombre    → nombre de la sede
     *   - direccion → dirección física (se guarda en campo 'lugar' de la BD)
     *   - telefono  → información de contacto/horario (campo 'horario' en BD)
     */
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

    /**
     * Actualiza los datos de una sede existente.
     * Ruta: POST /admin/sedes/update
     *
     * Campos POST esperados:
     *   - id → ID de la sede a modificar
     *   - nombre, direccion, telefono (igual que store)
     */
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

    /**
     * Elimina una sede de forma segura.
     * Ruta: POST /admin/sedes/delete
     *
     * El modelo Sede::delete() primero desvincula a todos los miembros
     * de la sede (pone su id_sede = NULL) y luego elimina la sede.
     * Esto evita errores de integridad referencial.
     *
     * Solo espera el campo POST 'id'.
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->sedeModel->delete($id);
            $this->redirect('/admin/sedes');
        }
    }
}

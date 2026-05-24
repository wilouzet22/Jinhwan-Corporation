<?php
/**
 * ============================================================
 * CONTROLADOR DE ASCENSOS Y TEORÍA – ADMINISTRACIÓN (AscensosController)
 * ============================================================
 * Gestiona el CRUD del contenido teórico de ascenso de cinturones.
 * Permite al administrador agregar, editar y eliminar los temas
 * de estudio que los estudiantes consultarán en su módulo de teoría.
 *
 * Acceso: requiere rol de Administrador (verifyAdmin).
 * Rutas:
 *   GET  /admin/ascensos         → listar todas las teorías agrupadas por nivel
 *   POST /admin/ascensos/create  → crear nuevo tema teórico
 *   POST /admin/ascensos/update  → actualizar tema teórico existente
 *   POST /admin/ascensos/delete  → eliminar tema teórico
 *
 * Vista: administracion/ascensos
 * Modelos usados: Teoria, Nivel
 * ============================================================
 */
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Teoria;
use App\Models\Nivel;

class AscensosController extends Controller {

    /** @var Teoria Modelo para CRUD del contenido teórico */
    private $teoriaModel;

    /** @var Nivel Modelo para obtener la lista de grados (dropdown del formulario) */
    private $nivelModel;

    /**
     * Constructor: verifica rol de administrador e instancia modelos.
     * Nota: solo llama a verifyAdmin() sin verifySession() previo
     * (verifyAdmin llama a initSession internamente, pero sin los checks
     * completos de verifySession; es un comportamiento ligeramente diferente
     * al de otros controladores admin).
     */
    public function __construct() {
        Security::verifyAdmin(); // Verificar que el usuario tenga rol de administrador

        $this->teoriaModel = new Teoria();
        $this->nivelModel  = new Nivel();
    }

    /**
     * Lista todos los temas teóricos disponibles con su información de nivel.
     * Ruta: GET /admin/ascensos
     *
     * Pasa a la vista:
     *   - $teorias  → lista de temas ordenados por grado y luego por ID
     *   - $niveles  → lista de grados para el selector del formulario
     */
    public function index() {
        $teorias = $this->teoriaModel->getAll(); // Incluye JOIN con grados para el nombre del nivel
        $niveles = $this->nivelModel->getAll();  // Lista de grados disponibles

        $this->view('administracion/ascensos', [
            'teorias'      => $teorias,
            'niveles'      => $niveles,
            'page_title'   => 'Administración de Teoría y Ascensos',
            'current_page' => 'ascensos'
        ]);
    }

    /**
     * Crea un nuevo tema teórico.
     * Ruta: POST /admin/ascensos/create
     *
     * Campos POST esperados:
     *   - titulo      → nombre/título del tema
     *   - descripcion → contenido explicativo del tema
     *   - url         → URL de video de apoyo (puede estar vacío)
     *   - nivel_id    → ID del grado al que pertenece este tema
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo'      => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'url_video'   => $_POST['url'],    // Campo URL (actualmente no se almacena en el nuevo esquema)
                'nivel_id'    => $_POST['nivel_id']
            ];

            $this->teoriaModel->create($data);
            $this->redirect('/admin/ascensos');
        }
    }

    /**
     * Actualiza un tema teórico existente.
     * Ruta: POST /admin/ascensos/update
     *
     * Campos POST esperados:
     *   - id          → ID del tema a modificar
     *   - titulo, descripcion, url, nivel_id (igual que store)
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'titulo'      => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'url_video'   => $_POST['url'],
                'nivel_id'    => $_POST['nivel_id']
            ];

            $this->teoriaModel->update($id, $data);
            $this->redirect('/admin/ascensos');
        }
    }

    /**
     * Elimina un tema teórico por su ID.
     * Ruta: POST /admin/ascensos/delete
     *
     * Solo espera el campo POST 'id' del tema a eliminar.
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->teoriaModel->delete($id);
            $this->redirect('/admin/ascensos');
        }
    }
}

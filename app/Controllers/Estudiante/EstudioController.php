<?php
/**
 * ============================================================
 * CONTROLADOR DE ESTUDIO TEÓRICO DEL ESTUDIANTE (EstudioController)
 * ============================================================
 * Permite al estudiante acceder al material de teoría para
 * prepararse para sus ascensos de cinturón.
 *
 * Acceso: requiere sesión activa.
 * Rutas:
 *   GET  /estudiante/estudio  → mostrar todo el material teórico
 *   POST /ascensos/toggle     → marcar/desmarcar favorito (endpoint AJAX)
 *
 * Vista: estudiante/estudio
 * Modelos usados: Teoria, Usuario
 *
 * Nota: la funcionalidad de favoritos está deshabilitada en el
 * nuevo esquema de BD, pero el código de toggle se mantiene para
 * compatibilidad con el JavaScript del front-end.
 * ============================================================
 */
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Teoria;
use App\Models\Usuario;

class EstudioController extends Controller {

    /**
     * Constructor: verifica sesión activa antes de acceder a los métodos.
     */
    public function __construct() {
        Security::verifySession();
    }

    /**
     * Muestra el módulo de estudio teórico del estudiante.
     * Ruta: GET /estudiante/estudio
     *
     * Proceso:
     *   1. Obtiene todos los temas teóricos ordenados por grado.
     *   2. Obtiene los datos del estudiante autenticado.
     *   3. Obtiene los favoritos del estudiante (actualmente vacío).
     *   4. Enriquece cada teoría con un array de 'recursos' (videos de apoyo).
     *   5. Renderiza la vista con todos los datos.
     *
     * La vista puede filtrar y agrupar los temas por nivel/cinturón.
     */
    public function index() {
        $teoriaModel = new Teoria();

        // Obtener todo el contenido teórico ordenado por grado ascendente
        $teorias = $teoriaModel->getAll();

        $usuarioModel = new Usuario();

        // Obtener los datos del estudiante autenticado (para mostrar su nivel actual)
        $estudiante = $usuarioModel->getById($_SESSION['id']);

        $usuario_id = $_SESSION['id'];

        // Obtener IDs de teorías favoritas del estudiante (actualmente retorna [] siempre)
        $favorites = $teoriaModel->getFavorites($usuario_id);

        // Enriquecer cada teoría con un array de recursos multimedia
        // Esto espeja la lógica del sistema legacy para compatibilidad con la vista
        foreach ($teorias as &$teoria) {
            $teoria['recursos'] = []; // Inicializar array de recursos vacío

            // Si hay URL de video, agregar como recurso de tipo Video
            if (!empty($teoria['url_video'])) {
                $teoria['recursos'][] = [
                    'titulo' => 'Video de Apoyo',
                    'url'    => $teoria['url_video'],
                    'tipo'   => 'Video'
                ];
            }
        }

        $this->view('estudiante/estudio', [
            'teorias'       => $teorias,    // Lista de temas teóricos enriquecidos
            'mi_teoria_ids' => $favorites,  // IDs de favoritos del estudiante (siempre [] por ahora)
            'estudiante'    => $estudiante, // Datos del alumno autenticado
            'page_title'    => 'Estudio Teórico'
        ]);
    }

    /**
     * Endpoint AJAX para marcar o desmarcar una teoría como favorita.
     * Ruta: POST /ascensos/toggle
     *
     * Recibe JSON del body con la estructura:
     *   { "teoriaId": 5 }
     *
     * Responde con JSON:
     *   { "status": "success", "action": "added" | "removed" }
     *
     * Nota: Actualmente la funcionalidad está deshabilitada en el modelo
     * (siempre retorna 'removed'). El endpoint se mantiene para que el
     * JavaScript del front-end no genere errores de red.
     */
    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el cuerpo de la petición JSON (AJAX no usa $_POST)
            $input    = json_decode(file_get_contents('php://input'), true);
            $teoria_id = $input['teoriaId'] ?? null;
            $usuario_id = $_SESSION['id'];

            if ($teoria_id) {
                $teoriaModel = new Teoria();

                // Intentar toggle (actualmente siempre retorna 'removed')
                $result = $teoriaModel->toggleFavorite($usuario_id, $teoria_id);

                // Responder con JSON al cliente JavaScript
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'action' => $result]);
                exit;
            }
        }

        // Si la petición no es POST o falta teoriaId → Bad Request
        http_response_code(400);
        exit;
    }
}

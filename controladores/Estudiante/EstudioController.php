<?php

include_once __DIR__ . '/../../modelos/Teoria.php';
include_once __DIR__ . '/../../modelos/Usuario.php';

class EstudianteEstudioController extends Controller {

    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $teoriaModel = new Teoria();
        $teorias = $teoriaModel->getAll();

        $usuarioModel = new Usuario();
        $usuario_id = $_SESSION['id'];
        $estudiante = $usuarioModel->getById($usuario_id);

        $favorites = $teoriaModel->getFavorites($usuario_id);

        foreach ($teorias as &$teoria) {
            $teoria['recursos'] = []; 

            if (!empty($teoria['url_video'])) {
                $teoria['recursos'][] = [
                    'titulo' => 'Video de Apoyo',
                    'url'    => $teoria['url_video'],
                    'tipo'   => 'Video'
                ];
            }
        }

        $this->view('estudiante/estudio', [
            'teorias'       => $teorias,    
            'mi_teoria_ids' => $favorites,  
            'estudiante'    => $estudiante, 
            'page_title'    => 'Estudio Teórico'
        ]);
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input    = json_decode(file_get_contents('php://input'), true);
            $teoria_id = $input['teoriaId'] ?? null;
            $usuario_id = $_SESSION['id'];

            if ($teoria_id) {
                $teoriaModel = new Teoria();
                $result = $teoriaModel->toggleFavorite($usuario_id, $teoria_id);

                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'action' => $result]);
                exit;
            }
        }

        http_response_code(400);
        exit;
    }
}

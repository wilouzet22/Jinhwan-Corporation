<?php
namespace App\Controllers\Estudiante;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Teoria;

class AscensosController extends Controller {
    
    public function __construct() {
        Security::verifySession();
    }

    public function index() {
        $teoriaModel = new Teoria();
        $teorias = $teoriaModel->getAll(); // This already handles joins and ordering
        $usuario_id = $_SESSION['id'];
        $favorites = $teoriaModel->getFavorites($usuario_id);

        // Process resources (mirroring legacy logic)
        foreach ($teorias as &$teoria) {
            $teoria['recursos'] = [];
            if (!empty($teoria['url_video'])) {
                $teoria['recursos'][] = [
                    'titulo' => 'Video de Apoyo',
                    'url' => $teoria['url_video'],
                    'tipo' => 'Video'
                ];
            }
        }

        $this->view('estudiante/ascensos', [
            'teorias' => $teorias,
            'mi_teoria_ids' => $favorites, // Renamed to match legacy JS expectation or view
            'page_title' => 'Material de Ascenso'
        ]);
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
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

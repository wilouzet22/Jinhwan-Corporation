<?php
namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Models\MultimediaGaleria;

class GaleriaController extends Controller {

    private $galeriaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
            header('Location: ' . base_url('/login'));
            exit;
        }

        $this->galeriaModel = new MultimediaGaleria();
    }

    public function index() {
        $publicaciones = $this->galeriaModel->getAllGeneral();

        $this->view('administracion/galeria/index', [
            'page_title' => 'Gestión de Galería',
            'current_page' => 'galeria',
            'publicaciones' => $publicaciones
        ]);
    }

    public function crear() {
        $this->view('administracion/galeria/crear', [
            'page_title' => 'Añadir a Galería',
            'current_page' => 'galeria'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $url_instagram = filter_input(INPUT_POST, 'url_instagram', FILTER_SANITIZE_URL);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

            if ($url_instagram) {
                if ($this->galeriaModel->insertGeneral($url_instagram, $descripcion)) {
                    $_SESSION['mensaje'] = "Publicación agregada a la galería con éxito.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Error al agregar la publicación.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            } else {
                $_SESSION['mensaje'] = "La URL de Instagram es obligatoria.";
                $_SESSION['tipo_mensaje'] = "error";
            }
            
            header('Location: ' . base_url('/admin/galeria'));
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id_multimedia', FILTER_SANITIZE_NUMBER_INT);
            
            if ($id) {
                if ($this->galeriaModel->delete($id)) {
                    $_SESSION['mensaje'] = "Publicación eliminada correctamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Error al eliminar la publicación.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }
            header('Location: ' . base_url('/admin/galeria'));
            exit;
        }
    }
}

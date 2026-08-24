<?php

namespace App\Controllers\Administracion;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Evento;

class CalendarioController extends Controller {

    private $eventoModel;

    public function __construct() {
        Security::verifySession(); 
        Security::verifyPermission('calendario');   
        $this->eventoModel = new Evento();
    }

    public function index() {
        $this->view('administracion/calendario', [
            'page_title'   => 'Calendario de Eventos',
            'current_page' => 'calendario'
        ]);
    }

    public function getEventos() {
        header('Content-Type: application/json');
        $eventos = $this->eventoModel->getAll();
        echo json_encode($eventos);
        exit;
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha = $_POST['fecha'] ?? '';
            $fechaTimestamp = strtotime($fecha);
            $todayTimestamp = strtotime(date('Y-m-d 00:00:00'));

            if (!$fechaTimestamp || $fechaTimestamp < $todayTimestamp) {
                $_SESSION['mensaje'] = 'No se pueden crear eventos para fechas pasadas.';
                $_SESSION['tipo_mensaje'] = 'error';
                $this->redirect('/admin/calendario');
                return;
            }

            $data = [
                'title'       => $_POST['titulo'],
                'description' => $_POST['descripcion'] ?? '',
                'start'       => $_POST['fecha'],
                'end'         => null,
                'id_persona'  => $_SESSION['id']
            ];

            $this->eventoModel->create($data);
            $_SESSION['mensaje'] = 'Evento creado exitosamente.';
            $_SESSION['tipo_mensaje'] = 'success';
            $this->redirect('/admin/calendario');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $fecha = $_POST['fecha'] ?? '';
            $fechaTimestamp = strtotime($fecha);
            $todayTimestamp = strtotime(date('Y-m-d 00:00:00'));

            if (!$fechaTimestamp || $fechaTimestamp < $todayTimestamp) {
                $_SESSION['mensaje'] = 'No se pueden asignar fechas pasadas a un evento.';
                $_SESSION['tipo_mensaje'] = 'error';
                $this->redirect('/admin/calendario');
                return;
            }

            $data = [
                'title'       => $_POST['titulo'],
                'description' => $_POST['descripcion'] ?? '',
                'start'       => $_POST['fecha'],
                'end'         => null
            ];

            $this->eventoModel->update($id, $data);
            $_SESSION['mensaje'] = 'Evento actualizado exitosamente.';
            $_SESSION['tipo_mensaje'] = 'success';
            $this->redirect('/admin/calendario');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->eventoModel->delete($id);
            $_SESSION['mensaje'] = 'Evento eliminado.';
            $_SESSION['tipo_mensaje'] = 'success';
            $this->redirect('/admin/calendario');
        }
    }
}

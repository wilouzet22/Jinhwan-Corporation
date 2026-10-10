<?php

class AdminNotificacionesController extends Controller {

    private Notificacion $notificacionModel;

    public function __construct() {
        Security::verifySession();
        Security::verifyAdmin();
        $this->notificacionModel = new Notificacion();
    }

    public function index() {
        $tipo = trim($_GET['tipo'] ?? 'todas');
        $notificaciones = $this->notificacionModel->getRecientes(100, $tipo);
        $noLeidasCount  = $this->notificacionModel->getNoLeidasCount();

        // Estadísticas rápidas por categoría
        $db = Database::getInstance()->getConnection();
        $conteoTotal = 0;
        $conteoCarac = 0;
        $conteoReg   = 0;
        $conteoAsc   = 0;

        $res = $db->query("SELECT tipo, COUNT(*) as cant FROM notificaciones GROUP BY tipo");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $conteoTotal += (int)$row['cant'];
                if (str_starts_with($row['tipo'], 'caracterizacion')) {
                    $conteoCarac += (int)$row['cant'];
                } elseif ($row['tipo'] === 'registro') {
                    $conteoReg += (int)$row['cant'];
                } elseif ($row['tipo'] === 'ascenso') {
                    $conteoAsc += (int)$row['cant'];
                }
            }
        }

        $this->view('administracion/notificaciones', [
            'notificaciones' => $notificaciones,
            'noLeidasCount'  => $noLeidasCount,
            'tipoActivo'     => $tipo,
            'conteoTotal'    => $conteoTotal,
            'conteoCarac'    => $conteoCarac,
            'conteoReg'      => $conteoReg,
            'conteoAsc'      => $conteoAsc,
            'page_title'     => 'Centro de Notificaciones',
            'current_page'   => 'notificaciones'
        ]);
    }

    public function marcarLeida() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->notificacionModel->marcarLeida($id);
            }
        }
        $this->redirect('/admin/notificaciones');
    }

    public function marcarTodas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->notificacionModel->marcarTodasLeidas();
        }
        $this->redirect('/admin/notificaciones?msg=todas_leidas');
    }

    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->notificacionModel->eliminar($id);
            }
        }
        $this->redirect('/admin/notificaciones');
    }
}

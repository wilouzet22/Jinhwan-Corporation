<?php

include_once __DIR__ . '/../../modelos/Grupo.php';

class WebGruposController extends Controller {

    public function index() {
        $grupoModel = new Grupo();
        $grupos     = $grupoModel->getAll(); 

        // Filtrar solo los grupos activos
        $gruposActivos = array_filter($grupos, fn($g) => ($g['activo'] ?? 0) == 1);

        $this->view('web/grupos', [
            'grupos'     => $gruposActivos,
            'page_title' => 'Grupos de Entrenamiento'
        ]);
    }
}

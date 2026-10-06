<?php

require_once __DIR__ . "/../../core/Controller.php";
require_once __DIR__ . "/../../core/Security.php";
require_once __DIR__ . "/../../modelos/Cronograma.php";
require_once __DIR__ . "/../../modelos/Grupo.php";

class AdminCronogramasController extends Controller
{
    private $cronogramaModel;
    private $grupoModel;

    public function __construct()
    {
        Security::verifySession();
        Security::verifyAdmin();
        $this->cronogramaModel = new Cronograma();
        $this->grupoModel = new Grupo();
    }

    public function index()
    {
        $cronogramas = $this->cronogramaModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view("administracion/cronogramas", [
            "cronogramas"  => $cronogramas,
            "grupos"       => $grupos,
            "current_page" => "cronogramas",
            "page_title"   => "Cronogramas de Clase - Admin"
        ]);
    }

    public function show($id)
    {
        $cronograma = $this->cronogramaModel->getById($id);
        if (!$cronograma) {
            $this->redirect("admin/cronogramas");
            return;
        }

        $ejerciciosClase = $this->cronogramaModel->getEjerciciosByCronograma($id);

        $fases = [
            "inicial" => [],
            "central" => [],
            "final"   => []
        ];

        foreach ($ejerciciosClase as $item) {
            $faseKey = strtolower($item["fase"]);
            if (isset($fases[$faseKey])) {
                $fases[$faseKey][] = $item;
            }
        }

        $this->view("administracion/cronograma_detalle", [
            "cronograma"   => $cronograma,
            "fases"        => $fases,
            "current_page" => "cronogramas",
            "page_title"   => "Detalle Cronograma - Admin"
        ]);
    }
}

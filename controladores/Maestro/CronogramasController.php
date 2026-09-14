<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Security.php';
require_once __DIR__ . '/../../modelos/Cronograma.php';
require_once __DIR__ . '/../../modelos/Ejercicio.php';
require_once __DIR__ . '/../../modelos/Grupo.php';

class MaestroCronogramasController extends Controller
{
    private $cronogramaModel;
    private $ejercicioModel;
    private $grupoModel;

    public function __construct()
    {
        Security::verifySession();
        Security::verifyMaestro();
        $this->cronogramaModel = new Cronograma();
        $this->ejercicioModel = new Ejercicio();
        $this->grupoModel = new Grupo();
    }

    public function index()
    {
        $cronogramas = $this->cronogramaModel->getAll();
        $grupos = $this->grupoModel->getAll();

        $this->view('maestro/cronogramas', [
            'cronogramas' => $cronogramas,
            'grupos' => $grupos,
            'current_page' => 'cronogramas'
        ]);
    }

    public function show($id)
    {
        $cronograma = $this->cronogramaModel->getById($id);
        if (!$cronograma) {
            $this->redirect('maestro/cronogramas');
            return;
        }

        $ejerciciosClase = $this->cronogramaModel->getEjerciciosByCronograma($id);
        $bibliotecaEjercicios = $this->ejercicioModel->getAll();

        // Organizar ejercicios por fases
        $fases = [
            'inicial' => [],
            'central' => [],
            'final' => []
        ];

        foreach ($ejerciciosClase as $item) {
            $faseKey = strtolower($item['fase']);
            if (isset($fases[$faseKey])) {
                $fases[$faseKey][] = $item;
            }
        }

        $this->view('maestro/cronograma_detalle', [
            'cronograma' => $cronograma,
            'fases' => $fases,
            'biblioteca' => $bibliotecaEjercicios,
            'current_page' => 'cronogramas'
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_grupo = $_POST['id_grupo'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $objetivo = trim($_POST['objetivo'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');
            $id_maestro = $_SESSION['id'] ?? null;

            if ($id_grupo && $fecha && $id_maestro) {
                $newId = $this->cronogramaModel->create([
                    'id_grupo' => $id_grupo,
                    'id_maestro' => $id_maestro,
                    'fecha' => $fecha,
                    'objetivo' => $objetivo,
                    'observaciones' => $observaciones
                ]);

                if ($newId) {
                    $this->redirect("maestro/cronogramas/{$newId}");
                    return;
                }
            }
        }
        $this->redirect('maestro/cronogramas');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_cronograma'] ?? null;
            if ($id) {
                $this->cronogramaModel->delete($id);
            }
        }
        $this->redirect('maestro/cronogramas');
    }

    public function addEjercicio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cronograma = $_POST['id_cronograma'] ?? null;
            $id_ejercicio = $_POST['id_ejercicio'] ?? null;
            $fase = $_POST['fase'] ?? 'inicial';
            $series_o_tiempo = trim($_POST['series_o_tiempo'] ?? '');
            $observaciones_especificas = trim($_POST['observaciones_especificas'] ?? '');

            if ($id_cronograma && $id_ejercicio && in_array($fase, ['inicial', 'central', 'final'])) {
                $this->cronogramaModel->addEjercicio([
                    'id_cronograma' => $id_cronograma,
                    'id_ejercicio' => $id_ejercicio,
                    'fase' => $fase,
                    'series_o_tiempo' => $series_o_tiempo,
                    'observaciones_especificas' => $observaciones_especificas
                ]);

                $this->redirect("maestro/cronogramas/{$id_cronograma}");
                return;
            }
        }
        $this->redirect('maestro/cronogramas');
    }

    public function removeEjercicio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cronograma = $_POST['id_cronograma'] ?? null;
            $id_clase_ejercicio = $_POST['id_clase_ejercicio'] ?? null;

            if ($id_clase_ejercicio) {
                $this->cronogramaModel->removeEjercicio($id_clase_ejercicio);
            }

            if ($id_cronograma) {
                $this->redirect("maestro/cronogramas/{$id_cronograma}");
                return;
            }
        }
        $this->redirect('maestro/cronogramas');
    }
}

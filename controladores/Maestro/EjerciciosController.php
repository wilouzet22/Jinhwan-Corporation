<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Security.php';
require_once __DIR__ . '/../../modelos/Ejercicio.php';

class MaestroEjerciciosController extends Controller
{
    private $ejercicioModel;

    public function __construct()
    {
        Security::verifySession();
        Security::verifyMaestro();
        $this->ejercicioModel = new Ejercicio();
    }

    public function index()
    {
        $ejercicios = $this->ejercicioModel->getAll();
        $tipos = [
            'Fuerza general', 'Fuerza Especifica', 'Pliometria', 'Coordinación',
            'Resistencia Aerobica', 'Resistencia anaerobica', 'Combate',
            'Flexibilidad', 'Velocidad', 'Otro'
        ];

        $this->view('maestro/ejercicios', [
            'ejercicios' => $ejercicios,
            'tipos' => $tipos,
            'current_page' => 'ejercicios'
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = trim($_POST['tipo'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $explicacion = trim($_POST['explicacion'] ?? '');

            if (!empty($tipo) && !empty($nombre)) {
                $this->ejercicioModel->create([
                    'tipo' => $tipo,
                    'nombre' => $nombre,
                    'explicacion' => $explicacion
                ]);
            }
        }
        $this->redirect('maestro/ejercicios');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_ejercicio'] ?? null;
            $tipo = trim($_POST['tipo'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $explicacion = trim($_POST['explicacion'] ?? '');

            if ($id && !empty($tipo) && !empty($nombre)) {
                $this->ejercicioModel->update($id, [
                    'tipo' => $tipo,
                    'nombre' => $nombre,
                    'explicacion' => $explicacion
                ]);
            }
        }
        $this->redirect('maestro/ejercicios');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_ejercicio'] ?? null;
            if ($id) {
                $this->ejercicioModel->delete($id);
            }
        }
        $this->redirect('maestro/ejercicios');
    }
}

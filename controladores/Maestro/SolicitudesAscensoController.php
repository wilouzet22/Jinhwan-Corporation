<?php

include_once __DIR__ . '/../../modelos/Usuario.php';
include_once __DIR__ . '/../../modelos/Nivel.php';
include_once __DIR__ . '/../../modelos/Certificado.php';

class MaestroSolicitudesAscensoController extends Controller {

    public function __construct() {
        Security::verifySession();
        Security::verifyMaestro();
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        $id_maestro = (int)$_SESSION['id'];

        // Consultar ascensos registrados por este maestro
        $sql = "SELECT c.id_certificado as id, c.id_estudiante, c.observaciones, 'aprobado' as estado,
                       c.creado_en as fecha_solicitud, c.fecha_examen as fecha_resolucion,
                       e.nombre as nombre_alumno, e.apellido as apellido_alumno,
                       c.grado_anterior as grado_actual,
                       c.grado_nuevo as grado_solicitado,
                       c.folio, c.id_certificado
                FROM certificados_ascenso c
                JOIN estudiante e ON c.id_estudiante = e.id_estudiante
                WHERE c.id_maestro = ?
                ORDER BY c.creado_en DESC";

        $stmt = $db->prepare($sql);
        $solicitudes = [];
        if ($stmt) {
            $stmt->bind_param("i", $id_maestro);
            $stmt->execute();
            $solicitudes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        $usuarioModel = new Usuario();
        $nivelModel   = new Nivel();
        
        $miembros = $usuarioModel->getAllWithDetails();
        $alumnos = array_filter($miembros, function($m) {
            return ($m['rol_id'] === Roles::ESTUDIANTE || $m['rol_id'] === 'Alumno' || $m['rol_id'] === 'Deportistas') && ((int)$m['activo'] === 1);
        });
        $grados_list = $nivelModel->getAll();

        $this->view('maestro/solicitudes_ascenso', [
            'solicitudes'  => $solicitudes,
            'alumnos'      => $alumnos,
            'grados_list'  => $grados_list,
            'page_title'   => 'Ascensos y Certificados',
            'current_page' => 'solicitudes_ascenso'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_maestro = (int)$_SESSION['id'];

            $alumnos_seleccionados = $_POST['alumnos_seleccionados'] ?? [];
            $grados_actuales       = $_POST['grados_actuales'] ?? [];
            $grados_solicitados    = $_POST['grados_solicitados'] ?? [];
            $observaciones         = trim($_POST['observaciones'] ?? '');

            if (empty($alumnos_seleccionados)) {
                $this->redirect('/maestro/solicitudes-ascenso?error=no_selection');
                return;
            }

            $certModel = new Certificado();
            $nivelModel = new Nivel();
            $niveles = $nivelModel->getAll();
            $nivelesMap = [];
            foreach ($niveles as $n) {
                $nivelesMap[(int)$n['id']] = $n['nombre'];
            }

            $insertados = 0;
            foreach ($alumnos_seleccionados as $id_alumno) {
                $id_alumno = intval($id_alumno);
                $id_grado_solicitado = intval($grados_solicitados[$id_alumno] ?? 0);

                if ($id_alumno > 0 && $id_grado_solicitado > 0) {
                    $grado_anterior = $grados_actuales[$id_alumno] ?? 'Blanco';
                    $grado_nuevo    = $nivelesMap[$id_grado_solicitado] ?? ('Grado ' . $id_grado_solicitado);

                    $ok = $certModel->create([
                        'id_estudiante'  => $id_alumno,
                        'id_maestro'     => $id_maestro,
                        'grado_anterior' => $grado_anterior,
                        'grado_nuevo'    => $grado_nuevo,
                        'id_grado_nuevo' => $id_grado_solicitado,
                        'fecha_examen'   => date('Y-m-d'),
                        'observaciones'  => $observaciones,
                        'folio'          => Certificado::generarFolio()
                    ]);

                    if ($ok) $insertados++;
                }
            }

            if ($insertados > 0) {
                $this->redirect('/maestro/solicitudes-ascenso?success=1');
                return;
            }

            $this->redirect('/maestro/solicitudes-ascenso?error=invalid_data');
        }
    }

    /** Endpoint AJAX: devuelve el HTML del certificado para el modal */
    public function certificado() {
        Security::verifySession();
        Security::verifyMaestro();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo '<p class="text-center text-rose-500 py-8">Solicitud inválida.</p>';
            return;
        }

        $certModel = new Certificado();
        $cert = $certModel->getById($id);

        if (!$cert) {
            http_response_code(404);
            echo '<p class="text-center text-slate-500 py-8">Certificado no encontrado.</p>';
            return;
        }

        // Render HTML fragment (no layout)
        include __DIR__ . '/../../vistas/administracion/certificado_preview.php';
    }

    public function descargar() {
        Security::verifyRole(Roles::MAESTRO);

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit('Solicitud inválida.');
        }

        $certModel = new Certificado();
        $cert = $certModel->getById($id);

        if (!$cert) {
            http_response_code(404);
            exit('Certificado no encontrado.');
        }

        require_once __DIR__ . '/../../helpers/DiplomaPdf.php';
        DiplomaPdf::descargar($cert);
    }
}


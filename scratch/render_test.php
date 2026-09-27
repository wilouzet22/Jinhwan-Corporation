<?php
define('APP_INIT', true);

function base_url($path = '') { return '../public/img/visual/logo.svg'; }
function asset($path = '') { return '../public/' . $path; }

$cert = [
    'id_certificado' => 1,
    'alumno_nombre' => 'Ana Sofía Ramírez Agudelo',
    'tipo_documento' => 'T.I.',
    'num_doc' => '1.025.485.992',
    'foto_perfil' => '',
    'grado_anterior' => 'Cinturón Azul',
    'grado_nuevo' => 'Cinturón Pinta Rojo',
    'fecha_examen' => '2026-08-15',
    'observaciones' => 'El practicante demostró excelente disciplina y técnica de poomsae.',
    'folio' => 'JH-2026-0001',
    'nombre_sede' => 'Belén'
];

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa Diploma</title>
    <style>
        body { margin: 0; padding: 20px; background: #e2e8f0; display: flex; justify-content: center; }
    </style>
</head>
<body>
<?php
include __DIR__ . '/../vistas/administracion/certificado_preview.php';
?>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents(__DIR__ . '/test_output.html', $html);
echo "Generated static test_output.html\n";

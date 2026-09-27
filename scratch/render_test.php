<?php
define('APP_INIT', true);

function base_url($path = '') { return '../public/img/visual/logo.svg'; }
function asset($path = '') { return '../public/' . $path; }

$cert = [
    'id_certificado' => 1,
    'alumno_nombre' => 'Ana Sofia Sánchez Agudelo',
    'tipo_documento' => 'TI',
    'num_doc' => '1011222665',
    'foto_perfil' => '',
    'grado_anterior' => 'Azul',
    'grado_nuevo' => 'Pinta Rojo',
    'fecha_examen' => '2026-08-29',
    'observaciones' => 'El practicante buen despeño tiene muy buana tecnica de pateo pero tiene que mejorar en las poomseas tiene un cardio muy bajo debe mejorar eso no sabe combatir peor lo intenta y tiene muy buen compañerismo.',
    'folio' => 'JH-2026-0001',
    'nombre_sede' => 'SEDE PRINCIPAL SANTA MÓNICA CAMPO ALEGRE'
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
echo "Rendered static test_output.html with v3!\n";

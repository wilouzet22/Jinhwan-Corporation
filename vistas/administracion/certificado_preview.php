<?php
// Este archivo es llamado como include dentro del modal de certificados.
// Espera recibir $cert = array con datos del certificado.
if (!isset($cert) || empty($cert)) return;

// 1. Plantilla institucional de alta resolución embebida en Base64 para evitar errores de CORS / canvas
$plantilla_file = __DIR__ . '/../../public/img/visual/diploma_plantilla_v3.png';
if (file_exists($plantilla_file)) {
    $plantilla_url = 'data:image/png;base64,' . base64_encode(file_get_contents($plantilla_file));
} else {
    $plantilla_url = asset('img/visual/diploma_plantilla_v3.png');
}

// 2. Tipografía oficial Script MT Bold embebida en Base64 para máxima fidelidad vectorial y renderizado offline
$font_file = __DIR__ . '/../../public/fonts/ScriptMTBold.ttf';
$font_base64 = '';
if (file_exists($font_file)) {
    $font_base64 = base64_encode(file_get_contents($font_file));
}

$font_doc_file = __DIR__ . '/../../public/fonts/DocIdFont.ttf';
$font_doc_base64 = '';
if (file_exists($font_doc_file)) {
    $font_doc_base64 = base64_encode(file_get_contents($font_doc_file));
}

// 3. Procesamiento del Nombre del Alumno (Title Case caligráfico)
$nombre_raw    = trim($cert['alumno_nombre'] ?? 'Estudiante');
$nombre_alumno = mb_convert_case($nombre_raw, MB_CASE_TITLE, 'UTF-8');

// 4. Procesamiento del Documento de Identidad (ej. "TI 1013462218", "CC 43567890", "PPT 987654321")
$tipo_doc_raw = trim($cert['tipo_documento'] ?? 'TI');
$t_upper = mb_strtoupper($tipo_doc_raw, 'UTF-8');
$t_clean = preg_replace('/[^A-Z]/', '', $t_upper);

if (str_contains($t_upper, 'TARJETA') || $t_clean === 'TI') {
    $tipo_doc = 'TI';
} elseif (str_contains($t_upper, 'CIUDADAN') || $t_clean === 'CC') {
    $tipo_doc = 'CC';
} elseif (str_contains($t_upper, 'EXTRANJER') || $t_clean === 'CE') {
    $tipo_doc = 'CE';
} elseif (str_contains($t_upper, 'PPT') || str_contains($t_upper, 'PROTECCI') || str_contains($t_upper, 'TEMPORAL')) {
    $tipo_doc = 'PPT';
} elseif (str_contains($t_upper, 'REGISTRO') || $t_clean === 'RC') {
    $tipo_doc = 'RC';
} elseif (str_contains($t_upper, 'PASAPORTE') || $t_clean === 'PA') {
    $tipo_doc = 'Pasaporte';
} elseif (str_contains($t_upper, 'NIT')) {
    $tipo_doc = 'NIT';
} else {
    $tipo_doc = !empty($t_clean) ? $t_clean : 'TI';
}

$num_doc = trim($cert['num_doc'] ?? '');
$documento_texto = !empty($num_doc) ? "{$tipo_doc} {$num_doc}" : '';

// 5. Procesamiento de Grado y Gup
$grado_raw   = trim($cert['grado_nuevo'] ?? '');
$grado_lower = mb_strtolower($grado_raw, 'UTF-8');

// Mapeo tradicional y cálculo automático de Gup / Dan
$gup_calculado = '';
if (preg_match('/(gup\s*\d+|\d+\s*gup|dan\s*\d+|\d+\s*dan)/i', $grado_raw, $m)) {
    $gup_calculado = mb_convert_case($m[0], MB_CASE_TITLE, 'UTF-8');
} elseif (str_contains($grado_lower, 'blanco') || str_contains($grado_lower, 'white')) {
    $gup_calculado = '10° Gup';
} elseif (str_contains($grado_lower, 'pinta amarilla') || str_contains($grado_lower, 'pinta amarillo') || str_contains($grado_lower, 'punta amarilla') || str_contains($grado_lower, 'punta amarillo')) {
    $gup_calculado = '9° Gup';
} elseif (str_contains($grado_lower, 'amarillo') || str_contains($grado_lower, 'yellow')) {
    $gup_calculado = '8° Gup';
} elseif (str_contains($grado_lower, 'pinta verde') || str_contains($grado_lower, 'punta verde')) {
    $gup_calculado = '7° Gup';
} elseif (str_contains($grado_lower, 'verde') || str_contains($grado_lower, 'green')) {
    $gup_calculado = '6° Gup';
} elseif (str_contains($grado_lower, 'pinta azul') || str_contains($grado_lower, 'punta azul')) {
    $gup_calculado = '5° Gup';
} elseif (str_contains($grado_lower, 'azul') || str_contains($grado_lower, 'blue')) {
    $gup_calculado = '4° Gup';
} elseif (str_contains($grado_lower, 'pinta roja') || str_contains($grado_lower, 'pinta rojo') || str_contains($grado_lower, 'punta roja') || str_contains($grado_lower, 'punta rojo')) {
    $gup_calculado = '3° Gup';
} elseif (str_contains($grado_lower, 'rojo') || str_contains($grado_lower, 'red')) {
    if (str_contains($grado_lower, 'negra') || str_contains($grado_lower, 'negro') || str_contains($grado_lower, 'black') || str_contains($grado_lower, 'pinta') || str_contains($grado_lower, 'punta')) {
        $gup_calculado = 'Gup 1';
    } else {
        $gup_calculado = '2° Gup';
    }
} elseif (str_contains($grado_lower, 'pinta negra') || str_contains($grado_lower, 'pinta negro') || str_contains($grado_lower, 'punta negra') || str_contains($grado_lower, 'punta negro')) {
    $gup_calculado = 'Gup 1';
} elseif (str_contains($grado_lower, 'negro') || str_contains($grado_lower, 'dan')) {
    if (preg_match('/(\d+)/', $grado_raw, $d)) {
        $gup_calculado = $d[1] . ' Dan';
    } else {
        $gup_calculado = '1er Dan';
    }
}

// Formateo del texto del cinturón
if (!empty($grado_raw)) {
    if (str_contains($grado_lower, 'pinta negro') || str_contains($grado_lower, 'punta negra') || str_contains($grado_lower, 'rojo p, negra')) {
        $grado_nuevo_texto = 'Cinturon Rojo P, Negra';
    } else {
        $grado_nuevo_texto = mb_convert_case($grado_raw, MB_CASE_TITLE, 'UTF-8');
        if (!str_starts_with(mb_strtolower($grado_nuevo_texto, 'UTF-8'), 'cintur')) {
            $grado_nuevo_texto = 'Cinturón ' . $grado_nuevo_texto;
        }
    }
} else {
    $grado_nuevo_texto = 'Cinturón de Taekwondo';
}

// 6. Formato de fecha en español idéntico al ejemplo físico oficial ("16 de Noviembre del 2025")
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$ts = !empty($cert['fecha_examen']) ? strtotime($cert['fecha_examen']) : time();
$dia = date('j', $ts);
$mes = $meses[(int)date('n', $ts)] ?? date('F', $ts);
$ano = date('Y', $ts);
$fecha_formateada = "{$dia} de {$mes} del {$ano}";
?>

<style>
@font-face {
    font-family: 'Script MT Bold';
    src: <?php if (!empty($font_base64)): ?>url('data:font/truetype;charset=utf-8;base64,<?= $font_base64 ?>') format('truetype'),<?php endif; ?>
         url('<?= asset('fonts/ScriptMTBold.ttf') ?>') format('truetype');
    font-weight: bold;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'DocIdFont';
    src: <?php if (!empty($font_doc_base64)): ?>url('data:font/truetype;charset=utf-8;base64,<?= $font_doc_base64 ?>') format('truetype'),<?php endif; ?>
         url('<?= asset('fonts/DocIdFont.ttf') ?>') format('truetype');
    font-weight: bold;
    font-style: italic;
    font-display: swap;
}

#certificado-contenido {
    width: 800px;
    height: 1035px;
    max-width: 100%;
    margin: 0 auto;
    position: relative;
    background: #ffffff;
    box-sizing: border-box;
    box-shadow: 0 20px 45px rgba(15,23,42,0.18);
    border-radius: 8px;
    overflow: hidden;
    user-select: none;
}

.diploma-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
    z-index: 0;
}

.diploma-nombre {
    position: absolute;
    top: 275px;
    left: 70px;
    right: 70px;
    text-align: center;
    font-family: 'Script MT Bold', cursive, 'Brush Script MT', serif;
    font-size: 38px;
    color: #000000;
    line-height: 1.1;
    z-index: 1;
    letter-spacing: 0.5px;
}

.diploma-doc {
    position: absolute;
    top: 326px;
    left: 70px;
    right: 70px;
    text-align: center;
    font-family: 'DocIdFont', Georgia, 'Times New Roman', serif;
    font-style: italic;
    font-weight: bold;
    font-size: 24px;
    color: #000000;
    line-height: 1.1;
    z-index: 1;
    letter-spacing: 0.5px;
}

.diploma-acredita-bloque {
    position: absolute;
    top: 550px;
    left: 70px;
    right: 70px;
    text-align: center;
    z-index: 1;
}

.diploma-grado {
    font-family: 'Script MT Bold', cursive, 'Brush Script MT', serif;
    font-size: 30px;
    color: #000000;
    line-height: 1.2;
    margin: 0;
    letter-spacing: 0.5px;
}

.diploma-gup {
    font-family: 'Script MT Bold', cursive, 'Brush Script MT', serif;
    font-size: 26px;
    color: #000000;
    line-height: 1.2;
    margin-top: 6px;
    letter-spacing: 0.5px;
}

.diploma-fecha {
    font-family: 'Script MT Bold', cursive, 'Brush Script MT', serif;
    font-size: 22px;
    color: #000000;
    line-height: 1.2;
    margin-top: 14px;
    letter-spacing: 0.5px;
}

@media print {
    body * {
        visibility: hidden !important;
    }
    #certificado-contenido, #certificado-contenido * {
        visibility: visible !important;
    }
    #certificado-contenido {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    @page {
        size: letter portrait;
        margin: 0;
    }
}
</style>

<div id="certificado-contenido" data-alumno="<?= htmlspecialchars($nombre_alumno) ?>">
    <!-- Plantilla gráfica institucional de fondo en alta resolución -->
    <img src="<?= $plantilla_url ?>" class="diploma-bg" alt="Diploma Jinhwan">
    
    <!-- 1. Nombre completo del estudiante -->
    <div class="diploma-nombre"><?= htmlspecialchars($nombre_alumno) ?></div>
    
    <!-- 2. Documento de identidad -->
    <?php if (!empty($documento_texto)): ?>
        <div class="diploma-doc"><?= htmlspecialchars($documento_texto) ?></div>
    <?php endif; ?>
    
    <!-- 3. Bloque de Acreditación: Grado, Gup y Fecha sobre la marca de agua 태권도 -->
    <div class="diploma-acredita-bloque">
        <div class="diploma-grado"><?= htmlspecialchars($grado_nuevo_texto) ?></div>
        <?php if (!empty($gup_calculado)): ?>
            <div class="diploma-gup"><?= htmlspecialchars($gup_calculado) ?></div>
        <?php endif; ?>
        <div class="diploma-fecha"><?= htmlspecialchars($fecha_formateada) ?></div>
    </div>
</div>

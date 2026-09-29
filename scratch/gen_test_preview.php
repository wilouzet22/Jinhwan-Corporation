<?php
$font_file = 'public/fonts/ScriptMTBold.ttf';
$font_b64 = base64_encode(file_get_contents($font_file));
$img_file = 'public/img/visual/diploma_plantilla_v3.png';
$img_b64 = base64_encode(file_get_contents($img_file));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Test Diploma Preview</title>
<style>
@font-face {
    font-family: 'Script MT Bold';
    src: url('data:font/truetype;charset=utf-8;base64,<?= $font_b64 ?>') format('truetype');
    font-weight: bold;
    font-style: normal;
}

body {
    margin: 0;
    padding: 20px;
    background: #e2e8f0;
    display: flex;
    justify-content: center;
}

#certificado-contenido {
    width: 800px;
    height: 1035px;
    position: relative;
    background: #ffffff;
    box-sizing: border-box;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
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
    top: 270px;
    left: 80px;
    right: 80px;
    text-align: center;
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 34px;
    color: #111827;
    line-height: 1.1;
    z-index: 1;
}

.diploma-doc {
    position: absolute;
    top: 318px;
    left: 80px;
    right: 80px;
    text-align: center;
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 24px;
    color: #111827;
    line-height: 1.1;
    z-index: 1;
}

.diploma-acredita-bloque {
    position: absolute;
    top: 545px;
    left: 80px;
    right: 80px;
    text-align: center;
    z-index: 1;
}

.diploma-grado {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 28px;
    color: #111827;
    line-height: 1.2;
    margin: 0;
}

.diploma-gup {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 25px;
    color: #111827;
    line-height: 1.2;
    margin-top: 4px;
}

.diploma-fecha {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 21px;
    color: #111827;
    line-height: 1.2;
    margin-top: 10px;
}
</style>
</head>
<body>

<div id="certificado-contenido">
    <img src="data:image/png;base64,<?= $img_b64 ?>" class="diploma-bg" alt="Diploma">
    <div class="diploma-nombre">Samuel Gomez Londono</div>
    <div class="diploma-doc">TI 1013462218</div>
    <div class="diploma-acredita-bloque">
        <div class="diploma-grado">Cinturon Rojo P, Negra</div>
        <div class="diploma-gup">Gup 1</div>
        <div class="diploma-fecha">16 de Noviembre del 2025</div>
    </div>
</div>

</body>
</html>

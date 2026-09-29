<?php
$font_file = __DIR__ . '/../public/fonts/ScriptMTBold.ttf';
$font_b64 = base64_encode(file_get_contents($font_file));
$img_file = __DIR__ . '/../public/img/visual/diploma_plantilla_v3.png';
$img_b64 = base64_encode(file_get_contents($img_file));

$html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Test Download PDF</title>
<script src="../public/js/vendor/html2pdf.bundle.min.js" charset="utf-8"></script>
<style>
@font-face {
    font-family: 'Script MT Bold';
    src: url('data:font/truetype;charset=utf-8;base64,{$font_b64}') format('truetype');
    font-weight: bold;
    font-style: normal;
}

body {
    margin: 0;
    padding: 20px;
    background: #f1f5f9;
    font-family: sans-serif;
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
    margin: 0 auto;
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
    left: 80px;
    right: 80px;
    text-align: center;
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 38px;
    color: #000000;
    line-height: 1.1;
    z-index: 1;
}

.diploma-doc {
    position: absolute;
    top: 326px;
    left: 80px;
    right: 80px;
    text-align: center;
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 26px;
    color: #000000;
    line-height: 1.1;
    z-index: 1;
}

.diploma-acredita-bloque {
    position: absolute;
    top: 550px;
    left: 80px;
    right: 80px;
    text-align: center;
    z-index: 1;
}

.diploma-grado {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 30px;
    color: #000000;
    line-height: 1.2;
    margin: 0;
}

.diploma-gup {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 26px;
    color: #000000;
    line-height: 1.2;
    margin-top: 6px;
}

.diploma-fecha {
    font-family: 'Script MT Bold', cursive, serif;
    font-size: 22px;
    color: #000000;
    line-height: 1.2;
    margin-top: 14px;
}
</style>
</head>
<body>

<div style="text-align:center; margin-bottom: 20px;">
    <button id="btn-dl" onclick="triggerDownload()" style="padding: 10px 20px; font-size: 16px; background: #2563eb; color: #fff; border:none; border-radius: 8px; cursor: pointer;">
        Descargar Diploma en PDF
    </button>
    <span id="status" style="margin-left: 10px; font-weight: bold;"></span>
</div>

<div id="certificado-contenido">
    <img src="data:image/png;base64,{$img_b64}" class="diploma-bg" alt="Diploma">
    <div class="diploma-nombre">Samuel Gomez Londono</div>
    <div class="diploma-doc">TI 1013462218</div>
    <div class="diploma-acredita-bloque">
        <div class="diploma-grado">Cinturon Rojo P, Negra</div>
        <div class="diploma-gup">Gup 1</div>
        <div class="diploma-fecha">16 de Noviembre del 2025</div>
    </div>
</div>

<script>
function triggerDownload() {
    const el = document.getElementById('certificado-contenido');
    const status = document.getElementById('status');
    status.textContent = 'Generando PDF...';

    const opt = {
        margin:       0,
        filename:     'diploma-samuel-gomez.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { 
            scale: 2, 
            useCORS: true, 
            allowTaint: true,
            scrollX: 0,
            scrollY: 0,
            logging: false
        },
        jsPDF:        { unit: 'mm', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(el).save()
        .then(() => {
            status.textContent = '¡PDF descargado con éxito!';
            status.style.color = 'green';
        })
        .catch(err => {
            status.textContent = 'Error: ' + err;
            status.style.color = 'red';
        });
}
</script>

</body>
</html>
HTML;

file_put_contents(__DIR__ . '/test_dl.html', $html);
echo "scratch/test_dl.html written successfully in pure UTF-8!\n";

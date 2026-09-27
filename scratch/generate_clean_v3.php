<?php
// Load original diploma base
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
$w = imagesx($orig);
$h = imagesy($orig);

$white   = imagecolorallocate($orig, 255, 255, 255);
$wmColor = imagecolorallocate($orig, 196, 214, 214);

// 1. Erase TI and the old line area completely (y: 470 to 570, x: 200 to 1100)
// This gives us a 100% clean white space between "Certifica que" and "Aprobó el examen..."
imagefilledrectangle($orig, 200, 470, 1100, 570, $white);

// 2. Erase "Noviembre 26 de 2023" area completely (y: 1015 to 1085)
// - Left of left stroke: white
imagefilledrectangle($orig, 340, 1015, 444, 1085, $white);
// - Left stroke of Kwon: exact watermark color
imagefilledrectangle($orig, 445, 1015, 535, 1075, $wmColor);
// - Gap between strokes: white
imagefilledrectangle($orig, 536, 1015, 719, 1085, $white);
// - Right stroke of Kwon: exact watermark color
imagefilledrectangle($orig, 720, 1015, 820, 1075, $wmColor);
// - Right of right stroke: white
imagefilledrectangle($orig, 821, 1015, 940, 1085, $white);

// Save as diploma_plantilla_v3.png and also overwrite diploma_base.png
imagepng($orig, 'public/img/visual/diploma_plantilla_v3.png');
imagepng($orig, 'public/img/visual/diploma_base.png');

echo "diploma_plantilla_v3.png generated successfully!\n";

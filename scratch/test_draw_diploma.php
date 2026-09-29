<?php
$im = imagecreatefrompng('scratch/diploma_clean_hd.png');
$font = 'C:/Windows/Fonts/SCRIPTBL.TTF';
$black = imagecolorallocate($im, 15, 23, 42); // very deep black/navy

function drawCenteredText($im, $size, $y, $color, $font, $text) {
    $bbox = imagettfbbox($size, 0, $font, $text);
    $textWidth = abs($bbox[2] - $bbox[0]);
    $imWidth = imagesx($im);
    $x = ($imWidth - $textWidth) / 2;
    imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
}

// 1. Zone 1: Name and Document (between y=354 and y=605)
drawCenteredText($im, 38, 440, $black, $font, 'Samuel Gomez Londono');
drawCenteredText($im, 28, 500, $black, $font, 'TI 1013462218');

// 2. Zone 2: Belt, Gup and Date (between y=824 and y=1086)
drawCenteredText($im, 36, 895, $black, $font, 'Cinturon Rojo P, Negra');
drawCenteredText($im, 32, 955, $black, $font, 'Gup 1');
drawCenteredText($im, 28, 1025, $black, $font, '16 de Noviembre del 2025');

imagepng($im, 'scratch/test_completed_diploma.png');
echo "Generated scratch/test_completed_diploma.png\n";

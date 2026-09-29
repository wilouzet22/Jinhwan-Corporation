<?php
$orig = imagecreatefromjpeg('scratch/extracted_diploma_base.jpg');
$w = imagesx($orig);
$h = imagesy($orig);

$white   = imagecolorallocate($orig, 255, 255, 255);
$wmColor = imagecolorallocate($orig, 196, 214, 214); // #C4D6D6

// 1. Clean the entire student area between "Certifica que" (ends ~y=360) and "Aprobó..." (starts ~y=595)
// Clear full width between the inner borders (x=100 to x=1178)
imagefilledrectangle($orig, 100, 370, 1178, 580, $white);

// 2. Clean "Noviembre 26 de 2023" from watermark
imagefilledrectangle($orig, 340, 1015, 444, 1085, $white);
imagefilledrectangle($orig, 445, 1015, 535, 1075, $wmColor);
imagefilledrectangle($orig, 536, 1015, 719, 1085, $white);
imagefilledrectangle($orig, 720, 1015, 820, 1075, $wmColor);
imagefilledrectangle($orig, 821, 1015, 940, 1085, $white);

// Save clean PNG
imagepng($orig, 'scratch/diploma_clean_hd.png');
echo "Saved scratch/diploma_clean_hd.png\n";

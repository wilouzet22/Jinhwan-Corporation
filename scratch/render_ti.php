<?php
$im = imagecreatetruecolor(600, 150);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 600, 150, $white);

imagettftext($im, 36, 0, 50, 90, $black, 'C:/Windows/Fonts/SCRIPTBL.TTF', 'TI 1013462218');
imagepng($im, 'scratch/test_scriptbl_ti.png');
echo "Rendered scratch/test_scriptbl_ti.png\n";

<?php
$im = imagecreatefromjpeg('scratch/extracted_diploma_base.jpg');
$crop = imagecrop($im, ['x' => 450, 'y' => 450, 'width' => 400, 'height' => 150]);
imagepng($crop, 'scratch/crop_ti_original.png');
echo "Cropped original TI\n";

<?php
$img = imagecreatefrompng('public/img/visual/diploma_base.png');
$crop = imagecrop($img, ['x' => 350, 'y' => 1010, 'width' => 600, 'height' => 80]);
imagepng($crop, 'public/img/visual/crop_date.png');
echo "Saved crop_date.png\n";

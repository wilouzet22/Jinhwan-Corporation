<?php
$img = imagecreatefrompng('public/img/visual/diploma_base.png');
$crop = imagecrop($img, ['x' => 450, 'y' => 460, 'width' => 400, 'height' => 120]);
imagepng($crop, 'public/img/visual/crop_ti.png');
echo "Saved crop_ti.png\n";

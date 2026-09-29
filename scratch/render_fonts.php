<?php
$fonts = [
    'BRUSHSCI.TTF', 
    'FRSCRIPT.TTF', 
    'SCRIPTBL.TTF', 
    'LCALLIG.TTF', 
    'segoepr.ttf', 
    'VLADIMIR.TTF'
];

$im = imagecreatetruecolor(1100, 700);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$grey = imagecolorallocate($im, 120, 120, 120);
imagefilledrectangle($im, 0, 0, 1100, 700, $white);

$y = 35;
foreach ($fonts as $f) {
    $path = 'C:/Windows/Fonts/' . $f;
    if (file_exists($path)) {
        imagettftext($im, 11, 0, 30, $y, $grey, 'C:/Windows/Fonts/arial.ttf', $f);
        imagettftext($im, 26, 0, 30, $y + 35, $black, $path, 'Club de taekwondo Certifica que Samuel Gomez');
        $y += 90;
    }
}
imagepng($im, 'scratch/font_samples.png');
echo "Rendered scratch/font_samples.png\n";

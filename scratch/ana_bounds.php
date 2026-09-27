<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
$minX = 2000; $maxX = 0; $minY = 2000; $maxY = 0;
for ($y = 1020; $y <= 1250; $y++) {
    for ($x = 750; $x <= 1150; $x++) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        if ($r < 80) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}
echo "Ana Patricia signature bounds (1278x1654): X: $minX to $maxX, Y: $minY to $maxY\n";
echo "Ana Patricia signature bounds (800x1036): X: " . round($minX*800/1278) . " to " . round($maxX*800/1278) . ", Y: " . round($minY*800/1278) . " to " . round($maxY*800/1278) . "\n";

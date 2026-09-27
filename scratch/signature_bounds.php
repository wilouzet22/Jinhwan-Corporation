<?php
$orig = imagecreatefrompng('public/img/visual/diploma_base.png');
// In diploma_base.png (1278x1654), where is the top of Nelson Restrepo's signature?
// Let's scan y from 1000 to 1200, x from 200 to 600
for ($y = 1020; $y <= 1180; $y += 5) {
    for ($x = 200; $x <= 600; $x += 2) {
        $rgb = imagecolorat($orig, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 60 && $g < 60 && $b < 60) {
            echo "First dark pixel in signature area: x=$x, y=$y (scaled: x=" . round($x * 800/1278) . ", y=" . round($y * 800/1278) . ")\n";
            break 2;
        }
    }
}

// Bounding box of Nelson's signature
$minX = 2000; $maxX = 0; $minY = 2000; $maxY = 0;
for ($y = 1020; $y <= 1250; $y++) {
    for ($x = 200; $x <= 550; $x++) {
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
echo "Nelson signature bounds (1278x1654): X: $minX to $maxX, Y: $minY to $maxY\n";
echo "Nelson signature bounds (800x1036): X: " . round($minX*800/1278) . " to " . round($maxX*800/1278) . ", Y: " . round($minY*800/1278) . " to " . round($maxY*800/1278) . "\n";

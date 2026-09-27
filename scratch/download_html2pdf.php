<?php
if (!is_dir(__DIR__ . '/../public/js/vendor')) {
    mkdir(__DIR__ . '/../public/js/vendor', 0777, true);
}
$url = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
$c = file_get_contents($url);
if ($c) {
    file_put_contents(__DIR__ . '/../public/js/vendor/html2pdf.bundle.min.js', $c);
    echo "SAVED: " . strlen($c) . " bytes\n";
} else {
    echo "FAILED\n";
}

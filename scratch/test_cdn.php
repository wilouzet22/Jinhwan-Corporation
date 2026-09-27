<?php
$c = @file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js');
echo ($c !== false) ? 'OK: ' . strlen($c) . ' bytes' : 'FAIL';

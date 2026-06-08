<?php
$content = file_get_contents('d:/project/koperasi_majakara/routes/web.php');
$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (stripos($line, 'BungaController') !== false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}

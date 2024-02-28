<?php
$string = "Hümeyra";
$encodedString = base64_encode(mb_convert_encoding($string, 'UTF-8'));

$command = "./main " . $encodedString;
$output = shell_exec($command);

$result = mb_convert_encoding(base64_decode($output), 'UTF-8');

echo "Результат обработки строки в Go: " . $result;
?>

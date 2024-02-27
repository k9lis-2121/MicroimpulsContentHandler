<?php
$string = "Hümeyra";
$encodedString = base64_encode(mb_convert_encoding($string, 'UTF-8'));

// Вызов скомпилированного Go скрипта с передачей закодированной строки в качестве аргумента
$command = "./main " . $encodedString;
$output = shell_exec($command);

// Декодирование результата из base64
$result = mb_convert_encoding(base64_decode($output), 'UTF-8');

// Вывод результата
echo "Результат обработки строки в Go: " . $result;
?>

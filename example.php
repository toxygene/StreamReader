<?php
use Toxygene\StreamReader\StreamReader;

require_once 'vendor/autoload.php';

$stream = fopen('php://memory', 'w+');
fwrite($stream, '0123456789');
fseek($stream, 0);

$reader = new StreamReader($stream);

while (!$reader->isEmpty()) {
    $readCount1 = rand(1, 3);
    $readCount2 = rand(1, 3);

    var_dump(
        $readCount1,
        $reader->readChars($readCount1),
        $readCount2,
        $reader->readChars($readCount2)
    );
    echo "\n";
}

fclose($stream);

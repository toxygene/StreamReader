<?php
use Toxygene\StreamReader\StreamReader;
use Toxygene\StreamReader\PeekableStreamReaderDecorator;

require_once 'vendor/autoload.php';

$stream = fopen('php://memory', 'w+');
fwrite($stream, '0123456789');
fseek($stream, 0);

$reader = new PeekableStreamReaderDecorator(
    new StreamReader($stream)
);

while (!$reader->isEmpty()) {
    $peekCount1 = rand(1, 3);
    $peekCount2 = rand(1, 3);
    $readCount = rand(1, 2);

    var_dump(
        $peekCount1,
        $reader->peek($peekCount1),
        $peekCount2,
        $reader->peek($peekCount2),
        $readCount,
        $reader->readChars($readCount)
    );
    echo "\n";
}

fclose($stream);
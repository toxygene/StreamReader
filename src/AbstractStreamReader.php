<?php
namespace Toxygene\StreamReader;

/**
 * Abstract stream reader
 *
 * @package Toxygene\StreamReader
 */
abstract class AbstractStreamReader implements StreamReaderInterface
{

    /**
     * {@inheritdoc}
     */
    public function readChars($count)
    {
        $chars = '';
        for ($i = 0; !$this->isEmpty() && $i < $count; ++$i) {
            $chars .= $this->readChar();
        }
        return $chars;
    }

}
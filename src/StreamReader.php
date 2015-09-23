<?php
namespace Toxygene\StreamReader;

/**
 * Simple stream reader
 *
 * @package Toxygene\StreamReader
 */
class StreamReader extends AbstractStreamReader
{

    /**
     * Constructor
     *
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return feof($this->stream);
    }

    /**
     * {@inheritdoc}
     */
    public function readChar()
    {
        return fgetc($this->stream);
    }

}
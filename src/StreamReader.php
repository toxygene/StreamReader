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
     * Stream
     *
     * @var resource
     */
    protected $stream;

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
    public function close()
    {
        return fclose($this->stream);
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

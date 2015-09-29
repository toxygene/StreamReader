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
     * Current column number
     *
     * @var integer
     */
    protected $column;

    /**
     * Current line number
     *
     * @var integer
     */
    protected $line;

    /**
     * Indicator that the last character was a line return
     */
    protected $wasLineReturn = false;

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
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnNumber()
    {
        return $this->column;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineNumber()
    {
        return $this->line;
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
        $char = fgetc($this->stream);

        if ($this->wasLineReturn) {
            ++$this->line;
            $this->column = 0;
            $this->wasLineReturn = false;
        } elseif ($this->column === null && $this->line === null) {
            $this->line = 1;
            $this->column = 0;
        } else {
            ++$this->column;
        }

        if ($char == "\n") {
            $this->wasLineReturn = true;
        }

        return $char;
    }

}
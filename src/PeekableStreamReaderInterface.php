<?php
namespace Toxygene\StreamReader;

interface PeekableStreamReaderInterface extends StreamReaderInterface
{

    /**
     * Get the current peek column number
     *
     * @var integer
     */
    public function getPeekColumnNumber();

    /**
     * Get the current peek line number
     *
     * @var integer
     */
    public function getPeekLineNumber();

    /**
     * Check if there are no more characters available to peek at
     *
     * @return boolean
     */
    public function isPeekEmpty();

    /**
     * Read the stream to the peek counter
     *
     * @return string
     */
    public function readCharsToPeek();

    /**
     * Peek ahead at the stream
     *
     * @param integer $count
     * @return string
     */
    public function peek($count = 1);

    /**
     * Reset the peek offset
     *
     * @return $this
     */
    public function resetPeek();

}
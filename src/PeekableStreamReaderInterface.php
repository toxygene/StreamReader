<?php
namespace Toxygene\StreamReader;

interface PeekableStreamReaderInterface
{

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
    public function peek($count);

    /**
     * Reset the peek offset
     *
     * @return $this
     */
    public function resetPeek();

}
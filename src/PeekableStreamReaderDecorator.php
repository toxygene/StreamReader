<?php
namespace Toxygene\StreamReader;

use SplDoublyLinkedList;
use SplQueue;

/**
 * Class StreamReaderPeekableDecorator
 * @package Toxygene\StreamReader
 */
class PeekableStreamReaderDecorator extends AbstractStreamReader implements PeekableStreamReaderInterface
{

    /**
     * Lookahead (peek) queue
     *
     * @var SplQueue
     */
    private $lookahead;

    /**
     * Current peek offset
     */
    private $peekOffset = 0;

    /**
     * Stream reader being decorated
     *
     * @var StreamReaderInterface
     */
    private $streamReader;

    /**
     * Constructor
     *
     * @param StreamReaderInterface $streamReader
     */
    public function __construct(StreamReaderInterface $streamReader)
    {
        $this->streamReader = $streamReader;

        $this->lookahead = new SplQueue();
        $this->lookahead->setIteratorMode(SplDoublyLinkedList::IT_MODE_KEEP);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->lookahead->isEmpty() && $this->streamReader->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function readChar()
    {
        $this->resetPeek();

        if (!$this->lookahead->isEmpty()) {
            return $this->lookahead->dequeue();
        }

        return $this->streamReader->readChar();
    }

    /**
     * Reset the peek offset
     *
     * @return $this
     */
    public function resetPeek()
    {
        $this->peekOffset = 0;
        return $this;
    }

    /**
     * Read the stream to the peek counter
     *
     * @return string
     */
    public function readCharsToPeek()
    {
        return $this->readChars($this->peekOffset);
    }

    /**
     * Peek ahead at the stream
     *
     * @param integer $count
     * @return string
     */
    public function peek($count = 1)
    {
        $advance = max(
            0,
            $this->peekOffset + $count - $this->lookahead->count()
        );

        if ($advance > 0) {
            for ($i = 0; !$this->streamReader->isEmpty() && $i < $advance; ++$i) {
                $this->lookahead->enqueue(
                    $this->streamReader->readChar()
                );
            }
        }

        $n = min(
            $this->lookahead->count(),
            $this->peekOffset + $count
        );

        $chars = '';
        for ($i = $this->peekOffset; $i < $n; ++$i) {
            $chars .= $this->lookahead[$i];
        }

        $this->peekOffset = $n;

        return $chars;
    }

}
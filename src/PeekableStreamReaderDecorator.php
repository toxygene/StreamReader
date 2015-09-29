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
     *
     * @var integer
     */
    private $peekOffset = 0;

    /**
     * Current column number
     *
     * @var integer
     */
    private $columnNumber;

    /**
     * Current line number
     *
     * @var integer
     */
    private $lineNumber;

    /**
     * Peek column number
     *
     * @var integer
     */
    private $peekColumnNumber;

    /**
     * Peek line number
     *
     * @var integer
     */
    private $peekLineNumber;

    /**
     * Peek last character was line return
     *
     * @var boolean
     */
    private $peekWasLineReturn;

    /**
     * Stream reader being decorated
     *
     * @var StreamReaderInterface
     */
    private $streamReader;

    /**
     * Last character was line return indicator
     */
    private $wasLineReturn = false;

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
    public function close()
    {
        $this->lookahead = new SplQueue();

        return $this->streamReader->close();
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnNumber()
    {
        return $this->columnNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Get the current peek column number
     *
     * @return integer
     */
    public function getPeekColumnNumber()
    {
        return $this->peekColumnNumber;
    }

    /**
     * Get the current peek line number
     *
     * @return integer
     */
    public function getPeekLineNumber()
    {
        return $this->peekLineNumber;
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
    public function isPeekEmpty()
    {
        return $this->streamReader->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function readChar()
    {
        $char = $this->readNextChar();

        if ($this->wasLineReturn) {
            ++$this->lineNumber;
            $this->columnNumber = 0;
            $this->wasLineReturn = false;
        } elseif ($this->columnNumber === null && $this->lineNumber === null) {
            $this->lineNumber = 1;
            $this->columnNumber = 0;
        } else {
            ++$this->columnNumber;
        }

        if ($char == "\n") {
            $this->wasLineReturn = true;
        }

        $this->resetPeek();

        return $char;
    }

    /**
     * @return string
     */
    private function readNextChar()
    {
        if (!$this->lookahead->isEmpty()) {
            $char = $this->lookahead
                ->dequeue();
        } else {
            $char = $this->streamReader
                ->readChar();
        }

        return $char;
    }

    /**
     * Reset the peek offset
     *
     * @return $this
     */
    public function resetPeek()
    {
        $this->peekOffset = 0;
        $this->peekColumnNumber = $this->columnNumber;
        $this->peekLineNumber = $this->lineNumber;
        $this->peekWasLineReturn = $this->wasLineReturn;

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
            $char = $this->lookahead[$i];

            if ($this->peekWasLineReturn) {
                ++$this->peekLineNumber;
                $this->peekColumnNumber = 0;
                $this->peekWasLineReturn = false;
            } else {
                ++$this->peekColumnNumber;
            }

            if ($char == "\n") {
                $this->peekWasLineReturn = true;
            }

            $chars .= $char;
        }

        $this->peekOffset = $n;

        return $chars;
    }

}
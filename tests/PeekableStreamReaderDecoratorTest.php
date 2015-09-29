<?php
namespace Toxygene\StreamReader\Tests;

use Toxygene\StreamReader\PeekableStreamReaderDecorator;
use Toxygene\StreamReader\StreamReader;

/**
 * Test case for the peekable stream reader decorator
 *
 * @coversDefaultClass \Toxygene\StreamReader\PeekableStreamReaderDecorator
 * @package Toxygene\StreamReader\Tests
 */
class PeekableStreamReaderDecoratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PeekableStreamReaderDecorator
     */
    private $reader;

    /**
     * @var resource
     */
    private $stream;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        $this->stream = fopen('php://memory', 'w+');

        $this->reader = new PeekableStreamReaderDecorator(new StreamReader($this->stream));
    }

    /**
     * Tear down the test case
     */
    public function tearDown()
    {
        unset($this->reader);
    }

    /**
     * @covers ::peek
     * @covers ::resetPeek
     */
    public function testStreamCanBePeekedAt()
    {
        $this->setStreamContents('0123456789');

        $this->assertEquals(
            '0123',
            $this->reader->peek(4)
        );

        $this->assertEquals(
            '456789',
            $this->reader->peek(6)
        );

        $this->reader->resetPeek();

        $this->assertEquals(
            '012',
            $this->reader->peek(3)
        );
    }

    private function setStreamContents($contents)
    {
        fwrite($this->stream, $contents);
        fseek($this->stream, 0);
    }

    /**
     * @covers ::peek
     * @covers ::readChar
     */
    public function testPeekIsResetAfterRead()
    {
        $this->setStreamContents('0123456789');

        $this->assertEquals(
            '01',
            $this->reader->peek(2)
        );

        $this->assertEquals(
            '0',
            $this->reader->readChar()
        );

        $this->assertEquals(
            '12',
            $this->reader->peek(2)
        );
    }

    /**
     * @covers ::readChar
     */
    public function testCharactersCanBeReadWithoutPeekingFirst()
    {
        $this->setStreamContents('0');

        $this->assertEquals(
            '0',
            $this->reader->readChar()
        );
    }

    /**
     * @covers ::isEmpty
     */
    public function testStreamIsNotConsideredEmptyWhenPeeking()
    {
        $this->setStreamContents('0123456789');

        $this->reader->peek(10);
        $this->assertFalse($this->reader->isEmpty());
    }

    /**
     * @covers ::readCharsToPeek
     */
    public function testCharactersCanBeReadUpToTheCurrentPeek()
    {
        $this->setStreamContents('012');

        $this->reader->peek(3);

        $this->assertEquals(
            '012',
            $this->reader->readCharsToPeek()
        );
    }

    /**
     * @covers ::isPeekEmpty
     * @covers ::peek
     */
    public function testPeekingPastTheEndOfTheStreamReaderReturnsTheAvailableCharacters()
    {
        $this->setStreamContents('0123456789');

        $this->assertEquals(
            '0123456789',
            $this->reader->peek(20)
        );

        $this->assertTrue($this->reader->isPeekEmpty());
    }

    /**
     * @covers ::getPeekColumnNumber
     * @covers ::getPeekLineNumber
     */
    public function testPeekColumnAndLineNumberAreCalculated()
    {
        $this->setStreamContents("12\n3\n45\n678");

        $this->assertEquals('1', $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 0);
        $this->assertPeekLineAndColumnNumber(1, 0);

        $this->assertEquals('2', $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 1);
        $this->assertPeekLineAndColumnNumber(1, 1);

        $this->assertEquals("\n", $this->reader->peek());
        $this->assertLineAndColumnNumber(1, 1);
        $this->assertPeekLineAndColumnNumber(1, 2);

        $this->assertEquals('3', $this->reader->peek());
        $this->assertLineAndColumnNumber(1, 1);
        $this->assertPeekLineAndColumnNumber(2, 0);

        $this->assertEquals("\n", $this->reader->peek());
        $this->assertLineAndColumnNumber(1, 1);
        $this->assertPeekLineAndColumnNumber(2, 1);

        $this->assertEquals('4', $this->reader->peek());
        $this->assertLineAndColumnNumber(1, 1);
        $this->assertPeekLineAndColumnNumber(3, 0);

        $this->assertEquals("\n", $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 2);
        $this->assertPeekLineAndColumnNumber(1, 2);

        $this->assertEquals('3', $this->reader->peek());
        $this->assertLineAndColumnNumber(1, 2);
        $this->assertPeekLineAndColumnNumber(2, 0);
    }

    private function assertLineAndColumnNumber($line, $column)
    {
        $this->assertEquals($line, $this->reader->getLineNumber());
        $this->assertEquals($column, $this->reader->getColumnNumber());
    }

    private function assertPeekLineAndColumnNumber($line, $column)
    {
        $this->assertEquals($line, $this->reader->getPeekLineNumber());
        $this->assertEquals($column, $this->reader->getPeekColumnNumber());
    }

}
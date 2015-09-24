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
     * Setup the test case
     */
    public function setUp()
    {
        $stream = fopen('php://memory', 'w+');
        fwrite($stream, '0123456789');
        fseek($stream, 0);

        $this->reader = new PeekableStreamReaderDecorator(new StreamReader($stream));
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

    /**
     * @covers ::peek
     * @covers ::readChar
     */
    public function testPeekIsResetAfterRead()
    {
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
        $this->reader->peek(10);
        $this->assertFalse($this->reader->isEmpty());
    }

    /**
     * @covers ::readCharsToPeek
     */
    public function testCharactersCanBeReadUpToTheCurrentPeek()
    {
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
        $this->assertEquals(
            '0123456789',
            $this->reader->peek(20)
        );

        $this->assertTrue($this->reader->isPeekEmpty());
    }

}
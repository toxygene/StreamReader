<?php
namespace Toxygene\StreamReader\Tests;

use Toxygene\StreamReader\StreamReader;

/**
 * Test case for the simple stream reader
 *
 * @coversDefaultClass \Toxygene\StreamReader\StreamReader
 * @package Toxygene\StreamReader\Tests
 */
class StreamReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var StreamReader
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

        $this->reader = new StreamReader($stream);
    }

    /**
     * Tear down the test case
     */
    public function tearDown()
    {
        unset($this->reader);
    }

    /**
     * @covers ::readChar
     */
    public function testCharactersCanBeRead()
    {
        $this->assertEquals(
            '0',
            $this->reader->readChar()
        );

        $this->assertEquals(
            '1',
            $this->reader->readChar()
        );

        $this->assertEquals(
            '2',
            $this->reader->readChar()
        );
    }

    /**
     * @covers ::isEmpty
     */
    public function testStreamEmptyCanBeChecked()
    {
        $this->assertFalse($this->reader->isEmpty());
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->reader->readChar();
        $this->assertTrue($this->reader->isEmpty());
    }

}
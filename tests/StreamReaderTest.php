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
     * @var resource
     */
    private $stream;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        $this->stream = fopen('php://memory', 'w+');
        $this->reader = new StreamReader($this->stream);
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
        $this->setStreamContents('012');

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

    private function setStreamContents($contents)
    {
        fwrite($this->stream, $contents);
        fseek($this->stream, 0);
    }

    /**
     * @covers ::isEmpty
     */
    public function testStreamEmptyCanBeChecked()
    {
        $this->setStreamContents('0123456789');

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
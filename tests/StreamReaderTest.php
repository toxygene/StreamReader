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

    /**
     * @covers ::getColumnNumber
     * @covers ::getLineNumber
     */
    public function testColumnAndLineNumbersAreCounted()
    {
        $this->setStreamContents("12\n3\n45\n678");

        $this->assertEquals('1', $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 0);

        $this->assertEquals('2', $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 1);

        $this->assertEquals("\n", $this->reader->readChar());
        $this->assertLineAndColumnNumber(1, 2);

        $this->assertEquals('3', $this->reader->readChar());
        $this->assertLineAndColumnNumber(2, 0);

        $this->assertEquals("\n", $this->reader->readChar());
        $this->assertLineAndColumnNumber(2, 1);

        $this->assertEquals('4', $this->reader->readChar());
        $this->assertLineAndColumnNumber(3, 0);

        $this->assertEquals('5', $this->reader->readChar());
        $this->assertLineAndColumnNumber(3, 1);

        $this->assertEquals("\n", $this->reader->readChar());
        $this->assertLineAndColumnNumber(3, 2);

        $this->assertEquals('6', $this->reader->readChar());
        $this->assertLineAndColumnNumber(4, 0);

        $this->assertEquals('7', $this->reader->readChar());
        $this->assertLineAndColumnNumber(4, 1);

        $this->assertEquals('8', $this->reader->readChar());
        $this->assertLineAndColumnNumber(4, 2);
    }

    private function assertLineAndColumnNumber($line, $column)
    {
        $this->assertEquals($line, $this->reader->getLineNumber());
        $this->assertEquals($column, $this->reader->getColumnNumber());
    }

}
<?php
namespace Toxygene\StreamReader;

/**
 * Stream reader interface
 *
 * @package Toxygene\StreamReader
 */
interface StreamReaderInterface
{

    /**
     * Close the stream
     * 
     * @return boolean
     */
    public function close();

    /**
     * Get the current column number
     *
     * @return integer
     */
    public function getColumnNumber();

    /**
     * Get the current line number
     *
     * @return integer
     */
    public function getLineNumber();

    /**
     * Check if the stream is empty
     *
     * @return boolean
     */
    public function isEmpty();

    /**
     * Read a character from the stream
     *
     * @return string
     */
    public function readChar();

    /**
     * Read multiple characters from the stream
     *
     * @param integer $count
     * @return string
     */
    public function readChars($count);

}
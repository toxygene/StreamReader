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
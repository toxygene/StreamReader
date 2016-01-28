<?php
namespace Toxygene\StreamReader;

class UriStreamReader extends StreamReader
{

    /**
     * Constructor
     *
     * @param resource $uri
     * @param $mode
     * @param bool|false $useIncludePath
     * @param null $context
     */
    public function __construct($uri, $mode, $useIncludePath = false, $context = null)
    {
        parent::__construct(fopen($uri, $mode, $useIncludePath, $context));
    }

}

<?php

namespace  ArjanSchouten\HtmlMinifier\Exception;

class FileNotFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param string    $message      Exception message to throw
     * @param int       $code         Exception code
     * @param Exception $previous     previous exception used for the exception chaining
     */
    public function __construct($message = 'The file does not exists.', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

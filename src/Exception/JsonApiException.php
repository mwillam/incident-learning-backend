<?php

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonApiException extends HttpException
{

    private $errorCode;

    public function __construct($message, $code, $statusCode = 500, \Exception $previous = null, array $headers = array())
    {
        $this->errorCode = $code;

        parent::__construct($statusCode, $message, $previous, $headers);
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}

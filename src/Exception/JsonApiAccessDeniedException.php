<?php

namespace App\Exception;


class JsonApiAccessDeniedException extends JsonApiException
{
    protected $statusCode = 401;

    public function __construct($message = 'You are not authorized.', $code = 'generic.unauthorized', Throwable $previous = null)
    {
        parent::__construct($message, $code, $this->statusCode, $previous);
    }

}

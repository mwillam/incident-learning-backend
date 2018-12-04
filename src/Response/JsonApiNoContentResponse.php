<?php

namespace App\Response;


class JsonApiNoContentResponse extends JsonApiResponse
{
    protected $statusCode = 204;

    public function __construct()
    {
        parent::__construct(null, $this->statusCode, array());
        $this->setContent('');
    }

}

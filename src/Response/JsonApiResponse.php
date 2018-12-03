<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonApiResponse extends JsonResponse
{
    protected $data;

    public function __construct($data, $status = 200, array $headers = array())
    {
        parent::__construct(null, $status, $headers, false);

        $this->data = $data;

        //FIXME: should be application/vnd.api+json, but has issues with chrome tools
        $this->headers->set('Content-Type', 'application/json');
    }

    public function getData()
    {
        return $this->data;
    }

}
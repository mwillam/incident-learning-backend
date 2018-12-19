<?php

namespace App\Exception;


use AppBundle\Serializer\Serializer;
use Throwable;

class JsonApiNotFoundException extends JsonApiException
{

    protected $statusCode = 404;

    protected $code = 'entity_not_found';

    private $entityClass;

    private $entityId;

    public function __construct($entityClass, $entityId, Throwable $previous = null)
    {
        $this->entityClass = Serializer::getResourceType($entityClass);
        $this->entityId = $entityId;
        $message = sprintf('Entity with id %d not found in %s',$this->entityId, $this->entityClass);
        parent::__construct($message, $this->code, $this->statusCode, $previous);
    }

}

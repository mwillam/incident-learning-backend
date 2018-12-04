<?php
namespace App\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use App\Response\JsonApiResponse;
use App\Response\JsonApiNoContentResponse;
use App\Serializer\Serializer;

class JsonApiResponseListener
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if($response instanceof JsonApiResponse) {
            if(!$response instanceof JsonApiNoContentResponse) {
                $content = $this->serializer->serialize($response->getData());
                $response->setContent($content);
            } else {
                $response->setContent(null);
            }
        }
    }
}

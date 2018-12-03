<?php

namespace App\Controller;


use App\Response\JsonApiNoContentResponse;
use App\Response\JsonApiResponse;
use App\Serializer\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends Controller
{
    private $serializer;
    private $entityManager;

    public function __construct(Serializer $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reports", methods={"POST"})
     */
    public function newReport(Request $request)
    {

        $content = $request->getContent();
        //var_dump($content);die;

        if ($content===null) {
            throw new \Exception("No content for creating a report.",401);
        }

        try {
            $report = $this->serializer->deserialize($content);
            $this->entityManager->persist($report);
            $this->entityManager->flush();
        }
        catch (\Exception $exception) {
            throw $exception;
        }

        return new Response();

    }

    /**
     * @Route("/reports", methods={"GET", "OPTIONS"})
     */
    public function getReport(Request $request)
    {

        return new JsonApiNoContentResponse();
    }

}
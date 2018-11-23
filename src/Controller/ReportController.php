<?php

namespace App\Controller;


use App\Serializer\Serializer;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends Controller
{
    private $serializer;
    private $entityManager;

    public function __construct(Serializer $serializer, EntityManager $entityManager)
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
        $report = $this->serializer->deserialize($content);
        $this->entityManager->persist($report);
        $this->entityManager->flush();
        return new Response();
    }

    /**
     * @Route("/reports", methods={"GET"})
     */
    public function getReport(Request $request)
    {

        return new Response();
    }

}
<?php

namespace App\Controller;


use App\Entity\Report;
use App\Response\JsonApiNoContentResponse;
use App\Response\JsonApiResponse;
use App\Serializer\Serializer;
use App\Service\RequestAnalyzer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends Controller
{
    private $serializer;
    private $entityManager;
    private $requestAnalyzer;

    public function __construct(
        Serializer $serializer,
        EntityManagerInterface $entityManager,
        RequestAnalyzer $requestAnalyzer
    ){
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->requestAnalyzer = $requestAnalyzer;
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
        $report->setCreatedNow();

        try {
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
        $queryKeys = $this->requestAnalyzer->filterEntityAttributes(Report::class, $request);

        $queryParameters = $this->requestAnalyzer->filterQueryParameters(Report::class, $request);

        $reports = $this->entityManager->getRepository(Report::class)
            ->findByAttributes($queryKeys, $queryParameters);

        if (empty($reports)) {
            return new JsonApiResponse(array());
        }

        return new JsonApiResponse($reports);
    }

}
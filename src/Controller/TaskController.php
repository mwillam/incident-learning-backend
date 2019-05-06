<?php

namespace App\Controller;


use App\Entity\Task;
use App\Response\JsonApiResponse;
use App\Serializer\Serializer;
use App\Service\RequestAnalyzer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends Controller
{
    private $entityManager;
    private $serializer;
    private $requestAnalyzer;

    public function __construct(
        EntityManagerInterface $entityManager,
        Serializer $serializer,
        RequestAnalyzer $requestAnalyzer
    ){
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->requestAnalyzer = $requestAnalyzer;
    }

    /**
     * @Route("/tasks", methods={"POST"})
     */
    public function newTask(Request $request)
    {
        $content = $request->getContent();

        if ($content===null) {
            throw new \Exception("No content for creating a report.",401);
        }

        $task = $this->serializer->deserialize($content);
        $task->setCreatedNow();

        try {
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        }
        catch (\Exception $exception) {
            throw $exception;
        }

        return new Response();

    }

    /**
     * @Route("/tasks", methods={"GET", "OPTIONS"})
     */
    public function getTask(Request $request)
    {
        $queryKeys = $this->requestAnalyzer->filterEntityAttributes(Task::class, $request);

        $queryParameters = $this->requestAnalyzer->filterQueryParameters(Task::class, $request);

        $tasks = $this->entityManager->getRepository(Task::class)
            ->findByAttributes($queryKeys, $queryParameters);

        if (empty($tasks)) {
            return new JsonApiResponse(array());
        }

        return new JsonApiResponse($tasks);
    }

}
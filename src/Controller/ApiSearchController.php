<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\SearchDTO;
use App\Service\Utils\RequestDataProcessor;
use App\Service\Api\Inside\Search\SearchHandlerService;

class ApiSearchController extends AbstractController
{
    private $reqProcessor;
    private $searchService;

    public function __construct(
        RequestDataProcessor $reqProcessor,
        SearchHandlerService $searchService
    ) {
        $this->reqProcessor = $reqProcessor;
        $this->searchService = $searchService;
    }

    #[Route('/api/search', name: 'app_api_search', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $dto = new SearchDTO($requestData);
        dump($dto);
        if (!$this->searchService->issetParam($dto)) {
            return $this->json(['error' => 'Недостаточно данных для поиска']);
        }

        $result = $this->searchService->performSearch($dto);

        return $this->json($result);
    }
}
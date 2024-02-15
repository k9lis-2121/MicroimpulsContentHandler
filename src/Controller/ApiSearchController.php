<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Utils\RequestDataProcessor;
use App\Service\Api\Inside\Search\SearchHandlerService;
use DateTimeZone;

class ApiSearchController extends AbstractController
{
    private $requestProcessor;
    private $searchHandlerService;

    public function __construct(
        RequestDataProcessor $requestProcessor,
        SearchHandlerService $searchHandlerService
    ) {
        $this->requestProcessor = $requestProcessor;
        $this->searchHandlerService = $searchHandlerService;
    }

    #[Route('/api/search', name: 'app_api_search', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $requestData = $this->requestProcessor->processRequestData($request);
        $data = $this->searchHandlerService->issetParam($requestData['data']);

        if (!$data) {
            return $this->json([
                [
                    'id' => 0,
                    'kpId' => 0,
                    'name' => 'Подходящих совпадений не найдено',
                    'name_orig' => 'no result',
                    'is_season' => false,
                    'averageJaccardIndex' => 0,
                ]
            ]);
        }

        $data['semantic'] = true;

        if (isset($data['searchTranscodingStatus'])) {
            $searchNameResult = $this->searchHandlerService->searchTranscodeStatus($data['searchTranscodingStatus']);
            return $this->json($searchNameResult);
        }

        $searchNameResult = [];
        if (!empty($data['searchName'])) {
            $searchNameResult = $data['semantic'] ?
                $this->searchHandlerService->findSemanticByName($data['searchName']) :
                $this->searchHandlerService->findByName($data['searchName']);
        }

        if (isset($data['searchKpId'])) {
            $searchNameResult[] = $this->searchHandlerService->findByKpId($data['searchKpId']);
        }

        $uniqueResults = array_column($searchNameResult, null, 'kpId');
        $searchNameResult = array_values($uniqueResults);

        foreach ($searchNameResult as $key => $item) {
            $transcodeStatus = $this->searchHandlerService->getTranscodeStatus($item['kpId']);
            if ($transcodeStatus) {
                $transcodeStatus['UpdateAt'] = $transcodeStatus['UpdateAt']->setTimezone(new DateTimeZone('Asia/Novokuznetsk'))->format('H:i d.m.y');
                $searchNameResult[$key] = array_merge($item, $transcodeStatus);
            }
        }

        return $this->json($searchNameResult);
    }
}
<?php

namespace App\Service\Api\Inside\Search\Searches;

use App\DTO\SearchDTO; // Импортируем DTO
use App\Repository\KplocalFilmsRepository;
use App\Service\Utils\SemanticSearchService;

class SearchByNameService
{
    private $kpLocalFilmsRepository;
    private $semanticSearchService;

    public function __construct(KplocalFilmsRepository $kpLocalFilmsRepository, SemanticSearchService $semanticSearchService)
    {
        $this->kpLocalFilmsRepository = $kpLocalFilmsRepository;
        $this->semanticSearchService = $semanticSearchService;
    }

    public function searchByNameSemantic(SearchDTO $dto)
    {
        $searchTerm = $dto->name; // Используем имя из DTO
        $results = $this->kpLocalFilmsRepository->finAllFilmsBySearch();
        $matchingResults = [];
        $searchTerms = preg_split('/\s+/', trim($searchTerm)); // Разбиваем исходный поисковый запрос на слова
    
        foreach ($results as $result) {
            $jaccardIndexes = [];

            foreach ($searchTerms as $term) {
                $namesToCompare = array_merge(
                    explode(' ', $result['name']), // Делим name на слова
                    explode(' ', $result['nameOrig']) // Делим nameOrig на слова
                );

                foreach ($namesToCompare as $word) {
                    $jaccardIndex = $this->semanticSearchService->calculateJaccardIndex($term, $word);
                    $jaccardIndexes[] = $jaccardIndex;
                }
            }
            
            $maxJaccardIndex = max($jaccardIndexes);
            
            if ($maxJaccardIndex > 0.2) { // Порог совпадения
                $matchingResults[] = [
                    'id' => $result['id'],
                    'kpId' => $result['kpId'],
                    'name' => $result['name'],
                    'nameOrig' => $result['nameOrig'],
                    'isSeason' => $result['isSeason'],
                    'averageJaccardIndex' => $maxJaccardIndex
                ];
            }
        }
        
        if (count($matchingResults) == 0) {
            $matchingResults[] = [
                'id' => 0,
                'kpId' => 0,
                'name' => 'Подходящих совпадений не найдено',
                'nameOrig' => 'no result',
                'isSeason' => false,
                'averageJaccardIndex' => 0
            ];
        } else {
            usort($matchingResults, function ($a, $b) {
                return $b['averageJaccardIndex'] <=> $a['averageJaccardIndex'];
            });

            // Возвращаем только лучшее совпадение
            $matchingResults = [$matchingResults[0]];
        }
        
        return $matchingResults;
    }

    public function searchByName(SearchDTO $dto)
    {
        $name = $dto->name; // Используем имя из DTO
        return $this->kpLocalFilmsRepository->findFilmsBySearchTerm($name);
    }
}
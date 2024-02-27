<?php

namespace App\Service\Api\Inside\Search;

use App\DTO\SearchDTO;
use App\Service\Api\Inside\Search\Searches\SearchByNameService;
use App\Service\DbAssist\SmartyDbAssistService;
use App\Repository\KplocalFilmsRepository;
use App\Repository\TranscodingProcessesRepository;

class SearchHandlerService
{
    private $searchByName;
    private $smartyDbAssistService;
    private $kpLocalFilmsRepository;
    private $transcodingProcessesRepository;

    public function __construct(
        SearchByNameService $searchByNameService,
        SmartyDbAssistService $smartyDbAssistService,
        KplocalFilmsRepository $kpLocalFilmsRepository,
        TranscodingProcessesRepository $transcodingProcessesRepository
    ) {
        $this->searchByName = $searchByNameService;
        $this->smartyDbAssistService = $smartyDbAssistService;
        $this->kpLocalFilmsRepository = $kpLocalFilmsRepository;
        $this->transcodingProcessesRepository = $transcodingProcessesRepository;
    }

    public function issetParam(SearchDTO $dto): bool
    {
        return !empty($dto->name) || !empty($dto->kpId) || !empty($dto->transcodeStatus);
    }

    public function performSearch(SearchDTO $dto)
    {
        $result = [];

        if ($dto->transcodeStatus) {
            return $this->transcodingProcessesRepository->findByTranscodingStatus($dto->transcodeStatus);
        }

        if (!empty($dto->name)) {
            $result = $dto->semantic ?
                $this->searchByName->searchByNameSemantic($dto) :
                $this->searchByName->searchByName($dto);
        }

        if (!empty($dto->kpId)) {
            $kpResult = $this->kpLocalFilmsRepository->findFilmByDTO($dto);
            if ($kpResult) {
                $smartyId = $this->smartyDbAssistService->getVideoIdByKinopoiskId((int)$dto->kpId);
                $kpResult['smartyId'] = $smartyId;
                $dto->smartyId = $smartyId; // Обновляем DTO
                $result[] = $kpResult;
            }
        }


        return $result;
    }
}
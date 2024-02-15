<?php

namespace App\Service\Api\Inside\Search;
use App\Service\Api\Inside\Search\SearchParamIsseterService;
use App\Service\Api\Inside\Search\Searches\SearchByNameService;
use App\Service\DbAssist\SmartyDbAssistService;


use App\Repository\KplocalFilmsRepository;
use App\Repository\TranscodingProcessesRepository;


class SearchHandlerService
{
    
    private $searchParamIsseter;
    private $searchByName;

    public function __construct(
        SearchParamIsseterService $searchParamIsseter, 
        SearchByNameService $searchByNameService,
        SmartyDbAssistService $smartyDbAssistService,
        KplocalFilmsRepository $kpLocalFilmsRepository,
        TranscodingProcessesRepository $transcodingProcessesRepository
        ){
        $this->searchParamIsseter = $searchParamIsseter;
        $this->searchByName = $searchByNameService;
        $this->smartyDbAssistService = $smartyDbAssistService;
        $this->kpLocalFilmsRepository=$kpLocalFilmsRepository;
        $this->transcodingProcessesRepository = $transcodingProcessesRepository;
    }

    public function issetParam(array $param){
        return $this->searchParamIsseter->getIssetParam($param);
    }

    public function findByName(string $name){
        $resultSearch = $this->searchByName->searchByName($name);
        foreach($resultSearch as $key => $value){
            $resultSearch[$key]['smartyId'] = $this->smartyDbAssistService->getVideoIdByKinopoiskId($value['kpId']);
        }
        return $resultSearch;
    }

    public function findSemanticByName(string $name){
        $resultSearch = $this->searchByName->searchByNameSemantic($name);
        foreach($resultSearch as $key => $value){
            $resultSearch[$key]['smartyId'] = $this->smartyDbAssistService->getVideoIdByKinopoiskId($value['kpId']);
        }
        dump('dump result search');
        dump($resultSearch);
        return $resultSearch;

    }

    public function findByKpId(string $kpId){
        $kpResult[0] = $this->kpLocalFilmsRepository->findOneByAsArray(['kpId' => $kpId]);
        $result[0] = $this->smartyDbAssistService->getVideoIdByKinopoiskId($kpId);
        foreach($result as $key => $value){
            $result[$key]['smartyId'] = $this->smartyDbAssistService->getVideoIdByKinopoiskId($kpId);
        }
        $final = array_merge($kpResult[0], $result[0]);
        return $final;
    }

    public function getTranscodeStatus($kpId){
        return $this->transcodingProcessesRepository->findOneByKpIdAsArray($kpId);
    }

    public function searchTranscodeStatus($status){
        return $this->transcodingProcessesRepository->findByTranscodingStatus($status);
    }

}
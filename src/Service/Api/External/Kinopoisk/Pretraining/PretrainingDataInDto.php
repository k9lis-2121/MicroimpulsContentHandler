<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining;

use App\Service\Api\External\Kinopoisk\Pretraining\checks\NullHalperService;
use App\Service\Api\External\Kinopoisk\Pretraining\Cleaners\CleanHelperService;
use App\Service\Api\External\Kinopoisk\Pretraining\Processing\KpDtoProcessingService;
use App\Service\Api\External\Kinopoisk\Pretraining\Converters\ConvertTypeService;
use App\DTO\KpDTO;


class PretrainingDataInDto
{
    private $kpDto;
    private $nullHalper;
    private $cleaner;
    private $processing;
    private $convertType;

    public function __construct(
            KpDTO $kpDTO,
            NullHalperService $nullHalperService,
            CleanHelperService $cleanHelperService,
            KpDtoProcessingService $kpDtoProcessingService,
            ConvertTypeService $convertTypeService,
        )
    {
        $this->kpDto = $kpDTO;
        $this->nullHalper = $nullHalperService;
        $this->cleaner = $cleanHelperService;
        $this->processing = $kpDtoProcessingService;
        $this->convertType = $convertTypeService;
    }

    private function nullChecks()
    {
        $this->nullHalper->checkName();
        $this->nullHalper->checkType();
        $this->nullHalper->checkIsSeries();
        $this->nullHalper->checkDescription();
        $this->nullHalper->checkShortDescription();
        $this->nullHalper->checkYear();
        $this->nullHalper->checkAlternativeName();
        $this->nullHalper->checkEnName();
        $this->nullHalper->checkAgeRating();
        $this->nullHalper->checkStatus();
        $this->nullHalper->checkMovieLength();
        $this->nullHalper->checkSeriesLength();
        $this->nullHalper->checkTotalSeriesLength();
    }

    private function cleanStrings(){
        $this->cleaner->cleanDtoKpString();
    }

    private function coutriesArrToString()
    {
        $this->convertType->coutriesToArray();
    }

    public function pretraining()
    {
        $this->nullChecks();
        $this->cleanStrings();
        $this->processing->getDirectorName();
        $this->coutriesArrToString();
    }
}
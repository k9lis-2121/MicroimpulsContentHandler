<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\Cleaners;

use App\Service\Cleaner\StringCleanerService;
use App\DTO\KpDTO;

class CleanHelperService
{
    private $cleanerString;
    private $kpDto;

    public function __construct(StringCleanerService $stringCleanerService, KpDto $kpDto)
    {
        $this->cleanerString = $stringCleanerService;
        $this->kpDto = $kpDto;
    }

    public function cleanDtoKpString()
    {
        $this->kpDto->setName($this->cleanerString->CleanName($this->kpDto->getName()));
        $this->kpDto->setAlternativeName($this->cleanerString->CleanName($this->kpDto->getAlternativeName()));
        $this->kpDto->setDescription($this->cleanerString->CleanDescription($this->kpDto->getDescription()));
        $this->kpDto->setShortDescription($this->cleanerString->CleanDescription($this->kpDto->getShortDescription()));
        $this->kpDto->setName($this->cleanerString->CleanName($this->kpDto->getName()));
    }
}
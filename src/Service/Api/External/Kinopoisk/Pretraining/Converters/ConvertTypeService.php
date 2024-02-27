<?php

namespace App\Service\Api\Kinopoisk\Pretraining\Coverters;
use App\DTO\KpDTO;

class ConvertTypeService
{

    private $kpDto;

    public function __construct(StringCleanerService $stringCleanerService, KpDto $kpDto)
    {
        $this->kpDto = $kpDto;
    }
    
    private function getArrToString(array $arr): string
    {
        $names = array_column($arr, 'name');
        $string = implode(', ', $names);
        return $string;
    }

    public function coutriesToArray()
    {
        $this->kpDto->setCountries = $this->getArrToString($this->kpDto->getCountries());
    }
}
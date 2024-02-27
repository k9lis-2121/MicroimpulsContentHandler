<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\checks;
use App\DTO\KpDTO;
use App\Service\Api\External\Kinopoisk\Pretraining\checks\Strings\CheckStirngService;
use App\Service\Api\External\Kinopoisk\Pretraining\checks\Ints\CheckIntService;
use App\Service\Api\External\Kinopoisk\Pretraining\checks\Bools\CheckBoolService;

class NullHalperService
{
    private $kpDto;
    private $checkString;
    private $checkInt;
    private $checkBool;

    public function __construct(
        KpDTO $kpDTO, 
        CheckStirngService $checkStirngService, 
        CheckIntService $checkIntService,
        CheckBoolService $checkBoolService,
        )
    {
        $this->kpDto = $kpDTO;
        $this->checkString = $checkStirngService;
        $this->checkInt = $checkIntService;
        $this->checkBool = $checkBoolService;
    }



    public function checkName()
    {
        $this->checkString->checkString('Name');
    }

    public function checkType()
    {
        $this->checkString->checkString('Type');
    }

    public function checkIsSeries()
    {
        $this->checkBool->checkBool('IsSeries');
    }

    public function checkDescription()
    {
        $this->checkString->checkString('Description');
    }

    public function checkShortDescription()
    {
        
        $this->checkString->checkString('ShortDescription');
    }

    public function checkYear(){
        $this->checkInt->checkInt('Year');
    }

    public function checkAlternativeName()
    {        
        $this->checkString->checkString('AlternativeName');
    }

    public function checkEnName()
    {        
        $this->checkString->checkString('EnName');
    }

    public function checkAgeRating()
    {
        $this->checkInt->checkInt('AgeRating');
    }

    public function checkStatus()
    {
        $this->checkString->checkString('Status');
    }

    public function checkMovieLength()
    {
        $this->checkInt->checkInt('MovieLength');
    }

    public function checkSeriesLength()
    {
        $this->checkInt->checkInt('SeriesLength');
    }

    public function checkTotalSeriesLength()
    {
        $this->checkInt->checkInt('totalSeriesLength');
    }
}
<?php


namespace App\Service\Api\External\Kinopoisk\Pretraining;
use App\DTO\KpDTO;

class JsonToDtoService
{
    private $kpDto;
    public function __construct(KpDto $kpDto)
    {
        $this->kpDto = $kpDto; 
    }

    public function dataSendToDto($data)
    {
        $kpResponseArray = json_decode($data->getContent(), true);
        $this->sendInDto($kpResponseArray);
    }


    private function sendInDto(array $data):void{
        $this->kpDto->setId($data['id']);
        $this->kpDto->setName($data['name']);
        $this->kpDto->setType($data['type']);
        $this->kpDto->setIsSeries($data['isSeries']);
        $this->kpDto->setExternalId($data['externalId']);
        $this->kpDto->setRating($data['rating']);
        $this->kpDto->setDescription($data['description']);
        $this->kpDto->setShortDescription($data['shortDescription']);
        $this->kpDto->setVotes($data['votes']);
        $this->kpDto->setYear($data['year']);
        $this->kpDto->setPoster($data['poster']);
        $this->kpDto->setGenres($data['genres']);
        $this->kpDto->setCountries($data['countries']);
        $this->kpDto->setSeasonsInfo($data['seasonsInfo']);
        $this->kpDto->setPersons($data['persons']);
        $this->kpDto->setAlternativeName($data['alternativeName']);
        $this->kpDto->setEnName($data['enName']);
        $this->kpDto->setNames($data['names']);
        $this->kpDto->setAgeRating($data['ageRating']);
        $this->kpDto->setStatus($data['status']);
        $this->kpDto->setMovieLength($data['movieLength']);
        $this->kpDto->setSeriesLength($data['seriesLength']);
        $this->kpDto->setTotalSeriesLength($data['totalSeriesLength']);
    }

}
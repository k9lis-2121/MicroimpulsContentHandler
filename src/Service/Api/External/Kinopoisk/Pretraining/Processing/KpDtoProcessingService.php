<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\Processing;
use App\DTO\KpDTO;
use App\Service\Cleaner\StringCleanerService;
use App\Service\Api\External\Kinopoisk\PretrainingDataService;

class KpDtoProcessingService
{
    private $kpDto;
    private $cleaner;
    private $pretrainingDataService;

    public function __construct(KpDTO $kpDTO, StringCleanerService $stringCleanerService, PretrainingDataService $pretrainingDataService)
    {
        $this->kpDto = $kpDTO;
        $this->cleaner = $stringCleanerService;
        $this->pretrainingDataService = $pretrainingDataService;
    }
    //     if(!empty($data['actors'])){
    //     foreach($data['actors'] as $actor){
    //         $actorId = $this->actorHelper->getIdActor($actor['name'], $actor['enName'], $actor['photo'], $actor['id']);
    //         dd('выход');
    //         $this->smartyDb->setActor($result['id'], $actorId);
    //     }
    // }else{
    //     dd('oi');
    // }
    public function getDirectorName()
    {
        $persons = $this->kpDto->getPersons();


        /**
         * Мне кажется код очевидным (пока что), но я один фиг потом забуду зачем именно так.
         * Поясняю:
         *      У нас в DTO есть массив с "Персонами" из которых нам нужно полчить режесеров (их может быть несколько)
         *   Актеры нам так же нужны, и что бы не дублировать код со строковыми проверками, оба изменения производятся тут
         *   И изначального массива удаляются режисеры
         */
        foreach($persons as $key => $person){

            $name = $this->cleaner->CleanName($person['name']);
            $enName = $this->cleaner->CleanName($person['enName']);

            if($person['enProfession'] == 'director' or $person['profession'] == 'режиссеры')
            {
                $directors[] = 
                            [
                                'id' => $person['id'],
                                'name' => $this->cleaner->CleanName($name),
                                'enName' => $this->cleaner->CleanName($enName),
                                'photo' => $person['photo'],
                                'description' => $this->cleaner->CleanName($person['description']),
                                'profession' => $this->cleaner->CleanName($person['profession']),
                                'enProfession' => $this->cleaner->CleanName($person['enProfession'])
                            ];
            }else{
                dump($this->cleaner->CleanName($enName));
                $newPerson['id'] = $person['id'];
                $newPerson['description'] = $this->cleaner->CleanName($person['description']);
                $newPerson['profession'] = $this->cleaner->CleanName($person['profession']);
                $newPerson['enProfession'] = $this->cleaner->CleanName($person['enProfession']);
                $newPerson['name'] = $this->cleaner->CleanName($name);
                $newPerson['enName'] = $this->cleaner->CleanName($enName);
                $newPerson['photo'] = $person['photo'];
                $actors[$key] = $newPerson;
            }         
        }
        $this->kpDto->setDirector($directors);
        dump($actors);
        $this->kpDto->setPersons($actors);
    }

    public function arrToStringByDto()
    {
        $this->kpDto->setCountries($this->pretrainingDataService->getArrToString($this->kpDto->getCountries));
    }
}
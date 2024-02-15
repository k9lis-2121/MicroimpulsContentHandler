<?php

namespace App\Service\Api\External\Kinopoisk;

use App\Repository\KplocalFilmsRepository;
use App\Repository\KplocalActorsRepository;
use App\Entity\KplocalFilms;
use App\Entity\KplocalAсtors;
use Doctrine\ORM\EntityManagerInterface;
class SaveLocalFilmService
{


    private $kpProcessor;
    private $entityManager;
    private $kplocalFilms;
    private $kplocalAсtors;
    private $getContent;

    public function __construct(
        KinopoiskProcessorService $kpProcessor, 
        EntityManagerInterface $entityManager, 
        KplocalFilmsRepository $kplocalFilms, 
        KplocalActorsRepository $kplocalAсtors,
        GetContentInfoService $getContent
        ){
            $this->kpProcessor = $kpProcessor;
            $this->entityManager = $entityManager;
            $this->kplocalFilms = $kplocalFilms;
            $this->kplocalActors = $kplocalAсtors;
            $this->getContent = $getContent;
        }


    public function saveLocal(string $kpId){
        $filmIsset = $this->kplocalFilms->findOneBy(['kpId' => $kpId]);

        if ($filmIsset == null) {
            
            $kpResponse = $this->getContent->sendApiRequest($kpId);
            $kpArray = json_decode($kpResponse->getContent(), true);
            $data = $this->kpProcessor->dataProcessing($kpArray);


            $kplocalFilmsEnt = new KplocalFilms;

            if($data['is_season'] == null){
                $data['is_season'] = 0;
            }

            $kplocalFilmsEnt->setKinopoiskId( $kpId );
            $kplocalFilmsEnt->setName($data['name']);
            $kplocalFilmsEnt->setDescription($data['description']);
            $kplocalFilmsEnt->setShortDescription($data['shortDescription']);
            $kplocalFilmsEnt->setCountries($data['countries']);
            $kplocalFilmsEnt->setKinopoiskRating($data['rating']['kp']);
            $kplocalFilmsEnt->setImdbRating($data['rating']['imdb']);
            $kplocalFilmsEnt->setDuration($data['duration']);
            $kplocalFilmsEnt->setIsSeason($data['is_season']);
            $kplocalFilmsEnt->setNameOrig($data['name_orig']);
            $kplocalFilmsEnt->setParentControl($data['parent_control']);
            $kplocalFilmsEnt->setDirector($data['director']);
            $kplocalFilmsEnt->setYear($data['year']);
            $kplocalFilmsEnt->setPoster($data['poster']['url']);
            $kplocalFilmsEnt->setGenres($data['genres']);
            $kplocalFilmsEnt->setAlternativeName('none');
            $kplocalFilmsEnt->setAgeRating($data['ageRating']);
            $kplocalFilmsEnt->setSeriesLength($data['seriesLength']);
            $kplocalFilmsEnt->setKpid($kpId);
            $this->entityManager->persist($kplocalFilmsEnt);
            $this->entityManager->flush();
        }
    }

    public function loadLocal($kpId){
        return $this->kplocalFilms->findOneBy(['kpId'=> $kpId]);
    }
}
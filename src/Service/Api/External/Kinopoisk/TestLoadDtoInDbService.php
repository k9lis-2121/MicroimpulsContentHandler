<?php


namespace App\Service\Api\External\Kinopoisk;
use App\DTO\KpDTO;
use App\Entity\KplocalActors;
use App\Entity\KplocalDirector;
use App\Entity\KplocalFilms;
use App\Repository\KplocalActorsRepository;
use App\Repository\KplocalDirectorRepository;
use App\Repository\KplocalFilmsRepository;
use Doctrine\ORM\EntityManagerInterface;

class TestLoadDtoInDbService
{
    private $kpDto;
    private $kpLocalActorsReposytory;
    private $kpLocalDirectorReposytory;
    private $kpLocalFilmsReposytory;
    private $entityManager;

    public function __construct(
        KpDTO $kpDTO,
        KplocalActors $kpLocalDirectorRepository,
        KplocalDirector $kpLocalFilmsRepository,
        KplocalFilms $kpLocalActorsRepository,
        EntityManagerInterface $entityManagerInterface,
    )
    {
        $this->kpDto = $kpDTO;
        $this->kpLocalActorsReposytory = $kpLocalActorsRepository;
        $this->kpLocalDirectorReposytory = $kpLocalDirectorRepository;
        $this->kpLocalFilmsReposytory = $kpLocalFilmsRepository;
        $this->entityManager = $entityManagerInterface;
    }


    public function saveFilmDtoInBase()
    {
        $newEntity = new KplocalFilms;
        $newEntity->setAgeRating($this->kpDto->getAgeRating());
        $newEntity->setName($this->kpDto->getName());
        $newEntity->setDescription($this->kpDto->getDescription());
        $newEntity->setShortDescription($this->kpDto->getShortDescription());
        $newEntity->setKinopoiskRating($this->kpDto->getRating()['kp']);
        $newEntity->setImdbRating($this->kpDto->getRating()['imdb']);
        $newEntity->setDuration($this->kpDto->getMovieLength());
        $newEntity->setIsSeason($this->kpDto->getIsSeries());
        $newEntity->setParentControl(0);
        $newEntity->setYear($this->kpDto->getYear());
        $newEntity->setDirector($this->kpDto->getDirector()[0]['name']);
        $newEntity->setKpId($this->kpDto->getId());
        $newEntity->setKinopoiskId($this->kpDto->getId());
        $newEntity->setCountries($this->kpDto->getCountries());
        $this->entityManager->persist($newEntity);
        $this->entityManager->flush();
    }

}
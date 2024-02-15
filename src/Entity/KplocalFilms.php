<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\KplocalFilmsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KplocalFilmsRepository::class)]
#[ApiResource]
class KplocalFilms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $countries = null;

    #[ORM\Column]
    private ?float $kinopoiskRating = null;

    #[ORM\Column]
    private ?float $imdbRating = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?bool $isSeason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameOrig = null;

    #[ORM\Column(length: 255)]
    private ?string $parentControl = null;

    #[ORM\Column(length: 255)]
    private ?string $director = null;

    #[ORM\Column(length: 255)]
    private ?string $kpId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private array $genres = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alternativeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ageRating = null;

    #[ORM\Column(nullable: true)]
    private ?int $seriesLength = null;

    #[ORM\Column(length: 255)]
    private ?string $kinopoiskId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getCountries(): ?string
    {
        return $this->countries;
    }

    public function setCountries(?string $countries): static
    {
        $this->countries = $countries;

        return $this;
    }

    public function getKinopoiskRating(): ?float
    {
        return $this->kinopoiskRating;
    }

    public function setKinopoiskRating(float $kinopoiskRating): static
    {
        $this->kinopoiskRating = $kinopoiskRating;

        return $this;
    }

    public function getImdbRating(): ?float
    {
        return $this->imdbRating;
    }

    public function setImdbRating(float $imdbRating): static
    {
        $this->imdbRating = $imdbRating;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function isIsSeason(): ?bool
    {
        return $this->isSeason;
    }

    public function setIsSeason(bool $isSeason): static
    {
        $this->isSeason = $isSeason;

        return $this;
    }

    public function getNameOrig(): ?string
    {
        return $this->nameOrig;
    }

    public function setNameOrig(?string $nameOrig): static
    {
        $this->nameOrig = $nameOrig;

        return $this;
    }

    public function getParentControl(): ?string
    {
        return $this->parentControl;
    }

    public function setParentControl(string $parentControl): static
    {
        $this->parentControl = $parentControl;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getKpId(): ?string
    {
        return $this->kpId;
    }

    public function setKpId(string $kpId): static
    {
        $this->kpId = $kpId;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getGenres(): array
    {
        return $this->genres;
    }

    public function setGenres(array $genres): static
    {
        $this->genres = $genres;

        return $this;
    }

    public function getAlternativeName(): ?string
    {
        return $this->alternativeName;
    }

    public function setAlternativeName(?string $alternativeName): static
    {
        $this->alternativeName = $alternativeName;

        return $this;
    }

    public function getAgeRating(): ?string
    {
        return $this->ageRating;
    }

    public function setAgeRating(?string $ageRating): static
    {
        $this->ageRating = $ageRating;

        return $this;
    }

    public function getSeriesLength(): ?int
    {
        return $this->seriesLength;
    }

    public function setSeriesLength(?int $seriesLength): static
    {
        $this->seriesLength = $seriesLength;

        return $this;
    }

    public function getKinopoiskId(): ?string
    {
        return $this->kinopoiskId;
    }

    public function setKinopoiskId(string $kinopoiskId): static
    {
        $this->kinopoiskId = $kinopoiskId;

        return $this;
    }
}

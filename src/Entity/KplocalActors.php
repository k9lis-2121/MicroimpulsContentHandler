<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\KplocalActorsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KplocalActorsRepository::class)]
#[ApiResource]
class KplocalActors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $filmId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $enName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enProffession = null;

    #[ORM\Column]
    private ?int $kpActorId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilmId(): ?int
    {
        return $this->filmId;
    }

    public function setFilmId(int $filmId): static
    {
        $this->filmId = $filmId;

        return $this;
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

    public function getEnName(): ?string
    {
        return $this->enName;
    }

    public function setEnName(string $enName): static
    {
        $this->enName = $enName;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getEnProffession(): ?string
    {
        return $this->enProffession;
    }

    public function setEnProffession(?string $enProffession): static
    {
        $this->enProffession = $enProffession;

        return $this;
    }

    public function getKpActorId(): ?int
    {
        return $this->kpActorId;
    }

    public function setKpActorId(int $kpActorId): static
    {
        $this->kpActorId = $kpActorId;

        return $this;
    }
}

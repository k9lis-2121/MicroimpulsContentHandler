<?php

namespace App\Entity;

use App\Repository\VodDirecoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VodDirecoryRepository::class)]
class VodDirecory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $kpId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dirIn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dirOut = null;

    #[ORM\Column]
    private ?bool $isSeason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hdd = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDirIn(): ?string
    {
        return $this->dirIn;
    }

    public function setDirIn(?string $dirIn): static
    {
        $this->dirIn = $dirIn;

        return $this;
    }

    public function getDirOut(): ?string
    {
        return $this->dirOut;
    }

    public function setDirOut(?string $dirOut): static
    {
        $this->dirOut = $dirOut;

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

    public function getHdd(): ?string
    {
        return $this->hdd;
    }

    public function setHdd(?string $hdd): static
    {
        $this->hdd = $hdd;

        return $this;
    }
}

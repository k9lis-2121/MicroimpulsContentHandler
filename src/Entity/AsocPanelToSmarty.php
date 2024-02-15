<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AsocPanelToSmartyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsocPanelToSmartyRepository::class)]
#[ApiResource]
class AsocPanelToSmarty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $KpId = null;

    #[ORM\Column(nullable: true)]
    private ?int $SmartyContentId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKpId(): ?string
    {
        return $this->KpId;
    }

    public function setKpId(string $KpId): static
    {
        $this->KpId = $KpId;

        return $this;
    }

    public function getSmartyContentId(): ?int
    {
        return $this->SmartyContentId;
    }

    public function setSmartyContentId(?int $SmartyContentId): static
    {
        $this->SmartyContentId = $SmartyContentId;

        return $this;
    }
}

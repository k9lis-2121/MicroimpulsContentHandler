<?php

namespace App\Entity;

use App\Repository\OtherDescriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OtherDescriptionRepository::class)]
class OtherDescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $content_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentId(): ?int
    {
        return $this->content_id;
    }

    public function setContentId(int $content_id): static
    {
        $this->content_id = $content_id;

        return $this;
    }

    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    public function setDescriptions(string $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }
}

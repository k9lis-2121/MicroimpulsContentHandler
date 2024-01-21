<?php

namespace App\Entity;

use App\Repository\RatiosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatiosRepository::class)]
class Ratios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $content_id = null;

    #[ORM\Column(length: 255)]
    private ?string $ratio = null;

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

    public function getRatio(): ?string
    {
        return $this->ratio;
    }

    public function setRatio(string $ratio): static
    {
        $this->ratio = $ratio;

        return $this;
    }
}

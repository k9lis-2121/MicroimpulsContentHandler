<?php

namespace App\Entity;

use App\Repository\DubsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DubsRepository::class)]
class Dubs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $content_id = null;

    #[ORM\Column(length: 255)]
    private ?string $dub = null;

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

    public function getDub(): ?string
    {
        return $this->dub;
    }

    public function setDub(string $dub): static
    {
        $this->dub = $dub;

        return $this;
    }
}

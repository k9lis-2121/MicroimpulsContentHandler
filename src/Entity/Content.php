<?php

namespace App\Entity;

use App\Repository\ContentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
class Content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $kp_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $sesonCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $allEpisodeCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKpId(): ?int
    {
        return $this->kp_id;
    }

    public function setKpId(int $kp_id): static
    {
        $this->kp_id = $kp_id;

        return $this;
    }

    public function getSesonCount(): ?int
    {
        return $this->sesonCount;
    }

    public function setSesonCount(?int $sesonCount): static
    {
        $this->sesonCount = $sesonCount;

        return $this;
    }

    public function getAllEpisodeCount(): ?int
    {
        return $this->allEpisodeCount;
    }

    public function setAllEpisodeCount(?int $allEpisodeCount): static
    {
        $this->allEpisodeCount = $allEpisodeCount;

        return $this;
    }
}

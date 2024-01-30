<?php

namespace App\Entity;

use App\Repository\ToastStatusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ToastStatusRepository::class)]
class ToastStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $component = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column]
    private ?bool $viewed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $kp_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }

    public function setComponent(?string $component): static
    {
        $this->component = $component;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function isViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): static
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function getKpId(): ?string
    {
        return $this->kp_id;
    }

    public function setKpId(?string $kp_id): static
    {
        $this->kp_id = $kp_id;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\TasksDirRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksDirRepository::class)]
class TasksDir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?array $results = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getResults(): ?array
    {
        return $this->results;
    }

    public function setResults(?array $results): static
    {
        $this->results = $results;

        return $this;
    }
}

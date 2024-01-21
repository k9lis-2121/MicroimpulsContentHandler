<?php

namespace App\Entity;

use App\Repository\TorrentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TorrentsRepository::class)]
class Torrents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $content_id = null;

    #[ORM\Column(length: 255)]
    private ?string $torrent = null;

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

    public function getTorrent(): ?string
    {
        return $this->torrent;
    }

    public function setTorrent(string $torrent): static
    {
        $this->torrent = $torrent;

        return $this;
    }
}

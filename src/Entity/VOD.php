<?php

namespace App\Entity;

use App\Repository\VODRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VODRepository::class)]
class VOD
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $kp_id = null;

    #[ORM\Column]
    private ?bool $content_type = null;

    #[ORM\Column(length: 255)]
    private ?string $content_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $parent_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $torrent_link = null;

    #[ORM\Column]
    private ?bool $transcoded = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $moderation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKpId(): ?int
    {
        return $this->kp_id;
    }

    public function setKpId(?int $kp_id): static
    {
        $this->kp_id = $kp_id;

        return $this;
    }

    public function isContentType(): ?bool
    {
        return $this->content_type;
    }

    public function setContentType(bool $content_type): static
    {
        $this->content_type = $content_type;

        return $this;
    }

    public function getContentName(): ?string
    {
        return $this->content_name;
    }

    public function setContentName(string $content_name): static
    {
        $this->content_name = $content_name;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): static
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    public function getTorrentLink(): ?string
    {
        return $this->torrent_link;
    }

    public function setTorrentLink(?string $torrent_link): static
    {
        $this->torrent_link = $torrent_link;

        return $this;
    }

    public function isTranscoded(): ?bool
    {
        return $this->transcoded;
    }

    public function setTranscoded(bool $transcoded): static
    {
        $this->transcoded = $transcoded;

        return $this;
    }

    public function getModeration(): ?string
    {
        return $this->moderation;
    }

    public function setModeration(?string $moderation): static
    {
        $this->moderation = $moderation;

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
}

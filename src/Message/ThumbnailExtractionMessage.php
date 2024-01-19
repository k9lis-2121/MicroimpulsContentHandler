<?php

namespace App\Message;

class ThumbnailExtractionMessage
{
    public $episode;
    public $contentDir;

    public function __construct(string $episode, string $contentDir)
    {
        $this->episode = $episode;
        $this->contentDir = $contentDir;
    }
}
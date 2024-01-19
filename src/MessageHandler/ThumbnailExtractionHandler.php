<?php

namespace App\MessageHandler;

use App\Message\ThumbnailExtractionMessage;
use App\Service\ThumbnailExtractorService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ThumbnailExtractionHandler implements MessageHandlerInterface
{
    private $thumbnailExtractor;

    public function __construct(ThumbnailExtractorService $thumbnailExtractor)
    {
        $this->thumbnailExtractor = $thumbnailExtractor;
    }

    public function __invoke(ThumbnailExtractionMessage $message)
    {
        $this->thumbnailExtractor->handleThumbnailExtraction($message);
    }
}
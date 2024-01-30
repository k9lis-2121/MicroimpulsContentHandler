<?php

namespace App\Interfaces\Service;

/**
 * Воркер по созданию скриншотов
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method void handleThumbnailExtraction()
 * @version 1.0.0
 */
interface ThumbnailExtractorInterface
{
    /**
     * Воркер по созданию скриншотов
     *
     * @param ThumbnailExtractionMessage $message
     * @return void
     */
    public function handleThumbnailExtraction(ThumbnailExtractionMessage $message): void;
}
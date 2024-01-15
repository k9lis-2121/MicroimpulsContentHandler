<?php

namespace App\Interface\Service\ImageHandler;

use Symfony\Bundle\MakerBundle\Str;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @method string createDirectory()
* @method string moveImagesToDirectory()
* @version 1.0.0
*/
interface ImageDirectoryHelperInterface
{
    /**
     * Создание директории с ID видео смарти
     *
     * @param string $directoryName
     * @return string
     */
    public function createDirectory(string $directoryName): string;

    /**
     * Перемещение изображения
     *
     * @param string $sourceDirectory
     * @param string $targetDirectory
     * @param string $filename
     * @return void
     */
    public function moveImagesToDirectory(string $sourceDirectory, string $targetDirectory, string $filename): void;
}
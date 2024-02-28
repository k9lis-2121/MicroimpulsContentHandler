<?php

namespace App\Interfaces\Service;

/**
 * Сервис для работы с ffmpeg
 * 
 * @author Валерий Ожерельев 
 * @method void extractThumbnail()
 * @method int getVideoDuration()
 * @version 1.0.0
 */
interface FfmpegInterface
{
    /**
     * Метод для извлечения скриншотов из видеофайлов
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $outputDirectory
     * @return void
     */
    public function extractThumbnail(string $inputFile, string $outputFile, string $outputDirectory): void;
    
    /**
     * Метод для извлечения длительности видеофайла
     *
     * @param string $path
     * @return integer
     */
    public function getVideoDuration(string $path): int;
}
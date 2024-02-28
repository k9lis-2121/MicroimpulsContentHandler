<?php

namespace App\Service;

use App\Interfaces\Service\FfmpegInterface;
use Symfony\Component\Process\Process;

/**
 * Сервис для работы с ffmpeg
 * 
 * @author Валерий Ожерельев 
 * @method void extractThumbnail()
 * @method int getVideoDuration()
 * @version 1.0.0
 */
class FfmpegService implements FfmpegInterface
{
    

    /**
     * Метод для извлечения скриншотов из видеофайлов
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $outputDirectory
     * @return void
     */
    public function extractThumbnail(string $inputFile, string $outputFile, string $outputDirectory): void
    {
        $ffmpeg = new Process([
            'ffmpeg',
            '-i',
            $inputFile,
            //Секретный код
            "$outputDirectory/$outputFile",
        ]);

        $ffmpeg->mustRun();
    }

    /**
     * Метод для извлечения длительности видеофайла
     *
     * @param string $path
     * @return integer
     */
    public function getVideoDuration(string $path): int
    {
        $ffprobe = new Process([
            'ffprobe',
            '-v',
            
            //Секретный код
            $path,
        ]);

        try {
            $ffprobe->mustRun();
            $output = $ffprobe->getOutput();
            $duration = (float) trim($output);
            $durationInMinutes = (int) floor($duration / 60);
            return $durationInMinutes;
        } catch (ProcessFailedException $exception) {
            return 0;
        } catch (\Throwable $error) {
            return 0;
        }
    }


}
<?php

namespace App\Service;

use App\Interfaces\Service\FfmpegInterface;
use Symfony\Component\Process\Process;

/**
 * Сервис для работы с ffmpeg
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
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
            '-r',
            '0.2',
            '-vframes',
            '1',
            '-y',
            '-f',
            'image2',
            '-q:v',
            '1',
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
            'error',
            '-show_entries',
            'format=duration',
            '-of',
            'default=noprint_wrappers=1:nokey=1',
            $path,
        ]);

        try {
            $ffprobe->mustRun();
            $output = $ffprobe->getOutput();
            $duration = (float) trim($output);
            $durationInMinutes = (int) floor($duration / 60);
            return $durationInMinutes;
        } catch (ProcessFailedException $exception) {
            // Обработка ошибки, если произошла
            // неудачная попытка выполнить команду ffprobe
            return 0;
        } catch (\Throwable $error) {
            // Обработка других типов ошибок и исключений
            return 0;
        }
    }


}
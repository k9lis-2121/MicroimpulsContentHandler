<?php

namespace App\Service;

use Symfony\Component\Process\Process;

class FfmpegService
{
    


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
        }
    }


}
<?php

namespace App\Service;

use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;
use App\Service\DbAssist\SmartyDbAssistService;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\FfmpegService;
use App\Message\ThumbnailExtractionMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\DBAL\Connection;

class ThumbnailExtractorService
{
    
    private $smartyDb;
    private $dirMaker;
    private $ffmpeg;
    private $messageBus;
    private $db;

    /**
     * Инициализация вспомогательных сервисов
     *
     * @param MessageBusInterface $messageBus
     * @param SmartyDbAssistService $smartyDb
     * @param DirMakerService $dirMaker
     * @param FfmpegService $ffmpeg
     */
    public function __construct(MessageBusInterface $messageBus, SmartyDbAssistService $smartyDb, DirMakerService $dirMaker, FfmpegService $ffmpeg, Connection $db){
        $this->smartyDb = $smartyDb;
        $this->dirMaker = $dirMaker;
        $this->ffmpeg = $ffmpeg;
        $this->messageBus = $messageBus;
        $this->db = $db;
    }

    /**
     * Поиск наилучшего разрешения в файлах
     *
     * @param [type] $directory
     * @return string|null
     */
    private function findHighestResolution($directory): ?string
    {
        $finder = new Finder();
        $finder->directories()->in($directory);

        $resolutions = ['1080p', '720p', '480p', '320p'];

        $highestResolution = null;
        foreach ($resolutions as $resolution) {
            $directories = iterator_to_array($finder->name('/'.$resolution.'$/'));
            if (!empty($directories)) {
                $highestResolution = $resolution;
                break;
            }
        }

        return $highestResolution;
    }

    /**
     * Извлечение скриншотов
     *
     * @param ThumbnailExtractionMessage $message
     * @return void
     * @ticket Здесь можно интегрировать запись статуса в mysql
     */
    public function handleThumbnailExtraction(ThumbnailExtractionMessage $message): void
    {
        $episode = $message->episode;
        $contentDir = $message->contentDir;

        $workDir = $this->findHighestResolution($contentDir);
        $dirScreen = '/mnt/adddata/panel_v3/public/img/mnt/smarty/tvmiddleware/video/episode/'.$episode;
        $this->dirMaker->makeScreenDir($dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_087.ts', 'screen1.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_088.ts', 'screen2.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_089.ts', 'screen3.jpg', $dirScreen);
        $this->smartyDb->setScreenEpisode($episode);
        $this->db->update('tasks_screen', ['status' => 'завершена'], ['episode' => $episode]);
    }

}
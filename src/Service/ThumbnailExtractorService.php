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
use App\Interfaces\Service\ThumbnailExtractorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Валерий Ожерельев 
 * @method void handleThumbnailExtraction()
 * @version 1.0.0
 */
class ThumbnailExtractorService
{
    
    private $smartyDb;
    private $dirMaker;
    private $ffmpeg;
    private $messageBus;
    private $db;
    private $episodeDir;

    /**
     * Инициализация вспомогательных сервисов
     *
     * @param MessageBusInterface $messageBus
     * @param SmartyDbAssistService $smartyDb
     * @param DirMakerService $dirMaker
     * @param FfmpegService $ffmpeg
     */
    public function __construct(ParameterBagInterface $parameterBag, MessageBusInterface $messageBus, SmartyDbAssistService $smartyDb, DirMakerService $dirMaker, FfmpegService $ffmpeg, Connection $db){
        $this->smartyDb = $smartyDb;
        $this->dirMaker = $dirMaker;
        $this->ffmpeg = $ffmpeg;
        $this->messageBus = $messageBus;
        $this->db = $db;
        $this->episodeDir = $parameterBag->get('kernel.project_dir').'/'.$parameterBag->get('SMARTY_IMAGE_EPISODE_SCREENS_DIR');
    }

    /**
     * Поиск наилучшего разрешения в файлах
     *
     * @param string $directory
     * @return string|null
     */
    private function findHighestResolution(string $directory): ?string
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
     */
    public function handleThumbnailExtraction(ThumbnailExtractionMessage $message): void
    {
        $episode = $message->episode;
        $contentDir = $message->contentDir;

        $workDir = $this->findHighestResolution($contentDir);
        $dirScreen = $this->episodeDir.$episode;
        dump($dirScreen);
        dump($contentDir.'/'.$workDir.'/'.$workDir.'_087.ts');
        $this->dirMaker->makeScreenDir($dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_087.ts', 'screen1.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_088.ts', 'screen2.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_089.ts', 'screen3.jpg', $dirScreen);
        $this->smartyDb->setScreenEpisode($episode);
        $this->db->update('tasks_screen', ['status' => 'завершена'], ['episode' => $episode]);
        
        /*
            не уместное создание статусов для тостов, слишком много будет генерироваться, нужно будет либо переработать воркер, либо как-то группировать задачи по созданию скриншотов для последующей выборки и создания тоста когда все скриншоты готовы
        */
        $this->db->update('toast_status', ['component' => 'WorkerThumbnailExtractor', 'title' => 'episode_id '.$episode , 'body' => 'Создание скриншота, завершено', 'viewed' => 0], ['title' => 'episode_id '.$episode]);
    }

}
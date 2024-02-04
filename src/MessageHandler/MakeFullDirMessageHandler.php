<?php

namespace App\MessageHandler;

use App\Message\MakeFullDirMessage;
use App\Service\Api\Inside\MakeContentDirService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\DBAL\Connection;
use App\Repository\VodDirTemplateRepository;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\DiskHandler\CheckFreeSizeService;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class MakeFullDirMessageHandler implements MessageHandlerInterface
{
    private $makeFullDirMessage;
    private $db;
    private $vodDirTemplateRepository;
    private $dirMakerService;
    private $logger;
    private $disk;

    public function __construct(MakeContentDirService $makeFullDirMessage, Connection $db, VodDirTemplateRepository $vodDirTemplateRepository, DirMakerService $dirMakerService, LoggerInterface $logger, CheckFreeSizeService $disk)
    {
        $this->makeFullDirMessage = $makeFullDirMessage;
        $this->db = $db;
        $this->vodDirTemplateRepository = $vodDirTemplateRepository;
        $this->dirMakerService = $dirMakerService;
        $this->logger = $logger;
        $this->disk = $disk;
    }

    public function __invoke(MakeFullDirMessage $message)
    {

        $data = $message->getData();
        $this->db->insert('tasks_dir', [
            'title' => $data['title'],
            'status' => 'в очереди',
        ]);
        

        $movie = $this->vodDirTemplateRepository->findOneBy(['title' => 'Фильм']);
        $hddNum = $this->disk->getFreeDisk();
        $tmpMovie = $movie->getTemplate();
        $season = $this->vodDirTemplateRepository->findOneBy(['title' => 'Сериал']);
        $tmpSeason = $season->getTemplate();
        $template = [
            'season' => $tmpSeason,
            'movie' => $tmpMovie
        ];



        $baseDir = $this->dirMakerService->makeBaseDir($data, $template);

        if ($data['isTrailler']) {
            $trailerDir = $this->dirMakerService->makeTraillerDir($data, $tmpMovie . '/trailer');
        }

        if ($data['seasonCount'] or $data['sameEpisodesCount']) {
            $seasonDir = $this->dirMakerService->dirCreateSE($data, $template);
            dump($seasonDir);
        }

        if ($data['isTrailler']) {
            $result[] = str_replace('/HDD' . '/' . $data['selectedDisk']. "/VOD/content"."/", '', $trailerDir['dir']);
        } elseif ($data['isSerial']) {
            foreach ($seasonDir as $arr) {
                foreach ($arr as $key => $value) {
                    if ($key == 'dir') {
                        $result['dir'][] = str_replace('/HDD' . '/' . $data['selectedDisk']. "/VOD/content"."/", '', $value);
                    }
                }
            }
        } else {
            $result[] = str_replace('/HDD' . '/' . $data['selectedDisk']. "/VOD/content"."/", '', $baseDir['dir']);
        }
        
        
        $this->db->update('tasks_dir', ['status' => 'завершена', 'results' => json_encode($result)], ['title' => $data['title']]);
        $this->db->insert('toast_status', ['component' => 'WorkerDirectoryMaker', 'title' => $data['kinopoiskId'], 'body' => 'Создание директорий завершено', 'viewed' => 0]);
    }
}
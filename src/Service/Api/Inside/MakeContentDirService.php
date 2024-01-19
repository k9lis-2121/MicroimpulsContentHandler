<?php

namespace App\Service\Api\Inside;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\VodDirTemplateRepository;
use App\Service\ContentDirHandler\DirMakerService;
use App\Message\MakeFullDirMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\Queue\MakeFullDirQueueService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;

/**
 * Класс управляет директориями контента
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array makeFullDir()
 * @version 1.0.0
 */
class MakeContentDirService
{


    private $vodDirTemplateRepository;
    private $dirMakerService;
    private $taskDirManager;
    private $entityManager;
    private $logger;
    private $taskManager;
    private $messageBus;
    private $db;

    public function __construct(Connection $db, MakeFullDirQueueService $taskManager, VodDirTemplateRepository $vodDirTemplateRepository, DirMakerService $dirMakerService, MessageBusInterface $messageBus, EntityManagerInterface $entityManager, MakeFullDirQueueService $taskDirManager, LoggerInterface $logger){
        $this->vodDirTemplateRepository = $vodDirTemplateRepository;
        $this->dirMakerService = $dirMakerService;
        
        $this->taskDirManager = $taskDirManager;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->taskManager = $taskManager;
        $this->messageBus = $messageBus;
        $this->db = $db;
    }


   
    public function makeFullDir(MakeFullDirMessage $message): void
    {
        $data = $message->getMessage();
        $this->logger->warning('MAKE CONTENT DIR SEVICE LOGGER');
        $task = $this->taskDirManager->createTask($data['title']);
        $this->bus->dispatch(new MakeFullDirMessage($data));
        $this->taskDirManager->updateTaskStatus($task, 'queued');

        $movie = $this->vodDirTemplateRepository->findOneBy(['title' => 'Фильм']);
        $tmpMovie = $movie->getTemplate();
        $season = $this->vodDirTemplateRepository->findOneBy(['title' => 'Сериал']);
        $tmpSeason = $season->getTemplate();
        $template = [
            'season' => $tmpSeason,
            'movie' => $tmpMovie
        ];



        $baseDir = $this->dirMakerService->makeBaseDir($data, $template);
        // if ($data['request']->files->get('file')) {
        //     $this->dirMakerService->infoFileLoader($data['request']->files->get('file'), $baseDir['dir']);
        // }

        if ($data['isTrailler']) {
            $trailerDir = $this->dirMakerService->makeTraillerDir($data, $tmpMovie . '/trailer');
        }

        if ($data['seasonCount'] or $data['sameEpisodesCount']) {
            $seasonDir = $this->dirMakerService->dirCreateSE($data, $template);
            dump($seasonDir);
        }

        if ($data['isTrailler']) {
            $result[] = str_replace('/VOD' . '/', '', $trailerDir['dir']);
        } elseif ($data['isSerial']) {
            foreach ($seasonDir as $arr) {
                foreach ($arr as $key => $value) {
                    if ($key == 'dir') {
                        $result['dir'][] = str_replace('/VOD' . '/', '', $value);
                    }
                }
            }
        } else {
            $result[] = str_replace('/VOD' . '/', '', $baseDir['dir']);
        }
        
        
        $this->logger->warning('UPDATE SUKA HEINA');
        dump($result);
        dump(json_encode($result));
        $this->logger->warning('END!!!!!!');
        
        $this->logger->warning($result);
        $this->db->update('tasks_dir', ['status' => 'завершена1', 'results' => json_encode($result)], ['title' => $data['title']]);
        // return $result;

    }
}
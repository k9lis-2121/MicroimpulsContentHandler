<?php

namespace App\Service\Queue;

use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\MakeFullDirMessage;
use Doctrine\DBAL\Connection;

/**
 * Undocumented class
 */
class MakeFullDirQueueService
{
    private $messageBus;
    private $db;

    /**
     * Undocumented function
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus, Connection $db)
    {
        $this->messageBus = $messageBus;
        $this->db = $db;
    }

    /**
     * Undocumented function
     *
     * @param array $requestData
     * @return void
     */
    public function enqueueMakeFullDir(array $requestData): void
    {
        dump($requestData);
        $this->messageBus->dispatch(new MakeFullDirMessage($requestData['data']));

         // Сохраняем задачу в таблице "tasks"
         $this->db->insert('tasks_dir', [
            'title' => $requestData['data']['title'],
            'status' => 'в очереди',
        ]);
    }
}
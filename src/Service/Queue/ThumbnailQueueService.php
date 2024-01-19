<?php

namespace App\Service\Queue;

use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ThumbnailExtractionMessage;
use Doctrine\DBAL\Connection;

/**
 * Undocumented class
 */
class ThumbnailQueueService
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
     * @param string $episode
     * @param string $contentDir
     * @return void
     */
    public function enqueueThumbnailExtraction(string $episode, string $contentDir): void
    {
        $this->messageBus->dispatch(new ThumbnailExtractionMessage($episode, $contentDir));

         // Сохраняем задачу в таблице "tasks"
         $this->db->insert('tasks_screen', [
            'episode' => $episode,
            'content_dir' => $contentDir,
            'status' => 'в очереди',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
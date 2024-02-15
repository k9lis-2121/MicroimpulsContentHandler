<?php

namespace App\MessageHandler;


use App\Message\SmartyContentCreatorMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\DBAL\Connection;


#[AsMessageHandler]
class SmartyContentCreatorMessageHandler
{

    private $kpProcessor;

    public function __construct(
        KinopoiskProcessorService $kpProcessor,
        private MessageBusInterface $bus,
        Connection $db,
    )
    {
        $this->kpProcessor = $kpProcessor;
        $this->db = $db;
    }

    public function __invoke(SmartyContentCreatorMessage $message)
    {

        $kpresponse = $message->getKinopoiskData();
        $selectedDisk = $message->getSelectedDisk();
        $createdVideoResponse = $this->kpProcessor->sendDataInSmarty($kpresponse, $selectedDisk); 
        
        $this->db->insert('asoc_panel_to_smarty', ['kp_id' => $kpresponse->getKinopoiskId(), 'smarty_content_id' => $createdVideoResponse['id']]);
        $this->db->insert('toast_status', ['component' => 'WorkerSmartyCreator', 'title' => $kpresponse->getKinopoiskId(), 'body' => 'Добавление в смарти завершено', 'viewed' => 0]);
    }
}

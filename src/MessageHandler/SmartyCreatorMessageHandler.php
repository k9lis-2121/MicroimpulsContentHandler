<?php


namespace App\MessageHandler;

use App\Message\SmartyCreatorMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\FfmpegService;
use App\Service\Api\Inside\SerialHelperService;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\DBAL\Connection;


#[AsMessageHandler]
class SmartyCreatorMessageHandler
{

    private $kpProcessor;
    private $getContentInfo;
    private $smartyApi;
    private $ffmpeg;
    private $serialHelper;

    public function __construct(
        GetContentInfoService $getContentInfo,
        KinopoiskProcessorService $kpProcessor,
        SmartyContentApiService $smartyApi,
        FfmpegService $ffmpeg,
        SerialHelperService $serialHelper,
        private MessageBusInterface $bus,
        Connection $db,
    )
    {
        $this->getContentInfo = $getContentInfo;
        $this->kpProcessor = $kpProcessor;
        $this->smartyApi = $smartyApi;
        $this->ffmpeg = $ffmpeg;
        $this->serialHelper = $serialHelper;
        $this->db = $db;
    }

    public function __invoke(SmartyCreatorMessage $message)
    {

        $data = $message->getData();
        $kpresponse = $message->getKinopoiskData();
        $makeDirResult = $message->getMakeDirResult();

        $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
        $kinopoiskData = json_decode($kpresponse->getContent(), true);
        $kinopoiskDataProcessing = $this->kpProcessor->dataProcessing($kinopoiskData);
        $createdVideoResponse = $this->kpProcessor->sendDataInSmarty($kinopoiskDataProcessing);

        

        if ($data['isTrailler']) {

            $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $makeDirResult['dir'][0] . '/playlist.m3u8');
            $this->smartyApi->createVideoFile('Трейлер', $createdVideoResponse['id'], ['is_trailer' => 1, 'filename' => $makeDirResult[0], 'duration' => $duration]);
        } elseif ($data['isSerial']) {

            $this->serialHelper->makeSeasonAndEpisodeInSmarty($data, $createdVideoResponse['id'], $makeDirResult);
        } else {

            $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $makeDirResult['dir'][0] . '/playlist.m3u8');
            $this->smartyApi->createVideoFile('Фильм', $createdVideoResponse['id'], ['filename' => $makeDirResult['dir'][0], 'duration' => $duration]);
        }

        
        $this->db->insert('toast_status', ['component' => 'WorkerSmartyCreator', 'title' => $data['kinopoiskId'], 'body' => 'Добавление в смарти завершено', 'viewed' => 0]);
    }
}

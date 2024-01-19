<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\VodDirTemplate;
use App\Repository\TasksDirRepository;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\ThumbnailExtractorService;
use App\Service\FfmpegService;
use App\Service\Api\Inside\MakeContentDirService;
use App\Service\Api\Inside\SerialHelperService;
use App\Service\Queue\MakeFullDirQueueService;
use App\Message\MakeFullDirMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\SmartyCreatorMessage;

use Doctrine\ORM\EntityManagerInterface;
/*
 
План:
Реализовать создание директорий для фильмов
Реализовать создание директорий для сериалов
Реализовать создание директорий для трейлеров

Релизовать добавление в локальную базу информации о директориях

Реализовать добавления в смарти
Есть какая-то проблема с загрузкой актеров, а скорее всего проблема именно в конвертере изображений

*/

/**
 * Контроллер реализующий авторматическую загрузку фильмов в смарти и создающий структуру директорий под него
 */
class ApiMakerDirController extends AbstractController
{

    private $kpProcessor;
    private $getContentInfo;
    private $smartyApi;
    private $ffmpeg;
    private $makeContentDir;
    private $serialHelper;

    /**
     * В конструктор загружаются вспомогательные сервисы
     *
     * @param GetContentInfoService $getContentInfo
     * @param KinopoiskProcessorService $kpProcessor
     * @param SmartyContentApiService $smartyApi
     * @param FfmpegService $ffmpeg
     * @param SertialHelperService $serialHelper
     */
    public function __construct(
        GetContentInfoService $getContentInfo,
        KinopoiskProcessorService $kpProcessor,
        SmartyContentApiService $smartyApi,
        FfmpegService $ffmpeg,
        MakeFullDirQueueService $makeContentDir,
        SerialHelperService $serialHelper,
        private MessageBusInterface $bus,
    ) {
        $this->getContentInfo = $getContentInfo;
        $this->kpProcessor = $kpProcessor;
        $this->smartyApi = $smartyApi;
        $this->ffmpeg = $ffmpeg;
        $this->makeContentDir = $makeContentDir;
        $this->serialHelper = $serialHelper;
    }

    /**
     * Огромный контроллер монстр, который я не знаю как оптимизировать
     *
     * @param Request $request
     * @param VodDirTemplateRepository $vodDirTemplateRepository
     * @param DirMakerService $dirMakerService
     * @return Response
     */
    #[Route('/api/maker/dir', name: 'app_api_maker_dir', methods: ['POST'])]
    public function index(Request $request, TasksDirRepository $taskDirRepository, DirMakerService $dirMakerService, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->get('data'), true);

        $message = ['data' => $data, 'request' => $request];
        // $result = $this->makeContentDir->enqueueMakeFullDir($message);
        $this->bus->dispatch(new MakeFullDirMessage($data));




        sleep(5);
        for($i = 0; $i < 100;){
            $taskDir = $taskDirRepository->findOneBy(['title' => $data['title']]);
            if ($taskDir == null) {
                $entityManager->refresh($taskDir);
                dump('null');
                $i++;
            }else{
                $dirStatus = $taskDir->getStatus();
                if($dirStatus == 'завершена'){
                    $i=100;

                    $result = $taskDir->getResults();

                    dump('succesful');

                    $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
                    $kinopoiskData = json_decode($kpresponse->getContent(), true);

                    dump('8============)');
                    dump($data);
                    $this->bus->dispatch(new SmartyCreatorMessage($data, $kinopoiskData, $result));

                    // $kinopoiskDataProcessing = $this->kpProcessor->dataProcessing($kinopoiskData);
                    // $createdVideoResponse = $this->kpProcessor->sendDataInSmarty($kinopoiskDataProcessing);

                    // if ($data['isTrailler']) {

                    //     $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result['dir'][0] . '/playlist.m3u8');
                    //     $this->smartyApi->createVideoFile('Трейлер', $createdVideoResponse['id'], ['is_trailer' => 1, 'filename' => $result[0], 'duration' => $duration]);
                        
                    // } elseif ($data['isSerial']) {

                    //     $this->serialHelper->makeSeasonAndEpisodeInSmarty($data, $createdVideoResponse['id'], $result);
                        
                    // } else {

                    //     $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result['dir'][0] . '/playlist.m3u8');
                    //     $this->smartyApi->createVideoFile('Фильм', $createdVideoResponse['id'], ['filename' => $result['dir'][0], 'duration' => $duration]);

                    // }


                }
            }
        }
        $response = [
            'message' => 'Запись добавлена',
        ];

        return $this->json($response);
    }
}

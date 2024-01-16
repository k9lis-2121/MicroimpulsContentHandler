<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\VodDirTemplate;
use App\Repository\VodDirTemplateRepository;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\ThumbnailExtractorService;
use App\Service\FfmpegService;
use App\Service\Api\Inside\MakeContentDirService;
use App\Service\Api\Inside\SerialHelperService;


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
        MakeContentDirService $makeContentDir,
        SerialHelperService $serialHelper
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
    public function index(Request $request, VodDirTemplateRepository $vodDirTemplateRepository, DirMakerService $dirMakerService): Response
    {
        $data = json_decode($request->get('data'), true);

        $result = $this->makeContentDir->makeFullDir($request, $data);


        $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
        $kinopoiskData = json_decode($kpresponse->getContent(), true);
        $kinopoiskDataProcessing = $this->kpProcessor->dataProcessing($kinopoiskData);
        $createdVideoResponse = $this->kpProcessor->sendDataInSmarty($kinopoiskDataProcessing);

        if ($data['isTrailler']) {

            $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result[0] . '/playlist.m3u8');
            $this->smartyApi->createVideoFile('Трейлер', $createdVideoResponse['id'], ['is_trailer' => 1, 'filename' => $result[0], 'duration' => $duration]);
            
        } elseif ($data['isSerial']) {

            $this->serialHelper->makeSeasonAndEpisodeInSmarty($data, $createdVideoResponse['id'], $result);
            
        } else {

            $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result[0] . '/playlist.m3u8');
            $this->smartyApi->createVideoFile('Фильм', $createdVideoResponse['id'], ['filename' => $result[0], 'duration' => $duration]);

        }


        $response = [
            'message' => 'Запись добавлена',
        ];

        return $this->json($response);
    }
}

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
    private $thumbnailExtractor;
    private $ffmpeg;

    /**
     * В конструктор загружаются вспомогательные сервисы
     *
     * @param GetContentInfoService $getContentInfo
     * @param KinopoiskProcessorService $kpProcessor
     * @param SmartyContentApiService $smartyApi
     * @param ThumbnailExtractorService $thumbnailExtractor
     * @param FfmpegService $ffmpeg
     */
    public function __construct(
                                    GetContentInfoService $getContentInfo, 
                                    KinopoiskProcessorService $kpProcessor, 
                                    SmartyContentApiService $smartyApi,
                                    ThumbnailExtractorService $thumbnailExtractor,
                                    FfmpegService $ffmpeg
                                ){
        $this->getContentInfo = $getContentInfo;
        $this->kpProcessor = $kpProcessor;
        $this->smartyApi = $smartyApi;
        $this->thumbnailExtractor = $thumbnailExtractor;
        $this->ffmpeg = $ffmpeg;
    }

    /**
     * Вспомогательная функция слишком малая для выноса в отдельный сервис
     *
     * @param integer $number
     * @return integer
     */
    private function numStandardizer(int $number): int
    {
        if($number < 10){
            $numStandardizeded = '0'.$number;
        }else{
            $numStandardizeded = $number;
        }

        return (int) $numStandardizeded;
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
        $filesystem = new Filesystem();



        $data = json_decode($request->get('data'), true);



        $movie = $vodDirTemplateRepository->findOneBy(['title' => 'Фильм']);
        $tmpMovie = $movie->getTemplate();
        $season = $vodDirTemplateRepository->findOneBy(['title' => 'Сериал']);
        $tmpSeason = $season->getTemplate();
        $template = [
            'season' => $tmpSeason,
            'movie' => $tmpMovie
        ];


        
    $baseDir = $dirMakerService->makeBaseDir($data, $template);
    if ($request->files->get('file')) {
        $dirMakerService->infoFileLoader($request->files->get('file'), $baseDir['dir']);
    }
            
            if($data['isTrailler']){
                $trailerDir = $dirMakerService->makeTraillerDir($data, $tmpMovie.'/trailer');
            }

            if($data['seasonCount'] or $data['sameEpisodesCount']){
                $seasonDir = $dirMakerService->dirCreateSE($data, $template);
                dump($seasonDir);
            }
            
            if($data['isTrailler']){
                $result[] = str_replace('/VOD'.'/', '', $trailerDir['dir']);
            }elseif($data['isSerial']){
                foreach($seasonDir as $arr){
                    foreach($arr as $key => $value){
                        if($key == 'dir'){
                            $result['dir'][] = str_replace('/VOD'.'/', '', $value);
                        }
                    }
                }
                
            }else{
                $result[] = str_replace('/VOD'.'/', '', $baseDir['dir']);
            }


            $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
            $kinopoiskData = json_decode($kpresponse->getContent(), true);
            $kinopoiskDataProcessing = $this->kpProcessor->dataProcessing($kinopoiskData);
            $createdVideoResponse = $this->kpProcessor->sendDataInSmarty($kinopoiskDataProcessing);
            
            if($data['isTrailler']){
                $duration = $this->ffmpeg->getVideoDuration('/VOD'.'/'.$result[0].'/playlist.m3u8');
                $videoFile = $this->smartyApi->createVideoFile('Трейлер', $createdVideoResponse['id'], ['is_trailer' => 1, 'filename' => $result[0], 'duration' => $duration]);
            }elseif($data['isSerial']){

                $resultCount = 0;
                foreach($data['episodesCount'] as $season => $episdoes){

                    $seasonNum = $this->numStandardizer($season);

                    $smartySeason = $this->smartyApi->createSeason('Сезон '. $seasonNum, $createdVideoResponse['id']);
                    for($i = 1; $i<=$episdoes; $i++){

                        $episodeNum = $this->numStandardizer($i);
                                                               
                        $duration = $this->ffmpeg->getVideoDuration('/VOD'.'/'.$result['dir'][$resultCount].'/playlist.m3u8');

                        $smartyEpisode = $this->smartyApi->createEpisode(
                                                                            $createdVideoResponse['id'], 
                                                                            'Серия '.$episodeNum, $smartySeason['id'], 
                                                                            ['duration' => $duration]
                                                                        );
                        
                        $contentDir = '/VOD'.'/'.$result['dir'][$resultCount];
                        $addScreenResult = $this->thumbnailExtractor->extractThumbnails($smartyEpisode['id'], $contentDir);
                        $videoFile[] = $this->smartyApi->createVideoFile(
                                                                            'Сезон '.$seasonNum.' Серия '.$episodeNum, $createdVideoResponse['id'], 
                                                                            [
                                                                                'filename' => $result['dir'][$resultCount], 
                                                                                'episode_id' => $smartyEpisode['id'], 
                                                                                'duration' => $duration
                                                                            ]
                                                                        );
                        $resultCount++;
                        
                    }

                }

            }else{
                $duration = $this->ffmpeg->getVideoDuration('/VOD'.'/'.$result[0].'/playlist.m3u8');

                $videoFile = $this->smartyApi->createVideoFile('Фильм', $createdVideoResponse['id'], ['filename' => $result[0], 'duration' => $duration]);
            }

            
        $response = [
            'message' => 'Запись добавлена',
        ];

        return $this->json($response);
    }
}
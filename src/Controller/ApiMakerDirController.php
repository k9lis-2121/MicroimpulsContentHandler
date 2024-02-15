<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TasksDirRepository;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Service\Api\JsonSendApi;
use App\Service\Utils\RequestDataProcessor;
use App\Message\MakeFullDirMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\SmartyCreatorMessage;
use App\Message\SmartyContentCreatorMessage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use App\Service\FileInfoLoaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Service\Api\External\Kinopoisk\SaveLocalFilmService;
use App\Service\DiskHandler\CheckFreeSizeService;
use App\Service\Utils\GetGlobalOptionService;
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

    private $getContentInfo;
    private $client;
    private $transcodeEndpoint;
    private $jsonSend;
    private $requestProcessor;
    private $saverKpData;
    /**
     * В конструктор загружаются вспомогательные сервисы
     *
     * @param GetContentInfoService $getContentInfo
     */
    public function __construct(
        GetContentInfoService $getContentInfo,
        private MessageBusInterface $bus,
        HttpClientInterface $client,
        GetGlobalOptionService $getGlobalOptionService,
        JsonSendApi $jsonSend,
        RequestDataProcessor $requestProcessor,
        SaveLocalFilmService $saveLocal
    ) {
        $this->getContentInfo = $getContentInfo;
        $this->client = $client;
        $this->transcodeEndpoint = $getGlobalOptionService->getTranscodeEndPoint();
        $this->jsonSend = $jsonSend;
        $this->requestProcessor = $requestProcessor;
        $this->saverKpData = $saveLocal;
    }

    /**
     * Огромный контроллер монстр, который я не знаю как оптимизировать
     *
     * @param Request $request
     * @param VodDirTemplateRepository $vodDirTemplateRepository
     * @param DirMakerService $dirMakerService
     * @return Response
     */

    #[Route('api/maker/directory', name: 'app_api_maker_directory', methods: ['POST'])]
    public function makeDirectory(Request $request, FileInfoLoaderService $fileInfoLoaderService, CheckFreeSizeService $checkFreeSizeService)
    {



        $requestData = $this->requestProcessor->processRequestData($request);
        $file = $requestData['file'];
        $data = $requestData['data'];


        if ($data['selectedDisk'] < 1) {
            $data['selectedDisk'] = $checkFreeSizeService->getFreeDisk();
        }
        $file = $request->files->get('file');

        if ($file) {
            $filePath = $file->getPathname();
            $jsonData = file_get_contents($filePath);
            $fileData = json_decode($jsonData, true);
            $fileInfoLoaderService->addContentData($fileData);
        }


        if ($data['createDirectory'] == 'yes') {
            $this->bus->dispatch(new MakeFullDirMessage($data));
        }

        if ($data['isSerial']) {
            $type = 'Season';
        } else {
            $type = 'Movie';
        }

        $contentDirectory = '/HDD' . '/' . $data['selectedDisk'] . '/VOD/content/' . $type . '/' . $data['kinopoiskId'] . '|' . $data['title'];

        $response = [
            'message' => true,
            'messageTitle' => $data['kinopoiskId'],
            'messageBody' => 'Задача поставлена в очередь',
            'contentDirectory' => $contentDirectory,
        ];

        return $this->json($response);
    }


    #[Route('/api/maker/smarty/content', name: 'app_api_maker_smarty_content', methods: ['POST'])]
    public function makeSmartyContent(Request $request): Response
    {
        
        $requestData = $this->requestProcessor->processRequestData($request);
        $data = $requestData['data'];
        
        $this->saverKpData->saveLocal($data['kinopoiskId']);

        $kinopoiskData = $this->saverKpData->loadLocal($data['kinopoiskId']);
        $this->bus->dispatch(new SmartyContentCreatorMessage($kinopoiskData, $data['selectedDisk']));

        $response = [
            'message' => true,
            'messageTitle' => $data['kinopoiskId'],
            'messageBody' => 'Задача поставлена в очередь',
            'contentDirectory' => '',
        ];

        return $this->json($response);
    }


    // #[Route('/api/maker/smarty/asset', name: 'app_api_maker_smarty_asset', methods: ['POST'])]
    // public function makeSmartyAsset(Request $request): Response
    // {
    //     $requestData = $this->requestProcessor->processRequestData($request);
    //     $data = $requestData['data'];

    //     $kinopoiskData = $this->saverKpData->loadLocal($data['kinopoiskId']);
    //     $this->bus->dispatch(new SmartyContentCreatorMessage($kinopoiskData, $data['selectedDisk']));

    //     $response = [
    //         'message' => true,
    //         'messageTitle' => $data['kinopoiskId'],
    //         'messageBody' => 'Задача поставлена в очередь',
    //         'contentDirectory' => '',
    //     ];

    //     return $this->json($response);
    // }

    #[Route('/api/maker/dir', name: 'app_api_maker_dir', methods: ['POST'])]
    public function index(Request $request, TasksDirRepository $taskDirRepository, EntityManagerInterface $entityManager, FileInfoLoaderService $fileInfoLoaderService, CheckFreeSizeService $checkFreeSizeService): Response
    {



        $requestData = $this->requestProcessor->processRequestData($request);
        $file = $requestData['file'];
        $data = $requestData['data'];


        if ($data['selectedDisk'] < 1) {
            $data['selectedDisk'] = $checkFreeSizeService->getFreeDisk();
        }





        if ($data['transcode']) {

            $user = $this->getUser();
            $data['user'] = $user->getUsername();
            dump($this->transcodeEndpoint);
            $response = $this->jsonSend->sendPostRequest($this->transcodeEndpoint, $data);


            return $this->json($response);
        }

        $file = $request->files->get('file');

        if ($file) {
            $filePath = $file->getPathname();
            $jsonData = file_get_contents($filePath);
            $fileData = json_decode($jsonData, true);
            $fileInfoLoaderService->addContentData($fileData);
        }


        if ($data['createDirectory'] == 'yes') {
            $this->bus->dispatch(new MakeFullDirMessage($data));
        }


        if ($data['uploadToSmarty'] == 'yes' and $data['createDirectory'] == 'yes') {
            $this->saverKpData->saveLocal($data['kinopoiskId']);
            while (true) {
                $taskDir = $taskDirRepository->findOneBy(['title' => $data['title']]);
                if ($taskDir) {
                    $dirStatus = $taskDir->getStatus();
                    if ($dirStatus == 'завершена') {
                        $result = $taskDir->getResults();
                        $kinopoiskData = $this->saverKpData->loadLocal($data['kinopoiskId']);
                        $this->bus->dispatch(new SmartyCreatorMessage($data, $kinopoiskData, $result, $data['selectedDisk']));
                        break; // выход из цикла
                    }
                }

                sleep(5);
                // Можно также добавить логику для определения максимального количества попыток перед прекращением цикла
            }
        }




        // if ($data['uploadToSmarty'] == 'yes') {
        //     sleep(5);
        //     for ($i = 0; $i < 100;) {
        //         $taskDir = $taskDirRepository->findOneBy(['title' => $data['title']]);
        //         if ($taskDir == null) {
        //             $entityManager->refresh($taskDir);
        //             $i++;
        //         } else {
        //             $dirStatus = $taskDir->getStatus();
        //             if ($dirStatus == 'завершена') {
        //                 $i = 100;
        //                 $result = $taskDir->getResults();
        //                 $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
        //                 $kinopoiskData = json_decode($kpresponse->getContent(), true);
        //                 $this->bus->dispatch(new SmartyCreatorMessage($data, $kinopoiskData, $result, $data['selectedDisk']));
        //             }
        //         }
        //     }
        // }





        if ($data['isSerial']) {
            $contentDirectory = '/HDD' . '/' . $data['selectedDisk'] . '/VOD/content/Season' . '/' . $data['kinopoiskId'] . '|' . $data['title'];
        } else {
            $contentDirectory = '/HDD' . '/' . $data['selectedDisk'] . '/VOD/content/Movie' . '/' . $data['kinopoiskId'] . '|' . $data['title'];
        }

        if ($data['createDirectory'] == 'no') {
            $contentDirectory = '';
        }







        $response = [
            'message' => true,
            'messageTitle' => $data['kinopoiskId'],
            'messageBody' => 'Задача поставлена в очередь',
            'contentDirectory' => $contentDirectory,
        ];

        return $this->json($response);
    }
}

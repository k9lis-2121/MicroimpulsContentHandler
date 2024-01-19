<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TasksDirRepository;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
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

    private $getContentInfo;

    /**
     * В конструктор загружаются вспомогательные сервисы
     *
     * @param GetContentInfoService $getContentInfo
     */
    public function __construct(
        GetContentInfoService $getContentInfo,
        private MessageBusInterface $bus,
    ) {
        $this->getContentInfo = $getContentInfo;
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
    public function index(Request $request, TasksDirRepository $taskDirRepository, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->get('data'), true);
        $this->bus->dispatch(new MakeFullDirMessage($data));

        sleep(5);
        for ($i = 0; $i < 100;) {
            $taskDir = $taskDirRepository->findOneBy(['title' => $data['title']]);
            if ($taskDir == null) {
                $entityManager->refresh($taskDir);
                dump('null');
                $i++;
            } else {
                $dirStatus = $taskDir->getStatus();
                if ($dirStatus == 'завершена') {
                    $i = 100;

                    $result = $taskDir->getResults();
                    $kpresponse = $this->getContentInfo->sendApiRequest($data['kinopoiskId']);
                    $kinopoiskData = json_decode($kpresponse->getContent(), true);

                    $this->bus->dispatch(new SmartyCreatorMessage($data, $kinopoiskData, $result));
                }
            }
        }
        $response = [
            'message' => 'Запись добавлена',
        ];

        return $this->json($response);
    }
}

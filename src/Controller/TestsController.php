<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ToastStatus;
use App\Entity\UserToastStatus;
use App\Repository\ToastStatusRepository;
use App\Repository\UserToastStatusRepository;
use App\Service\Api\External\Kinopoisk\KinopoiskProcessorService;
use App\Service\Api\External\Kinopoisk\GetContentInfoService;
use App\Repository\KplocalFilmsRepository;
use App\Repository\KplocalActorsRepository;
use App\Entity\KplocalFilms;
use App\Entity\KplocalAсtors;
use App\Service\Api\External\Kinopoisk\Pretraining\JsonToDtoService;
use App\DTO\KpDTO;
use App\Service\Api\External\Kinopoisk\Pretraining\PretrainingDataInDto;
use App\Service\Api\External\Kinopoisk\Pretraining\Cleaners\CleanHelperService;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Utils\GoHandlerService;
use App\Service\Api\External\Kinopoisk\TestLoadDtoInDbService;


class TestsController extends AbstractController
{
    #[Route('/tests', name: 'app_tests')]
    public function index(
        ToastStatusRepository $toastStatusRepository, 
        UserToastStatusRepository $userToastRepository, 
        KinopoiskProcessorService $kpProcessor, 
        EntityManagerInterface $entityManager, 
        GetContentInfoService $getContent, 
        KplocalFilmsRepository $kplocalFilms, 
        KplocalActorsRepository $kplocalAсtors,
        JsonToDtoService $jsonToDtoService,
        KpDTO $kpDTO,
        PretrainingDataInDto $pretrainingDataInDto,
        CleanHelperService $cleanHelperService,
        GoHandlerService $goHandlerService,
        TestLoadDtoInDbService $testLoadDtoInDbService,
    ): Response
    {
        // $kpId = '401152';
        $kpId = '231096';

        $response = $getContent->sendApiRequest($kpId);
        $jsonToDtoService->dataSendToDto($response);
        $pretrainingDataInDto->pretraining();
        dump($kpDTO);
        $testLoadDtoInDbService->saveFilmDtoInBase();


        // $res = $goHandlerService->getClearUnicodeString('Hümeyra');
        // dump($res);

        // $res = $kplocalFilms->findOneBy(['kpId'=> $kpId]);
        // dump ($res);



        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
        ]);
    }
}

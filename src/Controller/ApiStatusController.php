<?php

namespace App\Controller;

use App\Entity\ToastStatus;
use App\Repository\ToastStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiStatusController extends AbstractController
{
    #[Route('/api/status', name: 'app_api_status')]
    public function index(ToastStatusRepository $toastStatusRepository, EntityManagerInterface $entityManager): Response
    {
        // Получаем наиболее свежую запись ToastStatus с viewed = 0
        $toastStatus = $toastStatusRepository->findLatestUnviewedToastStatus();
        
        if ($toastStatus) {
            // Обновляем значение viewed на 1
            $toastStatus->setViewed(true);
            $entityManager->flush();
            
            // Формируем массив $response
            $response = [
                'message' => true,
                'messageTitle' => $toastStatus->getTitle(),
                'messageBody' => $toastStatus->getBody()
            ];
        } else {
            $response = [
                'message' => false,
                'messageTitle' => null,
                'messageBody' => null
            ];
        }
        
        return $this->json($response);
    }
}
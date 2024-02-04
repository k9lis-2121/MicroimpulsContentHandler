<?php

namespace App\Controller;

use App\Entity\ToastStatus;
use App\Entity\UserToastStatus;
use App\Repository\ToastStatusRepository;
use App\Repository\UserToastStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiStatusController extends AbstractController
{





    #[Route('/api/status', name: 'app_api_status')]
    public function index(ToastStatusRepository $toastStatusRepository, UserToastStatusRepository $userToastRepository, EntityManagerInterface $entityManager): Response
    {

        $response = [
            'message' => false,
            'messageTtitle' => null,
            'messageBody' => null,
        ];
        $result = false;

        //Получаем все уведомления
        $toasts = $toastStatusRepository->findAll();

        //получаем id пользователя
        $user = $this->getUser();
        $userId = $user->getId();

        if ($toasts != null) {
            $result = true;

            //Проходим каждое уведомление в цикле (ВНИМАНИЕ, НАДО ПОДУМАТЬ ЧЕ ДЕЛАТЬ СО СКРИНШОТАМИ)
            foreach ($toasts as $toast) {
                $toastId = $toast->getId();
                $toastTitle = $toast->getTitle();
                $toastBody = $toast->getBody();
                $toastKpId = $toast->getKpId();
                $toastComponent = $toast->getComponent();

                //Ищем соответствие между пользователем и уведомлением
                $userToastStatus = new UserToastStatus;
                $toastStatus = new ToastStatus;
                $userStatus = $userToastRepository->findOneBy(['toast_id' => $toastId, 'user_id' => $userId]);

                //Если записи об уведомлении пользователя нет, то...
                if ($userStatus == null) {

                    //Проверяем что это не скриншотер и создаем запись в таблицу пользовательских статусов
                    if ($toastComponent != 'WorkerThumbnailExtractor') {

                        $userToastStatus->setUserId($userId);
                        $userToastStatus->setToastId($toastId);
                        $userToastStatus->setViewed(0);

                        $entityManager->persist($userToastStatus);
                        $entityManager->flush();

                        //и сразу отображаем уведомление
                        $response = [
                            'message' => $result,
                            'messageTitle' => $toastTitle,
                            'messageBody' => $toastBody,
                        ];
                        break;


                        //Если это скриншотер и кинопоиск ид указан, то...
                    } else {

                        $screenStatus = $toastStatusRepository->findBy(['component' => 'WorkerThumbnailExtractor', 'kp_id' => $toastKpId]);
                        $userStatus = $userToastRepository->findOneBy(['toast_id' => $toastId, 'user_id' => $userId]);
                        dump($toastId);
                        if ($screenStatus != null and $userStatus == null) {
                            foreach ($screenStatus as $screen) {
                                if ($screen->getBody() == 'Создание скриншота, завершено') {
                                    $statusLocalId[] = $screen->getId();
                                    $res = true;
                                } else {
                                    unset($statusLocalId);
                                    $res = false;
                                    break;
                                }
                            }
                            if ($res) {
                                foreach ($statusLocalId as $statId) {
                                    $userToastStatus = new UserToastStatus;
                                    $userToastStatus->setUserId($userId);
                                    $userToastStatus->setToastId($statId);
                                    $userToastStatus->setViewed(0);

                                    $entityManager->persist($userToastStatus);
                                    $entityManager->flush();
                                }

                                $response = [
                                    'message' => true,
                                    'messageTitle' => 'ScreenMaker',
                                    'messageBody' => 'Создание всех скриншотов завершено!',
                                ];
                                return $this->json($response);
                            }
                        }
                    }
                }
            }
        }






        return $this->json($response);
    }
}

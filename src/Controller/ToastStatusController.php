<?php

namespace App\Controller;

use App\Entity\ToastStatus;
use App\Form\ToastStatusType;
use App\Repository\ToastStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/toast/status')]
class ToastStatusController extends AbstractController
{
    #[Route('/', name: 'app_toast_status_index', methods: ['GET'])]
    public function index(ToastStatusRepository $toastStatusRepository): Response
    {
        return $this->render('toast_status/index.html.twig', [
            'toast_statuses' => $toastStatusRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_toast_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $toastStatus = new ToastStatus();
        $form = $this->createForm(ToastStatusType::class, $toastStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($toastStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_toast_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('toast_status/new.html.twig', [
            'toast_status' => $toastStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_toast_status_show', methods: ['GET'])]
    public function show(ToastStatus $toastStatus): Response
    {
        return $this->render('toast_status/show.html.twig', [
            'toast_status' => $toastStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_toast_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ToastStatus $toastStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ToastStatusType::class, $toastStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_toast_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('toast_status/edit.html.twig', [
            'toast_status' => $toastStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_toast_status_delete', methods: ['POST'])]
    public function delete(Request $request, ToastStatus $toastStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toastStatus->getId(), $request->request->get('_token'))) {
            $entityManager->remove($toastStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_toast_status_index', [], Response::HTTP_SEE_OTHER);
    }
}

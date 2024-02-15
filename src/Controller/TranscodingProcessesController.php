<?php

namespace App\Controller;

use App\Entity\TranscodingProcesses;
use App\Form\TranscodingProcessesType;
use App\Repository\TranscodingProcessesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transcoding/processes')]
class TranscodingProcessesController extends AbstractController
{
    #[Route('/', name: 'app_transcoding_processes_index', methods: ['GET'])]
    public function index(TranscodingProcessesRepository $transcodingProcessesRepository): Response
    {
        return $this->render('transcoding_processes/index.html.twig', [
            'transcoding_processes' => $transcodingProcessesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transcoding_processes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transcodingProcess = new TranscodingProcesses();
        $form = $this->createForm(TranscodingProcessesType::class, $transcodingProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transcodingProcess);
            $entityManager->flush();

            return $this->redirectToRoute('app_transcoding_processes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transcoding_processes/new.html.twig', [
            'transcoding_process' => $transcodingProcess,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transcoding_processes_show', methods: ['GET'])]
    public function show(TranscodingProcesses $transcodingProcess): Response
    {
        return $this->render('transcoding_processes/show.html.twig', [
            'transcoding_process' => $transcodingProcess,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transcoding_processes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TranscodingProcesses $transcodingProcess, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TranscodingProcessesType::class, $transcodingProcess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transcoding_processes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transcoding_processes/edit.html.twig', [
            'transcoding_process' => $transcodingProcess,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transcoding_processes_delete', methods: ['POST'])]
    public function delete(Request $request, TranscodingProcesses $transcodingProcess, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transcodingProcess->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transcodingProcess);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transcoding_processes_index', [], Response::HTTP_SEE_OTHER);
    }
}

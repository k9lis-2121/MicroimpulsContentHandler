<?php

namespace App\Controller;

use App\Entity\VOD;
use App\Form\VODType;
use App\Form\VOD2Type;
use App\Form\VodCustomType;
use App\Repository\VODRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vod')]
class VODController extends AbstractController
{
    #[Route('/', name: 'app_vod_index', methods: ['GET'])]
    public function index(VODRepository $vODRepository): Response
    {
        return $this->render('vod/index.html.twig', [
            'vods' => $vODRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vod_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vod= new VOD();
        $form = $this->createForm(VODType::class, $vod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vod);
            $entityManager->flush();

            return $this->redirectToRoute('app_vod_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vod/new.html.twig', [
            'vod' => $vod,
            'form' => $form,
        ]);
    }

    #[Route('/new2', name: 'app_vod_new2', methods: ['GET', 'POST'])]
    public function new2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vod= new VOD();
        $form = $this->createForm(VodCustomType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $content = $form->getData();
            dd($content);


            if($content['moderation']){
                $content['moderation'] = 1;
            }else{
                $content['moderation'] = 0;
            }

            if($content['transcoded'] == 'ready'){
                $content['transcoded'] = 1;
            }else{
                $content['transcoded'] = 0;
            }


            if($content['content_type']){
                $content['content_type'] = 1;                
                $season_count = $content['season_count'];

                for($i=0; $i < $season_count; $i++){
                    $vod->setKpId($content['kp_id']);
                    $vod->setContentType($content['content_type']);
                    $vod->setContentName($content['content_name']);
                    $vod->setTorrentLink($content['torrent_link']);
                    $vod->setTranscoded($content['transcoded']);
                    $vod->setModeration($content['moderation']);
                    $vod->setDescription($content['description']);

                    $entityManager->persist($vod);
                    $entityManager->flush();
                }

            }else{
                $content['content_type'] = 0;
                $vod->setKpId($content['kp_id']);
                    $vod->setContentType($content['content_type']);
                    $vod->setContentName($content['content_name']);
                    $vod->setTorrentLink($content['torrent_link']);
                    $vod->setTranscoded($content['transcoded']);
                    $vod->setModeration($content['moderation']);
                    $vod->setDescription($content['description']);

                    $entityManager->persist($vod);
                    $entityManager->flush();
            }


            return $this->redirectToRoute('app_vod_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vod/new2.html.twig', [
            'vod' => $vod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vod_show', methods: ['GET'])]
    public function show(VOD $vod): Response
    {
        return $this->render('vod/show.html.twig', [
            'vod' => $vod,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vod_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VOD $vod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VODType::class, $vod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vod_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vod/edit.html.twig', [
            'vod' => $vod,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vod_delete', methods: ['POST'])]
    public function delete(Request $request, VOD $vod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vod->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vod_index', [], Response::HTTP_SEE_OTHER);
    }
}

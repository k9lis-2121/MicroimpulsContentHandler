<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Dubs;
use App\Entity\OtherDescription;
use App\Entity\Ratios;
use App\Entity\Resolutions;
use App\Entity\Subs;
use App\Entity\Torrents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ContentController extends AbstractController
{
    
    #[Route('/content', name: 'app_content', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $contents = $entityManager->getRepository(Content::class)->findAll();
        foreach($contents as $content){

            $contentArr[$content->getId()]['id'] = $content->getId();
            $contentArr[$content->getId()]['kpId'] = $content->getKpId();
            $contentArr[$content->getId()]['seasonCount'] = $content->getSesonCount();
            $contentArr[$content->getId()]['allEpisodeCount'] = $content->getAllEpisodeCount();


            $contentArr[$content->getId()]['dubs'] = $entityManager->getRepository(Dubs::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['otherDescription'] = $entityManager->getRepository(OtherDescription::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['ratios'] = $entityManager->getRepository(Ratios::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['resolutions'] = $entityManager->getRepository(Resolutions::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['subs'] = $entityManager->getRepository(Subs::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['torrents'] = $entityManager->getRepository(Torrents::class)->findBy(['content_id' => $content->getId()]);
        }

        dump($contentArr);

        return $this->render('content/index.html.twig', [
            'contents' => $contentArr,
        ]);
    }


    #[Route('/content/{id}/show', name: 'app_content_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $content = $entityManager->getRepository(Content::class)->findOneBy(['id' => $id]);

            $contentArr[$content->getId()]['id'] = $content->getId();
            $contentArr[$content->getId()]['kpId'] = $content->getKpId();
            $contentArr[$content->getId()]['seasonCount'] = $content->getSesonCount();
            $contentArr[$content->getId()]['allEpisodeCount'] = $content->getAllEpisodeCount();


            $contentArr[$content->getId()]['dubs'] = $entityManager->getRepository(Dubs::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['otherDescription'] = $entityManager->getRepository(OtherDescription::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['ratios'] = $entityManager->getRepository(Ratios::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['resolutions'] = $entityManager->getRepository(Resolutions::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['subs'] = $entityManager->getRepository(Subs::class)->findBy(['content_id' => $content->getId()]);
            $contentArr[$content->getId()]['torrents'] = $entityManager->getRepository(Torrents::class)->findBy(['content_id' => $content->getId()]);
        

        dump($contentArr);

        return $this->render('content/show.html.twig', [
            'contents' => $contentArr,
        ]);
    }

    #[Route('/content/{id}/edit', name: 'app_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Content $content, EntityManagerInterface $entityManager): Response
    {

        $dubs = $entityManager->getRepository(Dubs::class)->findBy(['content_id' => $content->getId()]);
        $otherDescription = $entityManager->getRepository(OtherDescription::class)->findBy(['content_id' => $content->getId()]);
        $ratios = $entityManager->getRepository(Ratios::class)->findBy(['content_id' => $content->getId()]);
        $resolutions = $entityManager->getRepository(Resolutions::class)->findBy(['content_id' => $content->getId()]);
        $subs = $entityManager->getRepository(Subs::class)->findBy(['content_id' => $content->getId()]);
        $torrents = $entityManager->getRepository(Torrents::class)->findBy(['content_id' => $content->getId()]);

        if ($request->isMethod('POST')) {
            // Обработка и сохранение данных из формы
            // ...

            $entityManager->flush();

            return $this->redirectToRoute('app_content');
        }

        return $this->render('content/edit.html.twig', [
            'content' => $content,
            'dubs' => $dubs,
            'otherDescription' => $otherDescription,
            'ratios' => $ratios,
            'resolutions' => $resolutions,
            'subs' => $subs,
            'torrents' => $torrents,
        ]);
    }

    #[Route('/content/{id}', name: 'app_content_delete', methods: ['DELETE'])]
    public function delete(Request $request, Content $content, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($content);
        $entityManager->flush();

        return $this->redirectToRoute('content_index');
    }
}
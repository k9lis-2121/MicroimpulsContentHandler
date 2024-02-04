<?php

namespace App\Controller;

use App\Entity\VodDirTemplate;
use App\Form\VodDirTemplateType;
use App\Repository\VodDirTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vod/dir/template')]
class VodDirTemplateController extends AbstractController
{
    #[Route('/', name: 'app_vod_dir_template_index', methods: ['GET'])]
    public function index(VodDirTemplateRepository $vodDirTemplateRepository): Response
    {
        

        $all = $vodDirTemplateRepository->findAll();
        foreach($all as $string){
            $tmp = $string->getTemplate();
            $demoData = [
                'base_dir' => 'test_090124',
                'kp_id' => '555443',
                'type' => 1,
                'name' => 'Dexter',
                'smarty_status' => '1',
                'season' => 1,
                'episode_count' => '3',
                'episode' => 1
            ];
            $parsedTemplate = strtr('{base_dir}/'.$tmp, $demoData);
            $parsedTemplate = str_replace('{', '', $parsedTemplate);
            $parsedTemplate = str_replace('}', '', $parsedTemplate);
            dump($parsedTemplate);
            $dirs = explode('/', $parsedTemplate);
            dump($dirs);
        }

        return $this->render('vod_dir_template/index.html.twig', [
            'vod_dir_templates' => $all,
        ]);
    }

    #[Route('/new', name: 'app_vod_dir_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vodDirTemplate = new VodDirTemplate();
        $form = $this->createForm(VodDirTemplateType::class, $vodDirTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vodDirTemplate);
            $entityManager->flush();

            return $this->redirectToRoute('app_vod_dir_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vod_dir_template/new.html.twig', [
            'vod_dir_template' => $vodDirTemplate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vod_dir_template_show', methods: ['GET'])]
    public function show(VodDirTemplate $vodDirTemplate): Response
    {
        return $this->render('vod_dir_template/show.html.twig', [
            'vod_dir_template' => $vodDirTemplate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vod_dir_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VodDirTemplate $vodDirTemplate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VodDirTemplateType::class, $vodDirTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vod_dir_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vod_dir_template/edit.html.twig', [
            'vod_dir_template' => $vodDirTemplate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vod_dir_template_delete', methods: ['POST'])]
    public function delete(Request $request, VodDirTemplate $vodDirTemplate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vodDirTemplate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vodDirTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vod_dir_template_index', [], Response::HTTP_SEE_OTHER);
    }
}

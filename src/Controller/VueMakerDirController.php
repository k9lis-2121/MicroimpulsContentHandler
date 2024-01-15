<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueMakerDirController extends AbstractController
{
    #[Route('/vue/maker/dir', name: 'app_vue_maker_dir')]
    public function index(): Response
    {
        return $this->render('vue_maker_dir/index.html.twig', [
            'controller_name' => 'VueMakerDirController',
        ]);
    }
}

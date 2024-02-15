<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueSearchController extends AbstractController
{
    #[Route('/vue/search', name: 'app_vue_search')]
    public function index(): Response
    {
        return $this->render('vue_search/index.html.twig', [
            'controller_name' => 'VueSearchController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DiskHandler\CheckFreeSizeService;

class VueMakerDirController extends AbstractController
{
    #[Route('/vue/maker/dir', name: 'app_vue_maker_dir')]
    #[Route('/', name: 'app_home')]
    public function index(CheckFreeSizeService $checkFreeSizeService): Response
    {
        $hddFree = $checkFreeSizeService->getAllDiskFreeSpace();
        return $this->render('vue_maker_dir/index.html.twig', [
            'controller_name' => 'Centra MovieMaker',
            'disks' => $hddFree,
        ]);
    }
}

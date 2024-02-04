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

        $hddArr = [1, 11, 14,15,20,21,3,5,8,9];

        foreach($hddArr as $hdd){
            $allInfo = $checkFreeSizeService->checkFreeSize($hdd);
            if($allInfo['bytes'] < 500000000000){
                $hddFree[] = "<b class='text-danger' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }elseif($allInfo['bytes'] < 1000000000000){
                $hddFree[] = "<b style='color: #a67e06;' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }elseif($allInfo['bytes'] > 1000000000000){
                $hddFree[] = "<b class='text-success' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }else{                
                $hddFree[] = "<b class='text-secondary' title='".$allInfo['bytes']." bytes'>".$allInfo['string']."</b>";
            }
            
        }
        return $this->render('vue_maker_dir/index.html.twig', [
            'controller_name' => 'Centra MovieMaker',
            'disks' => $hddFree,
        ]);
    }
}

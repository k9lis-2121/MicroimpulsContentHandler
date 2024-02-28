<?php

namespace App\Service\Api\External\Kinopoisk;


use App\Interface\Service\Api\External\Kinopoisk\ActorHelperInterface;
use App\Service\DbAssist\SmartyDbAssistService;
use App\Interface\Service\ImageHandler\ImageHandlerInterface;

class ActorHelperService implements ActorHelperInterface
{
    private $smartyDb;
    private $imgHandler;

    public function __construct(SmartyDbAssistService $smartyDb, ImageHandlerInterface $imgHandler) {
        $this->smartyDb = $smartyDb;
        $this->imgHandler = $imgHandler;
    }

    public function getIdActor(string $name, string $origName, string $img, int $kinopoiskId): ?int
    {
        $result = $this->smartyDb->searchActor($name, $origName);
        dump($result);
        if($result){
            return $result[0]['id'];
        }else{

            
            $this->imgHandler->imageConvert($img, 'default', 'jpg', 'png');
            dump('ACTOR IMAGE');
            $fname = uniqid('p_');
            $filename = $this->imgHandler->loadActorImageToSmarty($fname);
            dump($filename);
            return $this->smartyDb->addActor($name, $origName, 'upload/tvmiddleware/actors/'.$fname.'.png');
        }
    }

  
}
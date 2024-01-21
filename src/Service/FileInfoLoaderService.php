<?php

namespace App\Service;
use App\Entity\Content;
use App\Entity\Dubs;
use App\Entity\OtherDescription;
use App\Entity\Ratios;
use App\Entity\Resolutions;
use App\Entity\Subs;
use App\Entity\Torrents;
use Doctrine\ORM\EntityManagerInterface;

class FileInfoLoaderService
{
    private $entityContent;
    private $entityDubs;
    private $entityOtherDesc;
    private $entityRatios;
    private $entityResolutions;
    private $entitySubs;
    private $entitTorrents;
    public function __construct(EntityManagerInterface $entityManager, Content $content, Dubs $dubs, OtherDescription $otherDescription, Ratios $ratios, Resolutions $resolutions, Subs $subs, Torrents $torrents ){
        $this->entityContent = $content;
        $this->entityDubs = $dubs;
        $this->entityOtherDesc = $otherDescription;
        $this->entityRatios = $ratios;
        $this->entitySubs = $subs;
        $this->entityTorrents = $torrents;
        $this->entityResolutions = $resolutions;
        $this->entityManager = $entityManager;
    }

    private function addContent(int $kpId, int $sesonCount, int $allEpisodeCount){
        $this->entityContent->setKpId($kpId);
        $this->entityContent->setSesonCount( $sesonCount );
        $this->entityContent->setAllEpisodeCount($allEpisodeCount);
        $this->entityManager->persist($this->entityContent);
        $this->entityManager->flush();
        $contentId = $this->entityContent->getId();
        return $contentId;
    }

    private function addDubs(int $contentId, array $dubs){
        foreach($dubs as $dub){
            $dubsEntity = new Dubs();
            $dubsEntity->setContentId($contentId);
            $dubsEntity->setDub($dub);
            $this->entityManager->persist($dubsEntity);
        }
        $this->entityManager->flush();
    }
    
    private function addRatios(int $contentId, array $ratios){
        foreach($ratios as $ratio){
            $ratiosEntity = new Ratios();
            $ratiosEntity->setContentId($contentId);
            $ratiosEntity->setRatio($ratio);
            $this->entityManager->persist($ratiosEntity);
        }
        $this->entityManager->flush();
    }
    
    private function addSubs(int $contentId, array $subs){
        foreach($subs as $sub){
            $subsEntity = new Subs();
            $subsEntity->setContentId($contentId);
            $subsEntity->setSub($sub);
            $this->entityManager->persist($subsEntity);
        }
        $this->entityManager->flush();
    }
    
    private function addResolutions(int $contentId, array $resolutions){
        foreach($resolutions as $resolution){
            $resolutionsEntity = new Resolutions();
            $resolutionsEntity->setContentId($contentId);
            $resolutionsEntity->setResolution($resolution);
            $this->entityManager->persist($resolutionsEntity);
        }
        $this->entityManager->flush();
    }
    
    private function addTorrents(int $contentId, array $torrents){
        foreach($torrents as $torrent){
            $torrentsEntity = new Torrents();
            $torrentsEntity->setContentId($contentId);
            $torrentsEntity->setTorrent($torrent);
            $this->entityManager->persist($torrentsEntity);
        }
        $this->entityManager->flush();
    }

    private function addOtherDesc(int $contentId, string $desc){
        $this->entityOtherDesc->setContentId($contentId);
        $this->entityOtherDesc->setDescriptions($desc);
        $this->entityManager->persist($this->entityOtherDesc);
        $this->entityManager->flush();
    }

    

   

    public function addContentData(array $info){
        $contentId = $this->addContent($info['kp_id'], $info['sesonCount'], $info['allEpisodeCount']);
        $this->addDubs($contentId, $info['dub']);
        $this->addOtherDesc($contentId, $info['otherDescription']);
        $this->addRatios($contentId, $info['raito']);
        $this->addSubs($contentId, $info['sub']);
        $this->addResolutions($contentId, $info['resolutions']);
        $this->addTorrents($contentId, $info['torrents']);
    }
}

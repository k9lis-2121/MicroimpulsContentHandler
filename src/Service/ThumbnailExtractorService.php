<?php

namespace App\Service;

use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;
use App\Service\DbAssist\SmartyDbAssistService;
use App\Service\ContentDirHandler\DirMakerService;
use App\Service\FfmpegService;

class ThumbnailExtractorService
{
    
    private $smartyDb;
    private $dirMaker;
    private $ffmpeg;

    public function __construct(SmartyDbAssistService $smartyDb, DirMakerService $dirMaker, FfmpegService $ffmpeg){
        $this->smartyDb = $smartyDb;
        $this->dirMaker = $dirMaker;
        $this->ffmpeg = $ffmpeg;
    }

    private function findHighestResolution($directory): ?string
    {
        $finder = new Finder();
        $finder->directories()->in($directory);

        $resolutions = ['1080p', '720p', '480p', '320p'];

        $highestResolution = null;
        foreach ($resolutions as $resolution) {
            $directories = iterator_to_array($finder->name('/'.$resolution.'$/'));
            if (!empty($directories)) {
                $highestResolution = $resolution;
                break;
            }
        }

        return $highestResolution;
    }

    public function extractThumbnails(string $episode, $contentDir){
        $workDir = $this->findHighestResolution($contentDir);
        $dirScreen = '/mnt/adddata/panel_v3/public/img/mnt/smarty/tvmiddleware/video/episode/'.$episode;
        $this->dirMaker->makeScreenDir($dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_087.ts', 'screen1.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_088.ts', 'screen2.jpg', $dirScreen);
        $this->ffmpeg->extractThumbnail($contentDir.'/'.$workDir.'/'.$workDir.'_089.ts', 'screen3.jpg', $dirScreen);
        $sqlResult = $this->smartyDb->setScreenEpisode($episode);
        return $sqlResult;
    }

}
<?php

namespace App\Service\Api\Inside;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\VodDirTemplateRepository;
use App\Service\ContentDirHandler\DirMakerService;

/**
 * Класс управляет директориями контента
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array makeFullDir()
 * @version 1.0.0
 */
class MakeContentDirService
{


    private $vodDirTemplateRepository;
    private $dirMakerService;

    public function __construct(VodDirTemplateRepository $vodDirTemplateRepository, DirMakerService $dirMakerService){
        $this->vodDirTemplateRepository = $vodDirTemplateRepository;
        $this->dirMakerService = $dirMakerService;
    }


    /**
     * Сервис создает все директории для контента и возвращает массив с директориями для создания ассетов
     *
     * @param request $request
     * @param array $data
     * @return array
     */
    public function makeFullDir(request $request, array $data): array
    {

        $movie = $this->vodDirTemplateRepository->findOneBy(['title' => 'Фильм']);
        $tmpMovie = $movie->getTemplate();
        $season = $this->vodDirTemplateRepository->findOneBy(['title' => 'Сериал']);
        $tmpSeason = $season->getTemplate();
        $template = [
            'season' => $tmpSeason,
            'movie' => $tmpMovie
        ];



        $baseDir = $this->dirMakerService->makeBaseDir($data, $template);
        if ($request->files->get('file')) {
            $this->dirMakerService->infoFileLoader($request->files->get('file'), $baseDir['dir']);
        }

        if ($data['isTrailler']) {
            $trailerDir = $this->dirMakerService->makeTraillerDir($data, $tmpMovie . '/trailer');
        }

        if ($data['seasonCount'] or $data['sameEpisodesCount']) {
            $seasonDir = $this->dirMakerService->dirCreateSE($data, $template);
            dump($seasonDir);
        }

        if ($data['isTrailler']) {
            $result[] = str_replace('/VOD' . '/', '', $trailerDir['dir']);
        } elseif ($data['isSerial']) {
            foreach ($seasonDir as $arr) {
                foreach ($arr as $key => $value) {
                    if ($key == 'dir') {
                        $result['dir'][] = str_replace('/VOD' . '/', '', $value);
                    }
                }
            }
        } else {
            $result[] = str_replace('/VOD' . '/', '', $baseDir['dir']);
        }

        return $result;

    }
}
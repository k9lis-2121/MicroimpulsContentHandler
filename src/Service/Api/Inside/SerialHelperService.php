<?php

namespace App\Service\Api\Inside;

use App\Service\Api\External\Smarty\SmartyContentApiService;
use App\Service\ThumbnailExtractorService;
use App\Service\FfmpegService;

/**
 * Класс управляет созданием сезнов, эпизодов и ассетов в смарти
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method bool makeSeasonAndEpisodeInSmarty()
 * @version 1.0.0
 */
class SerialHelperService
{

    private $smartyApi;
    private $thumbnailExtractor;
    private $ffmpeg;

    public function __construct(
        SmartyContentApiService $smartyApi,
        ThumbnailExtractorService $thumbnailExtractor,
        FfmpegService $ffmpeg,
    )
    {
        $this->smartyApi = $smartyApi;
        $this->thumbnailExtractor = $thumbnailExtractor;
        $this->ffmpeg = $ffmpeg;
    }

    /**
     * Приведение чисел к формату 01, 02, 03 и т.д.
     *
     * @param integer $number
     * @return integer
     */
    private function numStandardizer(int $number): int
    {
        if ($number < 10) {
            $numStandardizeded = '0' . $number;
        } else {
            $numStandardizeded = $number;
        }

        return (int) $numStandardizeded;
    }

    /**
     * Метод создает сезоны в смарти
     *
     * @param array $data
     * @param int $createdVideoResponse
     * @return array
     */
    private function createSeasons(array $data, int $createdVideoResponse): array
    {
        $seasons = [];

        foreach ($data['episodesCount'] as $season => $episodes) {
            $seasonNum = $this->numStandardizer($season);
            $smartySeason = $this->smartyApi->createSeason('Сезон ' . $seasonNum, $createdVideoResponse);
            $seasons[$season] = ['seasonNum' => $seasonNum, 'smartySeason' => $smartySeason];
        }

        return $seasons;
    }

    /**
     * Метод создает эпизоды в смарти
     *
     * @param array $data
     * @param array $seasons
     * @param integer $createdVideoResponse
     * @param array $result
     * @return array
     */
    private function createEpisodes(array $data, array $seasons, int $createdVideoResponse, array $result): array
    {
        $resultCount = 0;
    
        foreach ($seasons as $season => $seasonData) {
            $episodesCount = $data['episodesCount'][$season];
    
            for ($i = 1; $i <= $episodesCount; $i++) {
                $episodeNum = $this->numStandardizer($i);
                $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result['dir'][$resultCount] . '/playlist.m3u8');
                $smartyEpisode = $this->smartyApi->createEpisode(
                    $createdVideoResponse,
                    'Серия ' . $episodeNum,
                    $seasonData['smartySeason']['id'],
                    ['duration' => $duration]
                );
    
                $contentDir = '/VOD' . '/' . $result['dir'][$resultCount];
                $this->thumbnailExtractor->extractThumbnails($smartyEpisode['id'], $contentDir);
                
                $episodeId[$season][$i] = $smartyEpisode['id'];

                $resultCount++;
            }
        }
        return $episodeId;
    }

    /**
     * Метод создает ассеты в смарти для сериалов
     *
     * @param array $data
     * @param array $seasons
     * @param array $episodeId
     * @param integer $createdVideoResponse
     * @param array $result
     * @return void
     */
    private function createVideoFiles(array $data, array $seasons, array $episodeId, int $createdVideoResponse, array $result): void
    {
        $resultCount = 0;
        foreach ($seasons as $season => $seasonData) {
            $episodesCount = $data['episodesCount'][$season];
            $seasonNum = $this->numStandardizer($seasonData['seasonNum']);
    
            for ($i = 1; $i <= $episodesCount; $i++) {
                $episodeNum = $this->numStandardizer($i);
                $duration = $this->ffmpeg->getVideoDuration('/VOD' . '/' . $result['dir'][$resultCount] . '/playlist.m3u8');
    
                $this->smartyApi->createVideoFile(
                    'Сезон ' . $seasonNum . ' Серия ' . $episodeNum,
                    $createdVideoResponse,
                    [
                        'filename' => $result['dir'][$resultCount],
                        'episode_id' => $episodeId[$season][$i],
                        'duration' => $duration
                    ]
                );
    
                $resultCount++;
            }
        }
    }
    
    /**
     * Метод для создания сезонов, серий и ассетов в смарти
     *
     * @param array $data
     * @param integer $createdVideoResponse
     * @param array $result
     * @return boolean
     */
    public function makeSeasonAndEpisodeInSmarty(array $data, int $createdVideoResponse, array $result): bool
    {
        $seasons = $this->createSeasons($data, $createdVideoResponse);
        $episodeId = $this->createEpisodes($data, $seasons, $createdVideoResponse, $result);
        $this->createVideoFiles($data, $seasons, $episodeId, $createdVideoResponse, $result);
    
        return true;
    }
}
<?php

namespace App\Service\Api\External\Smarty;

use Symfony\Component\HttpClient\HttpClient;
use App\Interface\Service\Api\External\Smarty\SmartyContentApiInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author microimpulse - оригинал на python
 * @author Валерий Ожерельев - перевод на php
 * @api внешний для смарти
 * @version 2.0.0
 */
class SmartyContentApiService implements SmartyContentApiInterface
{
    private $baseUrl;
    private $clientId;
    private $apiKey;

    public function __construct(
        #[Autowire('%env(SMARTY_API_URL)%')]     $baseUrl, 
        #[Autowire('%env(SMARTY_API_CID)%')]     $clientId, 
        #[Autowire('%env(SMARTY_API_KEY)%')]     $apiKey, 
    )
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->apiKey = $apiKey; 
    }

    private function getSignature(array $requestData): string
    {
        //Секретная часть кода
    }

    private function getFullUrl(string $path): string
    {
        
        //Секретная часть кода
    }

    private function apiRequest(string $path, array $data = []): array
    {
        $httpClient = HttpClient::create();
        $url = $this->getFullUrl($path);
        $data['client_id'] = $this->clientId;
        $data['signature'] = $this->getSignature($data);
        $response = $httpClient->request('POST', $url, [
            'body' => http_build_query($data),
        ])->getContent();
        $apiResponse = json_decode($response, true);
        if ($apiResponse['error']) {
            $errorMessage = sprintf("Api Error %s: %s", $apiResponse['error'], $apiResponse['error_message']);
            dump($errorMessage);
        }
        return $apiResponse;
    }

    private function setParams(array &$params, array $fields, array $kwargs): void
    {
        foreach ($kwargs as $key => $value) {
            if (in_array($key, $fields)) {
                $params[$key] = is_array($value) ? implode(',', $value) : $value;
            }
        }
        dump($params);
    }

    //VIDEO
    /**
    * @param string $name название фильма/сериала
    * @param string $rating Возрастной рейтинг
    * @param array $kwargs Дополнительные параметры: 
    *   [
    *        'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4', 'name_lang5', 'name_orig',
    *        'description', 'description_lang1', 'description_lang2', 'description_lang3',
    *        'description_lang4', 'description_lang5', 'year', 'countries', 'countries_lang1',
    *        'countries_lang2', 'countries_lang3', 'countries_lang4', 'countries_lang5',
    *        'director', 'director_lang1', 'director_lang2', 'director_lang3', 'director_lang4',
    *        'director_lang5', 'genres_kinopoisk', 'uri', 'language', 'language_lang1',
    *        'language_lang2', 'language_lang3', 'language_lang4', 'language_lang5', 'ext_id',
    *        'premiere_date', 'published_from', 'published_to', 'copyright_holder',
    *        'external_api_config', 'price_category', 'video_provider', 'genres', 'stream_services',
    *        'tariffs', 'actors_set', 'available_on', 'package_videos', 'kinopoisk_rating',
    *        'imdb_rating', 'average_customers_rating', 'duration', 'parent_control',
    *        'is_announcement'
    *   ]
    * @return array Возвращает id созданного фильма/сериала, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function createVideo(string $name, string $rating, array $kwargs = []): array
    {
        $params = [
            'name' => $name,
            'rating' => $rating,
        ];
        $fields = [
            'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4', 'name_lang5', 'name_orig',
            'description', 'description_lang1', 'description_lang2', 'description_lang3',
            'description_lang4', 'description_lang5', 'year', 'countries', 'countries_lang1',
            'countries_lang2', 'countries_lang3', 'countries_lang4', 'countries_lang5',
            'director', 'director_lang1', 'director_lang2', 'director_lang3', 'director_lang4',
            'director_lang5', 'genres_kinopoisk', 'uri', 'language', 'language_lang1',
            'language_lang2', 'language_lang3', 'language_lang4', 'language_lang5', 'ext_id',
            'premiere_date', 'published_from', 'published_to', 'copyright_holder',
            'external_api_config', 'price_category', 'video_provider', 'genres', 'stream_services',
            'tariffs', 'actors_set', 'available_on', 'package_videos', 'kinopoisk_rating',
            'imdb_rating', 'average_customers_rating', 'duration', 'parent_control',
            'is_announcement', 'is_season', 'poster_big'
        ];
        $this->setParams($params, $fields, array_map(function($v) { return is_array($v) ? $v : [$v]; }, $kwargs));
        return $this->apiRequest('/content/api/video/create/', $params);
    }

    /**
    * @param integer $id ID фильма/сериала в смарти
    * @param array $kwargs Дополнительные параметры
    *   [
    *        'load_meta', 'kinopoisk_id', 'name_lang1', 'name_lang2', 'name_lang3',
    *        'name_lang4', 'name_lang5', 'name_orig', 'director', 'director_lang1',
    *        'director_lang2', 'director_lang3', 'director_lang4', 'director_lang5',
    *        'countries', 'countries_lang1', 'countries_lang2', 'countries_lang3',
    *        'countries_lang4', 'countries_lang5', 'description', 'description_lang1',
    *        'description_lang2', 'description_lang3', 'description_lang4', 'description_lang5',
    *        'year', 'poster_url', 'screenshot_url', 'actors_set', 'genres_kinopoisk',
    *        'kinopoisk_rating', 'imdb_rating', 'rating', 'duration'
    *    ]
    * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function modifyVideo(int $id, array $kwargs = []):array {
        $params = array('id' => $id);
        $fields = [
            'load_meta', 'kinopoisk_id', 'name_lang1', 'name_lang2', 'name_lang3',
            'name_lang4', 'name_lang5', 'name_orig', 'director', 'director_lang1',
            'director_lang2', 'director_lang3', 'director_lang4', 'director_lang5',
            'countries', 'countries_lang1', 'countries_lang2', 'countries_lang3',
            'countries_lang4', 'countries_lang5', 'description', 'description_lang1',
            'description_lang2', 'description_lang3', 'description_lang4', 'description_lang5',
            'year', 'poster_url', 'screenshot_url', 'actors_set', 'genres_kinopoisk',
            'kinopoisk_rating', 'imdb_rating', 'rating', 'duration', 'genres',
        ];
        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/video/modify/', $params);
    }

    /**
    * @param integer $id ID фильма/сериала в смарти
    * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function deleteVideo(int $id):array {
        $params = array('id' => $id);
        return $this->apiRequest('/content/api/video/delete/', $params);
    }
    
    //ASSET / VIDEOFILE

    /**
    * @param string $name Имя ассета в смарти
    * @param integer $vid ID видеофайла в смарти
    * @param array $kwargs Дополнительные параметры:
    *   [
    *        'episode_id', 'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4',
    *        'name_lang5', 'filename', 'duration', 'is_trailer', 'ext_id', 'sort_after_vfid',
    *        'quality'
    *   ]
    * @return array Возвращает id созданного ассета, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function createVideoFile(string $name, int $vid, array $kwargs = []):array {
        $params = array(
            'name' => $name,
            'vid' => $vid
        );
        $fields = [
            'episode_id', 'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4',
            'name_lang5', 'filename', 'duration', 'is_trailer', 'ext_id', 'sort_after_vfid',
            'quality'
        ];
    
        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/video/file/create/', $params);
    }
    
    /** 
    * Модифицирует ассет
    * @param integer $id ID видеофайла в смарти
    * @param array $kwargs Дополнительные параметры:
    *   [
    *        'episode_id', 'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4',
    *        'name_lang5', 'filename', 'duration', 'is_trailer', 'ext_id', 'sort_after_vfid',
    *        'quality'
    *   ]
    * @return array Возвращает id созданного фильма/сериала, код ошибки, и текст ошибки (текст пустой если код = 0)
    */
    public function modifyVideoFile(int $id, array $kwargs = []):array {
        $params = array('id' => $id);
        $fields = [
            'name', 'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4',
            'name_lang5', 'filename', 'is_trailer', 'duration', 'episode_id', 'quality'
        ];
        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/video/file/modify/', $params);
    }

    //SEASON

    /**
     * Создает новый сезон
     * @param string $name Название сезона
     * @param int $vid Идентификатор сезона
     * @param array $kwargs Дополнительные параметры:
     *  [
     *       'name_lang1', 'name_lang2', 'name_lang3',
     *       'name_lang4', 'name_lang5', 'sort_after_sid'
     *  ]
     * @return array Возвращает id созданного сезона, код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function createSeason(string $name, int $vid, array $kwargs = []):array {
        $params = [
            'name' => $name,
            'vid' => $vid
        ];
        $fields = [
            'name_lang1', 'name_lang2', 'name_lang3',
            'name_lang4', 'name_lang5', 'sort_after_sid'
        ];

        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/season/create/', $params);
    }

    /**
     * Удаляет сезон по идентификатору
     * @param int $id Идентификатор сезона
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function deleteSeason(int $id):array {
        $params = ['id' => $id];
        return $this->apiRequest('/content/api/season/delete/', $params);
    }

    /**
     * Изменяет сезон по идентификатору
     * @param int $season_id Идентификатор сезона
     * @param array $kwargs Дополнительные параметры:
     *  [
     *       'name_lang1', 'name_lang2', 'name_lang3',
     *       'name_lang4', 'name_lang5', 'sort_after_sid'
     *  ]
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function modifySeason(int $season_id, array $kwargs = []):array {
        $params = ['season_id' => $season_id];
        $fields = [
            'name', 'name_lang1', 'name_lang2', 'name_lang3',
            'name_lang4', 'name_lang5', 'sort_after_sid'
        ];

        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/season/modify/', $params);
    }

    //EPISODE

    /**
     * Создает новую серию
     * @param int $vid Идентификатор серии
     * @param string $name Название серии
     * @param int $season_id Идентификатор сезона, к которому принадлежит серия
     * @param array $kwargs Дополнительные параметры:
     *  [
     *      'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4', 'name_lang5',
     *       'description', 'description_lang1', 'description_lang2', 'description_lang3',
     *       'description_lang4', 'description_lang5', 'duration',
     *       'sort_after_eid'
     *  ]
     * @return array Возвращает id созданной серии, код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function createEpisode(int $vid, string $name, int $season_id, array $kwargs = []):array {
        $params = [
            'vid' => $vid,
            'name' => $name,
            'season_id' => $season_id
        ];
        $fields = [
            'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4', 'name_lang5',
            'description', 'description_lang1', 'description_lang2', 'description_lang3',
            'description_lang4', 'description_lang5', 'duration',
            'sort_after_eid'
        ];

        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/episode/create/', $params);
    }

    /**
     * Удаляет серию по идентификатору
     * @param int $id Идентификатор серии
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function deleteEpisode(int $id):array {
        $params = ['id' => $id];
        return $this->apiRequest('/content/api/episode/delete/', $params);
    }

    /**
     * Изменяет серию по идентификатору
     * @param int $episode_id Идентификатор серии
     * @param array $kwargs Дополнительные параметры:
     *  [
     *      'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4', 'name_lang5',
     *       'description', 'description_lang1', 'description_lang2', 'description_lang3',
     *       'description_lang4', 'description_lang5', 'duration',
     *       'sort_after_eid'
     *  ]
     * @return array Возвращает код ошибки, и текст ошибки (текст пустой если код = 0)
     */
    public function modifyEpisode(int $episode_id, array $kwargs = []):array {
        $params = ['episode_id' => $episode_id];
        $fields = [
            'name', 'name_lang1', 'name_lang2', 'name_lang3', 'name_lang4',
            'name_lang5', 'description', 'description_lang1', 'description_lang2',
            'description_lang3', 'description_lang4', 'description_lang5', 'duration',
            'sort_after_eid'
        ];

        $this->setParams($params, $fields, $kwargs);
        return $this->apiRequest('/content/api/episode/modify/', $params);
    }

}
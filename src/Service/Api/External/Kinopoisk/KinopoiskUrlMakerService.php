<?php

namespace App\Service\Api\External\Kinopoisk;

use App\Interface\Service\Api\External\Kinopoisk\KinopoiskUrlMakerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
* @author Валерий Ожерельев
* @api Кинопоиск
* @method object makeUrl()
* @version 1.0.0
*/
class KinopoiskUrlMakerService implements KinopoiskUrlMakerInterface
{
    private $kp_token;
    private $kp_base_url;
    private $kp_version_api;

    public function __construct(
        #[Autowire('%env(KP_TOKEN)%')] $kp_token, 
        #[Autowire('%env(KP_BASE_URL)%')] $kp_base_url, 
        #[Autowire('%env(KP_VERSION_API)%')] $kp_version_api)
    {
        $this->kp_token = $kp_token;
        $this->kp_base_url = $kp_base_url;
        $this->kp_version_api = $kp_version_api;
    }

    /**
    * @param string $kpId ID кинопоиска, строка т.е. может иметь отрицание в виде "!"
    * @param string $colOptions Перечисление столбцов которые нужно получить в формате url (пробелы заменяются на %20) список столбцов можно посмотреть в документации к апи
    * @return array Возвращает массив содержащий сгенерированный url и тело запроса
    */
    public function makeUrl(string $kpId, string $colOptions): array
    {
        
        $request['url'] = $this->kp_base_url . $this->kp_version_api . '/movie/'.$kpId;
        $request['query_body'] = 
        [
            'headers' => [
                'X-API-KEY' => $this->kp_token,
                'accept' => 'application/json',
              ],
            'query' => [
                'selectFields' => $colOptions,
            ],
        ];

        return $request;
    }
}
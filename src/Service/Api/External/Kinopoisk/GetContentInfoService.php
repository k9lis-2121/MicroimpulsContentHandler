<?php

// namespace App\Service\Api\External\Kinopoisk;
namespace App\Service\Api\External\Kinopoisk;
use App\Service\Api\External\Kinopoisk\KinopoiskUrlMakerService;
use App\Service\Api\BaseApiService;
use App\Interface\Service\Api\External\Kinopoisk\KinopoiskUrlMakerInterface;
use App\Interface\Service\Api\External\Kinopoisk\GetContentInfoInterface;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method object sendApiRequest()
* @version 1.0.0
*/
class GetContentInfoService implements GetContentInfoInterface
{
    private $urlMaker;
    private $baseApi;

    public function __construct(KinopoiskUrlMakerService $urlMaker, BaseApiService $baseApi){

        $this->urlMaker = $urlMaker;
        $this->baseApi = $baseApi;

    }

    /**
    * @param string $kpId ID с кинописка стринг т.к. может принимать значения исключения в виде "!" 
    * @param string $colOptions Перечисление столбцов которые нужно получить в формате url (пробелы заменяются на %20) список столбцов можно посмотреть в документации к апи
    * @return object
    */
    public function sendApiRequest(string $kpId, string $colOptions = 'persons%20movieLength%20poster.url%20poster.previewUrl%20filmName%20name%20alternativeName%20enName%20rating.kp%20rating.imdb%20year%20description%20shortDescription%20slogan%20ageRating%20genres%20countries%20actor%20director'): object
    {

        $query = $this->urlMaker->makeUrl($kpId, $colOptions);
        $response = $this->baseApi->sendApiRequest('GET', $query['url'], $query['query_body']);

        return $response;
    }
}
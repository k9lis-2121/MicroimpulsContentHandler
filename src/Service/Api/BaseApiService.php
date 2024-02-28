<?php

namespace App\Service\Api;

use App\Interface\Service\Api\BaseApiInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

/**
* @author Валерий Ожерельев 
* @api базовый для отправки запросов
* @method response sendApiRequest()
* @version 1.0.0
*/
class BaseApiService implements BaseApiInterface
{
    /**
    * @param string $method Метод запроса GET, POST или другие
    * @param string $url Адрес куда делаем api запрос
    * @param array $parametrs Параметры которые нужно передать апи в виде массива
    * @return response Возвращает ответ сервера api (содержимое и код ответа)
    */
    public function sendApiRequest(string $method, string $url, array $parametrs): Response{

        $httpClient = HttpClient::create();
        $response = $httpClient->request($method, $url, $parametrs);

        $statusCode = $response->getStatusCode();
        $content = $response->getContent();

        return new Response($content, $statusCode);

    }
}
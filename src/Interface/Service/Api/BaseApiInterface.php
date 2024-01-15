<?php

namespace App\Interface\Service\Api;

use Symfony\Component\HttpFoundation\Response;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api базовый для отправки запросов
* @method response sendApiRequest()
* @version 1.0.0
*/
interface BaseApiInterface
{
    /**
    * @param string $method Метод запроса GET, POST или другие
    * @param string $url Адрес куда делаем api запрос
    * @param array $parametrs Параметры которые нужно передать апи в виде массива
    * @return response Возвращает ответ сервера api (содержимое и код ответа)
    */
    public function sendApiRequest(string $method, string $url, array $parametrs): Response;
}
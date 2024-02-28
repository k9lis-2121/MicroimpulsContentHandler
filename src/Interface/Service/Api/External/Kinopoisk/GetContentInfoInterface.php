<?php

namespace App\Interface\Service\Api\External\Kinopoisk;

/**
* @author Валерий Ожерельев 
* @api Кинопоиск
* @method object sendApiRequest()
* @version 1.0.0
*/
interface GetContentInfoInterface
{

    /**
    * @param string $kp_id ID кинопоиска, строка т.к. может иметь параметр отрицания "!"
    * @param string $colOptions Перечисление столбцов которые нужно получить в формате url (пробелы заменяются на %20) список столбцов можно посмотреть в документации к апи
    * @return object
    */
    public function sendApiRequest(string $kp_id, string $colOptions): object;
}
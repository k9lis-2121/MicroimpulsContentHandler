<?php

namespace App\Interface\Service\Api\External\Kinopoisk;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method object makeUrl()
* @version 1.0.0
*/
interface KinopoiskUrlMakerInterface
{
    /**
    * @param string $kpId ID кинопоиска, строка т.е. может иметь отрицание в виде "!"
    * @param string $colOptions Перечисление столбцов которые нужно получить в формате url (пробелы заменяются на %20) список столбцов можно посмотреть в документации к апи
    * @return array Возвращает массив содержащий сгенерированный url и тело запроса
    */
    public function makeUrl(string $kpId, string $colOptions);
}
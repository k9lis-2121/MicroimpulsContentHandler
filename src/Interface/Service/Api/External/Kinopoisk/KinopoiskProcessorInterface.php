<?php

namespace App\Interface\Service\Api\External\Kinopoisk;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method object dataProcessing()
* @version 1.0.0
*/
interface KinopoiskProcessorInterface
{

    /**
    * @param array json ответ от кинопоиска преобразованный в массив
    * @return array Обработанный массив с данными готовыми для записи
    */
    public function dataProcessing(array $data): array;
}
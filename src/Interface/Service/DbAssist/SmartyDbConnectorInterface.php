<?php

namespace App\Interface\Service\DbAssist;

/**
 * Класс для прямого подключения к базе смарти.
 * 
* @author Валерий Ожерельев
* @method void smartyDbQuery()
* @version 1.0.0
*/
interface SmartyDbConnectorInterface
{
    /**
     * Подключение и отправка запроса
     *
     * @param string $query
     */
    public function smartyDbQuery(string $query);

}
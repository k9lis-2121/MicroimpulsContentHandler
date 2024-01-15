<?php

namespace App\Interface\Service\DbAssist;

/**
 * Класс для прямого подключения к базе смарти.
 * 
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
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
<?php

namespace App\Interface\Service\Cleaner;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @method string CleanFull()
* @method string CleanName()
* @method string CleanDescription()
* @version 1.0.0
*/
interface StringCleanerInterface
{
    /**
     * Возвращает полностью очищенную строку
     * @param string|null $str строка которую нужно очистить
     * @return string Возвращает полностью очищенную строку
    */
    public function CleanFull(string|null $str): string;

    /**
    * @param string $str строка которую нужно очистить
    * @return string Возвращает очищеную строку, но допускает некоторые символы, например ":"
    */
    public function CleanName(string $str): string;

    /**
    * @param string|null $str строка которую нужно очистить
    * @return string Возвращает очищеную строку, но допускает некоторые символы, например ":", ",", "." и др.
    */
    public function CleanDescription(string|null $str): string;

}
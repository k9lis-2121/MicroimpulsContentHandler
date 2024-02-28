<?php

namespace App\Interface\Service\DbAssist;

/**
 * Класс для выполнения прямыз запросов к базе данных смарти, 
 * для автоматизированной загрузки данные для которых не существует api методов в смарти
 * 
* @author Валерий Ожерельев 
* @method void setVid()
* @method void setKinopoiskId()
* @method void setGenres()
* @method void setAdditionalTariffs()
* @version 2.0.0
*/
interface SmartyDbAssistInterface
{

    /**
     * Установка Video ID
     *
     * @param integer $vid
     * @return void
     */
    public function setVid(int $vid): void;

    /**
     * Метод для добавления кинопоиск id к фильму
     * @param int $vid ID видео в смарти
    * @param int $kinopoiskId ID с кинопоиска
    * @return void
    */
    public function setKinopoiskId(int $kinopoiskId): void;

    /**
     * Метод для добавления жанров видео
     *
     * @param integer $vid
     * @param array $genres
     * @return void
     */
    public function setGenres(array $genres): void;
    
    /**
     * Метод для добавления дополнительных тарифов на корторых доступен фильм
     *
     * @param integer $vid
     * @param array $tariffs
     * @return void
     */
    public function setAdditionalTariffs(array $tariffs): void;

    /**
     * Отправка запросов на внесение изменений в базу
     *
     * @return void
     */
    public function flashQuery(): void;
}
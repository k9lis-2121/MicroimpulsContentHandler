<?php

namespace App\Interface\Message;

/**
 * Interface Message SmartyCreator
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array getData()
 * @method array getKinopoiskData()
 * @method array getMakerDirResult()
 * @version 1.0.0
 */
interface SmartyCreatorMessageInterface
{
    /**
     * Получить все данные оптравленные воркеру
     *
     * 
     */
    public function getData();

    /**
     * Получить данные из кинопоиска
     *
     * 
     */
    public function getKinopoiskData();

    /**
     * Получить список сзданных директорий (в виде массива)
     *
     * @return array
     */
    public function getMakeDirResult(): array;
}
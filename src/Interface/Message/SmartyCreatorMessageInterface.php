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
     * @return array
     */
    public function getData(): array;

    /**
     * Получить данные из кинопоиска
     *
     * @return array
     */
    public function getKinopoiskData(): array;

    /**
     * Получить список сзданных директорий (в виде массива)
     *
     * @return array
     */
    public function getMakeDirResult(): array;
}
<?php

namespace App\Message;

/**
 * Message SmartyContentCreator
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array getKinopoiskData()
 * @method integer getSelectedDisk()
 * @version 1.0.0
 */
class SmartyContentCreatorMessage 
{

    public function __construct(private $getKinopoiskData, private $selectedDisk)
    {
    }


    /**
     * Получить данные из кинопоиска
     *
     */
    public function getKinopoiskData()
    {
        return $this->getKinopoiskData;
    }

    public function getSelectedDisk()
    {
        return $this->selectedDisk;
    }
}
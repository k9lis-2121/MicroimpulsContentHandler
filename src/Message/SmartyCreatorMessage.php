<?php

namespace App\Message;
use App\Interface\Message\SmartyCreatorMessageInterface;

/**
 * Message SmartyCreator
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array getData()
 * @method array getKinopoiskData()
 * @method array getMakerDirResult()
 * @version 1.0.0
 */
class SmartyCreatorMessage implements SmartyCreatorMessageInterface
{

    public function __construct(private $data, private $getKinopoiskData, private array $makeDirResult = [], private $selectedDisk)
    {
    }

    /**
     * Получить все данные оптравленные воркеру
     *
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Получить данные из кинопоиска
     *
     */
    public function getKinopoiskData()
    {
        return $this->getKinopoiskData;
    }

    /**
     * Получить список сзданных директорий (в виде массива)
     *
     * @return array
     */
    public function getMakeDirResult(): array
    {
        return $this->makeDirResult;
    }

    public function getSelectedDisk(){
        return $this->selectedDisk;
    }
}
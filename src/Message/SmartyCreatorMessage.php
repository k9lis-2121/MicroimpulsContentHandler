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

    public function __construct(private array $data = [], private array $getKinopoiskData = [], private array $makeDirResult = [])
    {
    }

    /**
     * Получить все данные оптравленные воркеру
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Получить данные из кинопоиска
     *
     * @return array
     */
    public function getKinopoiskData(): array
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
}
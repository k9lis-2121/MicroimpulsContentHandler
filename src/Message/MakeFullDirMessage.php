<?php

namespace App\Message;

use App\Interface\Message\MakeFullDirMessageInterface;

/**
 * Message MakeFullDir
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array getData()
 * @version 1.0.0
 */
class MakeFullDirMessage implements MakeFullDirMessageInterface
{

    /**
     * Конструктор
     *
     * @param array $data
     */
    public function __construct(private array $data = [])
    {
    }

    /**
     * Получить все данные переданные воркеру
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
<?php

namespace App\Interface\Message;

/**
 * Message interface
 * 
 * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
 * @method array getData()
 * @version 1.0.0
 */
interface MakeFullDirMessageInterface
{
    /**
     * Получить все данные переданные воркеру
     *
     * @return array
     */
    public function getData(): array;
}
<?php

namespace App\Interface\Service\Utils;

/**
 * Интерфейс для получения свободного диска
 * @method int getFreeDisk()
 */
interface CheckFreeDiskInterface
{
    /**
     * Получить самый свободный диск
     *
     * @return integer
     */
    public function getFreeDisk(): int;
}
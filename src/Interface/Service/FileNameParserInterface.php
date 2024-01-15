<?php

namespace App\Interfaces\Service;

interface FileNameParserInterface
{
    /**
     * Получить очищенное имя файла
     *
     * @param string $fileName
     * @return array
     */
    public function getClearName(string $fileName): array;

    /**
     * Получить результат парсинга
     *
     * @param string $fileName
     * @return array
     */
    public function getParsingName(string $fileName): array;
}
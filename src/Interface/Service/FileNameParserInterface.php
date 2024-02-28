<?php

namespace App\Interfaces\Service;

/**
 * Парсер и чистильщик строк
 * 
 * @author Валерий Ожерельев 
 * @method array getClearName()
 * @method array getParsingName()
 * @version 1.0.0
 */
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
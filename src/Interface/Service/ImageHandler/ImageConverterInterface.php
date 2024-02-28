<?php

namespace App\Interface\Service\ImageHandler;

/**
* @author Валерий Ожерельев 
* @method string convertToJpg()
* @version 1.0.0
*/
interface ImageConverterInterface
{
    /**
     * Конвертировать через api convertio
     *
     * @param string $url ссылка на изображение
     * @return string Возвращает ссылку или путь к изображению
     */
    public function convertToJpg(string $url): string;
}
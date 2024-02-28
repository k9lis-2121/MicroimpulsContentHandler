<?php

namespace App\Interface\Service;

/**
 * Класс для обработки массива полученного из info.txt
 * 
 * @author Валерий Ожерельев 
 * @method void addContentData()
 * @version 1.0.0
 */
interface FileInfoLoaderInterface
{
    /**
     *  Метод для добавления JSON файла info.txt перобразованного в массив, с описанием данных о видеофайле
     *
     * @param array $info
     * @return void
     */
    public function addContentData(array $info): void;
}
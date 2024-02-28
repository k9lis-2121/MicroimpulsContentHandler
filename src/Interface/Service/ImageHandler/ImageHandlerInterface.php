<?php

namespace App\Interface\Service\ImageHandler;

/**
 * Класс для управления конвертацией изображений локально или через api
* @author Валерий Ожерельев 
* @method string imageConvert()
* @version 1.0.0
*/
interface ImageHandlerInterface
{
    /**
    * @param string $img Ссылка на изображение или путь к файлу обязательный параметр
    * @param string $converter default или convertio - испоьзовать встроенный конвертер php или api convertio (лимит 25 изображений в сутки) значение по умолчанию default можно оставить пустым
    * @param string $from значение по умолчанию webp можно оставить пустым
    * @param string $to значение по умолчанию jpg можно оставить пустым
    * @return string Возвращает путь до готового изображения
    */
    public function imageConvert(string $img, string $converter, string $from, string $to): string;

    /**
     * Класс для загрузки изображения в смарти
     *
     * @param integer $videoId
     * @param string $sourcePath
     * @return string
     */
    public function loadImageToSmarty(int $videoId, string $sourcePath): bool;

    public function loadActorImageToSmarty(string $id): string;
}
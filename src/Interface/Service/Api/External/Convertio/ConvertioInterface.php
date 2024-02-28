<?php

namespace App\Interface\Service\Api\External\Convertio;

    /**
     * Конвертация изображений через сервис convertio (лимит 25 изобрвжений в сутки)
    * @author Валерий Ожерельев 
    * @api convertio
    * @method string convertioToJpg()
    * @version 1.0.0
    */
interface ConvertioInterface
{
    /**
     * Конвертация картинки в JPG с помощью сервиса convertio (лимит 25 изобрвжений в сутки)
     *
     * @param string $image_url ссылка на кортинку которую нужно передать convertio для конвертации
     * @return string Возвращает полный путь до нового файла, или текст ошибки
     */
    public function convertioToJpg(string $image_url): string;
}
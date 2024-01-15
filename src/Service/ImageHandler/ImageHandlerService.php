<?php

namespace App\Service\ImageHandler;

use App\Interface\Service\ImageHandler\ImageHandlerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Service\ImageHandler\ImageConverterService;
use App\Service\ImageHandler\ImageDirectoryHelperService;
use App\Service\Api\External\Convertio\ConvertioService;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;

/**
 * Класс для управления конвертацией изображений локально или через api
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @method string imageConvert()
* @version 1.0.0
*/
class ImageHandlerService implements ImageHandlerInterface
{
    private $publicPath;
    private $converterDefault;
    private $converterApi;
    private $imgDirHelper;
    private $convertedFile;

    public function __construct(KernelInterface $kernel, ImageConverterService $converterDefault, ConvertioService $converterApi, ImageDirectoryHelperService $imgDirHelper)
    {
        $this->publicPath = $kernel->getProjectDir();
        $this->converterDefault = $converterDefault;
        $this->converterApi = $converterApi;
        $this->imgDirHelper = $imgDirHelper;
    }

    private function processFileWithExiftool(string $filePath): string
    {
        $output = new ConsoleOutput();
        // Получаем тип файла с помощью консольной команды exiftool
        $process = new Process(['exiftool', '-FileTypeExtension', '-s', '-s', $filePath]);
        $process->run();
    
        $newDirectoryName = $this->publicPath . '/public/img/tmp';
        if (!$process->isSuccessful()) {
            $output->writeln('Не удалось получить тип файла.');
            return '';
        }
    
        $outputLines = explode("\n", trim($process->getOutput()));
        $fileType = trim(end($outputLines));
        $fileType = explode('FileTypeExtension: ', $fileType);
        dump($fileType);
        $fileType = $fileType[1];
    
        if (empty($fileType)) {
            $output->writeln('Не удалось получить тип файла.');
            return '';
        }
        // Генерируем новое имя файла на основе типа файла
        $newFileName = uniqid('file_') . '.' . $fileType;
    
        // Полный путь до нового файла в новой директории
        $newFilePath = $newDirectoryName . '/' . $newFileName;
    
        // Создаем копию файла
        copy($filePath, $newFilePath);
    
        // Переименуем файл
        rename($newFilePath, $newDirectoryName . '/' . $newFileName);
    
        $output->writeln('Файл успешно обработан и переименован.');
    
        return $newDirectoryName . '/' . $newFileName;
    }

    /**
    * @param string $url путь к изображению для скачивания
    * @return string путь до файла
    */
    private function douwnloadImage($url): string
    {
        // Получаем содержимое файла
        $fileContents = file_get_contents($url);

        // Генерируем уникальное имя файла
        $uniqueFileName = 'pb_poster';//uniqid('pb_');

        // Получаем расширение файла из заголовков ответа сервера
        $extension = '';
        $headers = get_headers($url);
        if ($headers && preg_match('/\bContent-Type: ([\w\/\.\-]+)/', implode("\n", $headers), $matches)) {
            $contentType = $matches[1];
            $extension = pathinfo($contentType, PATHINFO_EXTENSION);
        }

        // Добавляем расширение к имени файла, если оно доступно
        if (!empty($extension)) {
            $uniqueFileName .= '.' . $extension;
        }

        // Полный путь до файла
        $filePath = $this->publicPath . '/public/img/tmp/' . $uniqueFileName;

        dump($filePath);

        // Сохраняем файл
        file_put_contents($filePath, $fileContents);

        $newFilePath = $this->processFileWithExiftool($filePath);
        unlink($filePath);
        dump($newFilePath);
        $this->convertedFile = $newFilePath;
        return $newFilePath;
    }

    /**
    * @param string $img Ссылка на изображение или путь к файлу обязательный параметр
    * @param string $converter default или convertio - испоьзовать встроенный конвертер php или api convertio (лимит 25 изображений в сутки) значение по умолчанию default можно оставить пустым
    * @param string $from значение по умолчанию webp можно оставить пустым
    * @param string $to значение по умолчанию jpg можно оставить пустым
    * @return string возвращается путь до изображения или ошибка в виде строки
    */
    public function imageConvert(string $img, string $converter = 'dafault', string $from = 'webp', string $to = 'jpg'): string
    {
        if($converter == 'default'){
            $pathToImg = $this->douwnloadImage($img);
            $converting = $this->converterDefault->convertToJpg($pathToImg);
            dump('convertToJpg');
            dump($converting);
            return $converting;
        }elseif($converter == 'convertio'){
            $converting = $this->converterApi->convertioToJpg($img);
            dump('convertertioToJPG');
            dump($converting);
            return $converting;
        }else{
            dump($img);
            dump($converter);
            return 'Что-то пошло не так';
        }
        
    }

    /**
     * Загрузка изображения в смарти
     *
     * @param integer $videoId
     * @param string $sourcePath
     * @return boolean
     */
    public function loadImageToSmarty(int $videoId, string $sourcePath): bool
    {
        dump('source');
        dump($this->convertedFile);
        $targetDirectory = $this->imgDirHelper->createDirectory($videoId);
        $this->imgDirHelper->moveImagesToDirectory($this->convertedFile, $targetDirectory, 'pb_poster.jpg');
        return true;
    }


    /**
     * Загрузка изображения в смарти
     *
     * @param integer $videoId
     * @param string $sourcePath
     * @return boolean
     */
    public function loadActorImageToSmarty(string $id): string
    {
        dump('source');
        dump($this->convertedFile);
        $targetDirectory = '/mnt/adddata/panel_v3/public/img/mnt/smarty/tvmiddleware/actors';
        $this->imgDirHelper->moveImagesToDirectory($this->convertedFile, $targetDirectory, $id.'.png');

        $exp = explode('/', $this->convertedFile);
        return end($exp);
    }
}
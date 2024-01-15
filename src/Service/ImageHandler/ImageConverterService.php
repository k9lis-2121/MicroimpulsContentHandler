<?php

namespace App\Service\ImageHandler;

use App\Interface\Service\ImageHandler\ImageConverterInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @method string convertToJpg()
* @version 1.0.0
*/
class ImageConverterService implements ImageConverterInterface
{
    private $publicPath;

    public function __construct(KernelInterface $kernel)
    {
        $this->publicPath = $kernel->getProjectDir();
    }

    private function generateImageUrl($filename)
    {
        return '/img/tmp/' . $filename;
    }

    /**
    * @param $filePath Путь до конвыертируемого файла
    * @return string Путь или ссылка до получившегося файла
    */
    public function convertToJpg(string $filePath): string
    {

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $supportedExtensions = ['webp', 'png', 'gif']; // Добавьте поддерживаемые расширения сюда

        if (in_array($extension, $supportedExtensions)) {

            // Создаем ресурс изображения в соответствии с расширением
            if ($extension === 'webp') {
                $newImage = imagecreatefromwebp($filePath);
                unlink($filePath);
            } elseif ($extension === 'png') {
                $newImage = imagecreatefrompng($filePath);
                unlink($filePath);
            } elseif ($extension === 'gif') {
                $newImage = imagecreatefromgif($filePath);
                unlink($filePath);
            }

            // Получаем ресурс изображения .webp
            dump($filePath);
            $webpImage = imagecreatefromwebp($filePath);
            $filename = 'pb_poster';// uniqid('file_');
            // Создаем пустой холст для нового изображения в формате .jpg
            $newImage = imagecreatetruecolor(imagesx($webpImage), imagesy($webpImage));
            // Путь для сохранения преобразованного изображения в директории public
            $savePath = $this->publicPath . '/img/tmp/' . $filename . '.'.$extension;
            
            // Копируем изображение и преобразуем его в формат .jpg с максимальным сжатием (качество 100)
            imagecopy($newImage, $webpImage, 0, 0, 0, 0, imagesx($webpImage), imagesy($webpImage));
            imagejpeg($newImage, $savePath, 100);
            
            // Освобождаем память, освобождаем ресурсы
            imagedestroy($webpImage);
            imagedestroy($newImage);


            // Полный URL для доступа к сохраненному изображению
            $imageUrl = $this->generateImageUrl($filename);

            return $imageUrl;
        } else {
            // Файл не требует конвертации, возвращаем его исходный путь
            return $filePath;
        }
    }

    
}
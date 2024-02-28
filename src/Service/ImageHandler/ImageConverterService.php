<?php

namespace App\Service\ImageHandler;

use App\Interface\Service\ImageHandler\ImageConverterInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
* @author Валерий Ожерельев 
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
        $supportedExtensions = ['webp', 'png', 'gif']; 

        if (in_array($extension, $supportedExtensions)) {

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

            dump($filePath);
            $webpImage = imagecreatefromwebp($filePath);
            $filename = 'pb_poster';// uniqid('file_');
            $newImage = imagecreatetruecolor(imagesx($webpImage), imagesy($webpImage));
            $savePath = $this->publicPath . '/img/tmp/' . $filename . '.'.$extension;
            
            imagecopy($newImage, $webpImage, 0, 0, 0, 0, imagesx($webpImage), imagesy($webpImage));
            imagejpeg($newImage, $savePath, 100);
            
            imagedestroy($webpImage);
            imagedestroy($newImage);


            $imageUrl = $this->generateImageUrl($filename);

            return $imageUrl;
        } else {
            return $filePath;
        }
    }

    
}
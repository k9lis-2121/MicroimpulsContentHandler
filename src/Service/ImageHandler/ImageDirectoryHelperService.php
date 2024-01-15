<?php

namespace App\Service\ImageHandler;

use App\Interface\Service\ImageHandler\ImageDirectoryHelperInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\KernelInterface;


class ImageDirectoryHelperService implements ImageDirectoryHelperInterface
{

    private $smartyImageDir;
    private $publicPath;
    public $smartyActorImageDir;

    public function __construct(#[Autowire('%env(SMARTY_IMAGE_POSTERS_DIR)%')] $smartyImageDir, #[Autowire('%env(SMARTY_IMAGE_ACTORS_DIR)%')] $smartyActorImageDir, KernelInterface $kernel)
    {
        $this->publicPath = $kernel->getProjectDir();
        $this->smartyImageDir = $this->publicPath.$smartyImageDir;
        $this->smartyActorImageDir = $this->smartyActorImageDir;
    }

    /**
     * Создание директории по ID видео смарти
     *
     * @param string $directoryName
     * @return string
     */
    public function createDirectory(string $directoryName): string
    {
        $targetDirectory = $this->smartyImageDir . DIRECTORY_SEPARATOR . $directoryName;

        $filesystem = new Filesystem();
        $filesystem->mkdir($targetDirectory);

        return $targetDirectory;
    }

    /**
     * Перемещения изображения в заданную директорию и удаление временного
     *
     * @param string $sourceDirectory
     * @param string $targetDirectory
     * @return void
     */
    public function moveImagesToDirectory(string $sourceDirectory, string $targetDirectory, string $filename): void
    {
        $filesystem = new Filesystem();
        dump('sourse -> '.$sourceDirectory);
        dump('target -> '.$targetDirectory."/$filename");
        $filesystem->copy($sourceDirectory, $targetDirectory."/$filename", true);

        $filesystem->remove($sourceDirectory);
    }

}
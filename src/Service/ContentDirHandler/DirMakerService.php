<?php

namespace App\Service\ContentDirHandler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\KernelInterface;


class DirMakerService 
{

    private $filesystem;
    private $vodDir;

    public function __construct(
            #[Autowire('%env(VOD_CONTENT_DIR)%')] $vodDir,
            KernelInterface $kernel,
            Filesystem $filesystem,
        )
    {
        $this->filesystem = $filesystem;
        $this->vodDir = $vodDir;
    }

    private function makeContentDir($path){

        $filesystem = new Filesystem();
        try {

            if ($filesystem->exists($path)) {
                $result = [
                    'status' => true,
                    'message' => 'Директория существует!',
                    'dir' => $path,
                ];
                
                return $result;
            } else {
                $filesystem->mkdir($path);
                
                $result = [
                    'status' => true,
                    'message' => 'Директория успешно создана!',
                    'dir' => $path,
                ];
                
                return $result;
            }
        } catch (\Exception $e) {
            $result = [
                'status' => true,
                'message' => 'Ошибка при создании директории: ' . $e->getMessage(),
                'dir' => $path,
            ];
            
            return $result;
        }
    }

    private function setTemplate($data, $template){
        $dirData = [
            'base_dir' => $this->vodDir,
            'kp_id' => $data['kinopoiskId'],
            'name' => $data['title'],
        ];

        if($data['isSerial']){
            $dirData['type'] = 'Season';
        }else{
            $dirData['type'] = 'Movie';
        }

        if(isset($data['season'])){
            $dirData['season'] = $data['season'];
            $dirData['episode'] = $data['episode'];
        }

        if(isset($data['trailer'])){
            $dirData['trailer'] = $data['trailer'];
        }

        dump('SET TEMPLATE');
        dump($template);

        $parsedTemplate = strtr('{base_dir}/'.$template, $dirData);
        $parsedTemplate = str_replace('{', '', $parsedTemplate);
        $parsedTemplate = str_replace('}', '', $parsedTemplate);
        $dirs = explode('/', $parsedTemplate);
        unset($dirs[0]);

        $result = ['fullPath' => $parsedTemplate, 'pathArray' => $dirs];
        return $result;
    }

    public function infoFileLoader($file, $parsedTemplate)
    {
        $fileContent = $file->getContent();
        $filename = 'info.txt';
        $filePath = $parsedTemplate . '/' . $filename;
    
        if (file_exists($filePath)) { 

            if (!unlink($filePath)) {
                $result = [
                    'status' => false,
                    'message' => 'Ошибка при удалении файла!',
                    'dir' => $filePath,
                ];
                return $result;
            }
        }
        try {
            $this->filesystem->dumpFile($filePath, $fileContent);
            $result = [
                'status' => false,
                'message' => 'Файл успешно сохранен!',
                'dir' => $filePath,
            ];
            return $result;
        } catch (\Exception $e) {
            $result = [
                'status' => true,
                'message' => 'Ошибка при ссохранении файла: ' . $e->getMessage(),
                'dir' => $filePath,
            ];
            
            return $result;
        }
    }

    public function dirCreateSE($data, $parsedTemplate){
        foreach($data['episodesCount'] as $season => $episode){
            for($i2=1; $i2 <= $episode; $i2++){
                $dataS = [
                    'season' => $season,
                    'episode' => $i2,
                ];
                $dataMerge = array_merge($data, $dataS);
                $seasonTmp = $this->setTemplate($dataMerge, $parsedTemplate['season']);
                $result[] = $this->makeContentDir($seasonTmp['fullPath']);
            }
        }
        return $result;
    }


    public function makeBaseDir($data, $template){
        $tmp = $this->setTemplate($data, $template['movie']);
        $makeResult = $this->makeContentDir($tmp['fullPath']);
        return $makeResult;
    }



    public function makeTraillerDir($data, $parsedTemplate){
        $dataS = [
            'trailler' => 'trailler',
        ];
        $dataMerge = array_merge($data, $dataS);
        $seasonTmp = $this->setTemplate($dataMerge, $parsedTemplate);
        $result = $this->makeContentDir($seasonTmp['fullPath']);
        return $result;
    }

    public function makeScreenDir($path){
        $result = $this->makeContentDir($path);
        return $result;
    }

}
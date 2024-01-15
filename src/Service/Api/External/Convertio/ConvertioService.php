<?php

namespace App\Service\Api\External\Convertio;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Interface\Service\Api\External\Convertio\ConvertioInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\KernelInterface;

    /**
     * Конвертация изображений через сервис convertio (лимит 25 изобрвжений в сутки)
    * @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
    * @api convertio
    * @method string convertioToJpg()
    * @version 1.0.0
    */
class ConvertioService implements ConvertioInterface
{
    private $convertio_token;
    private $httpClient;
    private $projectDir;

    public function __construct(
        #[Autowire('%env(CONVERTIO_TOKEN)%')] $convertio_token, HttpClientInterface $httpClient, KernelInterface $kernel)
    {
        $this->convertio_token = $convertio_token;
        $this->httpClient = $httpClient;
        $this->projectDir = $kernel->getProjectDir();
    }

    private function convertFile(string $fileUrl): string
    {
        $data = [
            'apikey' => $this->convertio_token,
            'file' => $fileUrl,
            'outputformat' => 'jpg',
        ];

        $response = $this->httpClient->request('POST', 'http://api.convertio.co/convert', [
            'body' => json_encode($data),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);
        $id = $responseData['data']['id'];

        return $id;
    }

    private function checkConversionStatus(string $conversionId): ?string
    {
        $url = 'http://api.convertio.co/convert/' . $conversionId . '/status';

        do {
            $response = $this->httpClient->request('GET', $url);
            $responseData = json_decode($response->getContent(), true);
            $stepPercent = $responseData['data']['step_percent'];

            if ($stepPercent === 100) {
                return $responseData['data']['output']['url'];
            }

            sleep(1);
        } while ($stepPercent < 100);

        return null;
    }

    private function downloadAndSaveFile(string $fileUrl): ?string
    {
        $filename = basename($fileUrl);
        $savePath = $this->projectDir . '/public/img/tmp/' . $filename;

        $response = $this->httpClient->request('GET', $fileUrl);
        $fileContent = $response->getContent();

        if (file_put_contents($savePath, $fileContent) !== false) {
            return $filename;
        }

        return null;
    }

    /**
    * @param string $image_url ссылка на кортинку которую нужно передать convertio для конвертации
    * @return string Возвращает полный путь до нового файла, или текст ошибки
    */
    public function convertioToJpg($image_url): string
    {
        $id = $this->convertFile($image_url);
        if($id){
            $loadUrl = $this->checkConversionStatus($id);
        }else{
            dump($id);
            return 'Ошибка на стадии загрузки изображения';
        }
        if($loadUrl != null){
            $fileName = $this->downloadAndSaveFile($loadUrl);
        }else{
            dump($loadUrl);
            return 'Ошибка на стадии проверки статуса';
        }
        if($fileName != null){
            return $fileName;
        }else{
            dump($fileName);
            return 'Ошибка на этапе сохранения файла';
        }
    }
}
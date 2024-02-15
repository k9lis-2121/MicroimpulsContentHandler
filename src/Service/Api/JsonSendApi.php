<?php

namespace App\Service\Api;

use Symfony\Component\HttpClient\HttpClient;

class JsonSendApi
{
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function sendPostRequest($url, $data)
    {
        $response = $this->client->request(
            'POST',
            $url,
            [
                'json' => $data,
                'verify_peer' => false,
                'verify_host' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    // Другие заголовки, если они нужны
                ],
            ]
        );

        return $response->toArray();
    }
}

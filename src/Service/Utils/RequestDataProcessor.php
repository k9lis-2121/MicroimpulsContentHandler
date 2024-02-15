<?php

namespace App\Service\Utils;

use App\Interface\Service\Utils\RequestDataProcessorInterface;

class RequestDataProcessor implements RequestDataProcessorInterface
{
    public function processRequestData($request)
    {
        $contentType = $request->headers->get('Content-Type');
        $file = null;
        $data = [];

        if (0 === strpos($contentType, 'multipart/form-data')) {
            $file = $request->files->get('info.txt');
            $requestData = $request->request->get('data');
            
            if (!empty($requestData)) {
                $data = json_decode($requestData, true);
            }
        } elseif (0 === strpos($contentType, 'application/json')) {
            $rawData = $request->getContent();
            $data = json_decode($rawData, true);
        }

        return ['file' => $file, 'data' => $data];
    }
}
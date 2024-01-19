<?php

namespace App\Message;

class SmartyCreatorMessage
{

    public function __construct(private array $data = [], private array $getKinopoiskData = [], private array $makeDirResult = [])
    {
    }

    public function getData(): array
    {
        return $this->data;
    }
    public function getKinopoiskData(): array
    {
        return $this->getKinopoiskData;
    }

    public function getMakeDirResult(): array
    {
        return $this->makeDirResult;
    }
}
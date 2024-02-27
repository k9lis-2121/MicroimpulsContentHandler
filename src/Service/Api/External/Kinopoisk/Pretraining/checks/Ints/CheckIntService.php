<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\checks\Ints;
use App\Service\Api\External\Kinopoisk\Pretraining\checks\BaseMethod;

class CheckIntService
{
    private $base;

    public function __construct(BaseMethod $baseMethod)
    {
        $this->base = $baseMethod;
    }

    public function checkInt(string|null $property)
    {
        $this->base->checkBase($property, 'int');
    }
}
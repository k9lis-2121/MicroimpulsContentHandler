<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\checks\Strings;

use App\Service\Api\External\Kinopoisk\Pretraining\checks\BaseMethod;

class CheckStirngService
{
    private $base;

    public function __construct(BaseMethod $baseMethod)
    {
        $this->base = $baseMethod;
    }

    public function checkString(string|null $property)
    {
        $this->base->checkBase($property, 'str');
    }

}
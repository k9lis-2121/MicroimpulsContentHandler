<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\checks\Bools;

use App\Service\Api\External\Kinopoisk\Pretraining\checks\BaseMethod;

class CheckBoolService
{
    private $base;

    public function __construct(BaseMethod $baseMethod)
    {
        $this->base = $baseMethod;
    }

    public function checkBool($property)
    {
        $this->base->checkBase($property, 'bool');
    }
}
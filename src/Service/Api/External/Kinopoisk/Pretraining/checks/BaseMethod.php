<?php

namespace App\Service\Api\External\Kinopoisk\Pretraining\checks;
use App\DTO\KpDTO;

class BaseMethod
{
    private $kpDto;

    public function __construct(KpDTO $kpDTO)
    {
        $this->kpDto = $kpDTO;
    }

    public function checkBase(string|null $property, string $varType)
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);


        $value = $this->kpDto->{$getter}();

        if($varType == 'int')
        {
            $default = 0;
        }elseif($varType == 'str')
        {
            $default = 'Апи не отдал '.$property;
        }elseif($varType == 'bool')
        {
            $default = false;
        }

        if ($value === null) {
            $this->kpDto->{$setter}($default);
        }

    }
}
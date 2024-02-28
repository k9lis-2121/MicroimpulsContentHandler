<?php

namespace App\Service\Utils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoHandlerService
{

    private $goDir;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->goDir = $parameterBag->get("GO_BASE_DIR");
    }


    private function sendGo($script, $params)
    {
        $encodedString = base64_encode(mb_convert_encoding($params, 'UTF-8'));

        $command = "cd $this->goDir/ && ./$script " . $encodedString;
        $output = shell_exec($command);

        $result = mb_convert_encoding(base64_decode($output), 'UTF-8');

        return $result;
    }

    public function getClearUnicodeString($string)
    {
        return $this->sendGo('main', $string);
    }
}
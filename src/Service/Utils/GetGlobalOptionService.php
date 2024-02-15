<?php

namespace App\Service\Utils;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GetGlobalOptionService
{
    public function __construct(ParameterBagInterface $parameterBag)
    {
        
        $this->transcodeEndpoint = $parameterBag->get('TRANSCODE_ENDPOINT');
        dump($this->transcodeEndpoint);
    }

    public function getTranscodeEndPoint(){
        return $this->transcodeEndpoint;
    }
}
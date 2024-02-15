<?php

namespace App\Service\Api\Inside\Search;

class SearchParamIsseterService
{
    public function getIssetParam(array $params): array
    {
        $issetParams = [];
        foreach ($params as $key => $value) {
            if($value){
                $issetParams[$key] = $value;
            }
        }
        return $issetParams;
    }
}
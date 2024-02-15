<?php

namespace App\Service\Utils;

class SemanticSearchService
{
    public function calculateJaccardIndex($searchTerm, $value)
    {
        $inputVector = str_split(mb_strtolower($searchTerm));
        $valueVector = str_split(mb_strtolower($value));
        
        similar_text(implode('', $inputVector), implode('', $valueVector), $percentage);
        
        $jaccardIndex = $percentage / 100;
        
        return $jaccardIndex;
    }
}
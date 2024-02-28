<?php

namespace App\Service\Cleaner;

use App\Interface\Service\Cleaner\StringCleanerInterface;
use App\Service\Utils\GoHandlerService;

/**
* @author Валерий Ожерельев 
* @method string CleanFull()
* @method string CleanName()
* @method string CleanDescription()
* @version 1.0.0
*/
class StringCleanerService implements StringCleanerInterface
{

    private $goHandler;

    public function __construct(GoHandlerService $goHandlerService)
    {
        $this->goHandler = $goHandlerService;
    }

    private function stringConvert($str): string
    {
        $str = mb_convert_encoding($str, 'UTF-8', 'auto');
        return $str;
    }

    private function replaceBreveLetters($inputString) {
        $result = $this->goHandler->getClearUnicodeString($inputString);     
        return $result;
    }

    /**
    * @param string $str Строка которую нужно обработать
    * @param string $type Full|Name|Description Тип применяемой обработки
    * @return string Обработанная строка
    */
    private function stringReplacer($str, $type): string
    {
        if($type == 'Full'){
            $str = preg_replace('/[^a-zA-Zа-яА-ЯЁё0-9_\. ]/u', '', $this->replaceBreveLetters($str));
        }elseif($type == 'Name'){
            $str = preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_\.: ]/u', '', $this->replaceBreveLetters($str));
        }elseif($type == 'Description'){
            $str = preg_replace('/[^a-zA-Zа-яА-ЯЁё0-9_\.;\n\?\-, ]/u', '', $this->replaceBreveLetters($str));
        }
        return $str;
    }

    /**
    * @param string $str Строка которую нужно обработать
    * @param string $type Full|Name|Description Тип применяемой обработки
    * @return string Обработанная строка
    */
    private function getClean($str, $type): string
    {
        return $this->stringReplacer($this->stringConvert($str), $type);
    }

    /**
    * @param string|null $str строка которую нужно очистить
    * @return string Возвращает полностью очищенную строку
    */
    public function CleanFull(string|null $str): string
    {
        if($str == null){
            return '';
        }
        $str = $this->getClean($str, 'Full');
        $str = str_replace('.', '_', $str);

        return $str;
    }
    
    /**
    * @param string|mull $str строка которую нужно очистить
    * @retrun string Возвращает очищеную строку, но допускает некоторые символы, например ":"
    */
    public function CleanName(string|null $str): string
    {
        if($str == null){
            return 'Нет данных';
        }
        $str = $this->getClean($str, 'Name');
        return $str;
    }

    /**
    * @param string|null $str строка которую нужно очистить
    * @retrun string Возвращает очищеную строку, но допускает некоторые символы, например ":"
    */
    public function CleanDescription(string|null $str): string
    {        
        if($str == null){
            return '';
        }
        $str = $this->getClean($str, 'Description');
        return $str;
    }
}
<?php

namespace App\Service\Api\External\Kinopoisk;

use App\Interface\Service\Api\External\Kinopoisk\PretrainingDataInterface;
/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method object dataProcessing()
* @version 1.0.0
*/
class PretrainingDataService implements PretrainingDataInterface
{

    /**
    * Преобразование массива из кинопоиска в строку для смарти в соответствии стребованиями API смарти
    * @param array $arr Массив который нужно преобразовать в строку
    * @return string Возвращает строку со значениями из массива через запятую
    */
    public function getArrToString(array $arr): string
    {
        $names = array_column($arr, 'name');
        $string = implode(', ', $names);
        return $string;
    }

     /**
    * Проверка на null если null вернуть 0, иначе возвращаем без изменений
    * @param mixed $arr Массив который нужно преобразовать в строку
    * @return mixed Возвращает строку со значениями из массива через запятую
    */
    public function notNull(mixed $data): mixed
    {
        if($data != null){
            return $data;
        }else{
            return 0;
        }
    }

     /**
     * Проверяем является ли контент сериалом
     *
     * @param string $type
     * @return integer возвращает 1 или 0, 1 - сериал 0 - фильм, не bool т.к. api требует число
     */
    public function isSeason(string $type): int
    {
        if($type == 'tv-series' || $type == 'animated-series'){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * Перебираем массив с альтернативными названиями для заполнения поля вторым именем
     *
     * @param array $names
     * @return string
     */
    public function getOrigName(array $names): string
    {
        foreach($names as $name){
            if(isset($name['language'])){
                if($name['language'] == 'US' || $name['language'] == 'EN'){
                    $orig_name = $name['name'];
                }
            }
        }

        if(!isset($orig_name)){
            if(isset($names[1]['name'])){
                $orig_name = $names[1]['name'];
            }elseif(isset($name[0]['name'])){
                $orig_name = $names[0]['name'];
            }else{
                $orig_name = 'No alter name content';
            }
        }

        return $orig_name;
    }

    /**
     * Массивом передаем короткое и длинное описание, если основное привышает 1000 символов, возврааем короткое
     *
     * @param array $descriptions
     * @example массив $descriptions['description' => 'Длинное описание', 'shortDescription' => 'Короткое описание']
     * @return string
     */
    public function lenghtDescriptionCheck(array $descriptions): string
    {
        if(strlen($descriptions['description']) > 1000){
            $description = $descriptions['shortDescription'];
        }else{
            $description = $descriptions['description'];
        }
        if($description == null){
            return 'Нет данных';
        }
        return $description;
    }

    /**
     * Перебираем массив с участниками для поиска имени с должностью "Директор"
     *
     * @param array $persons
     * @return string
     */
    public function searchFilmDirector(array $persons): string
    {
        foreach ($persons as $key => $value) {
            if ($value['enProfession'] === 'director') {
                // Массив с "enProfession" равным "director" найден
                $foundArray = $key;
                break;
            }
        }
        if(isset($foundArray)){
            $director = $persons[$foundArray]['name'];
        }

        return $director;
    }
    /**
     * Проверка требуется ли родительский контроль (1 если рейтинг > 18). Строковый тип, т.е. могут быть рейтинги pg, r и т.д.
     *
     * @param string|null $rating
     * @return integer
     */
    public function parentControll(string|null $rating): int
    {
        if($rating == null){
            $parentControl = 0;
        }
        if($rating >= 18){
            $parentControl = 1;
        }else{
            $parentControl = 0;
        }
        return $parentControl;
    }
}
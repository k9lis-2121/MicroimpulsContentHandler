<?php

namespace App\Interface\Service\Api\External\Kinopoisk;

/**
* @author Валерий Ожерельев <ozherelev_va@mycentera.ru>
* @api Кинопоиск
* @method string getArrToString()
* @method mixed notNull()
* @method integer isSeason()
* @method string getOrigName()
* @method string lenghtDescriptionCheck()
* @method string searchFilmDirector()
* @version 1.0.0
*/
interface PretrainingDataInterface
{
    /**
     * Преобразование массива из кинопоиска в строку для смарти в соответствии стребованиями API смарти
    * @param array $arr Массив который нужно преобразовать в строку
    * @return string Возвращает строку со значениями из массива через запятую
    */
    public function getArrToString(array $arr): string;

    /**
    * Проверка на null если null вернуть 0, иначе возвращаем без изменений
    * @param mixed $arr Массив который нужно преобразовать в строку
    * @return mixed Возвращает строку со значениями из массива через запятую
    */
    public function notNull(mixed $data): mixed;

    /**
     * Проверяем является ли контент сериалом
     *
     * @param string $type
     * @return integer возвращает 1 или 0, 1 - сериал 0 - фильм, не bool т.к. api требует число
     */
    public function isSeason(string $type): int;

    /**
     * Перебираем массив с альтернативными названиями для заполнения поля вторым именем
     *
     * @param array $names
     * @return string
     */
    public function getOrigName(array $names): string;

    /**
     * Массивом передаем короткое и длинное описание, если основное привышает 1000 символов, возврааем короткое
     *
     * @param array $descriptions
     * @example массив $descriptions['description' => 'Длинное описание', 'shortDescription' => 'Короткое описание']
     * @return string
     */
    public function lenghtDescriptionCheck(array $descriptions): string;

    /**
     * Перебираем массив с участниками для поиска имени с должностью "Директор"
     *
     * @param array $persons
     * @return string
     */
    public function searchFilmDirector(array $persons): string;

    /**
     * Проверка требуется ли родительский контроль (1 если рейтинг > 18). Строковый тип, т.е. могут быть рейтинги pg, r и т.д.
     *
     * @param string|null $persons
     * @return integer
     */
    public function parentControll(string|null $rating): int;
}

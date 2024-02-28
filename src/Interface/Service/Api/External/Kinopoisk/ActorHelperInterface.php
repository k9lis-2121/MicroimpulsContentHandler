<?php

namespace App\Interface\Service\Api\External\Kinopoisk;

/**
* @author Валерий Ожерельев 
* @api Кинопоиск
* @method object getIdActor()
* @version 1.0.0
*/
interface ActorHelperInterface
{

    /**
     * Поиск или добавление актера в базе смарти, возвращает его id в смарти
     *
     * @param string $name
     * @param string $origName
     * @param string $img
     * @param integer $kinopoiskId
     * @return integer|null
     */
    public function getIdActor(string $name, string $origName, string $img, int $kinopoiskId): ?int;

}
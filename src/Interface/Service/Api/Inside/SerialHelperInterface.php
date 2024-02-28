<?php

namespace App\Interface\Service\Api\Inside;

/**
 * Класс управляет созданием сезнов, эпизодов и ассетов в смарти
 * @author Валерий Ожерельев 
 * @method bool makeSeasonAndEpisodeInSmarty()
 * @version 1.0.0
 */
interface SerialHelperInterface
{
    /**
     * Метод для создания сезонов, серий и ассетов в смарти
     *
     * @param array $data
     * @param integer $createdVideoResponse
     * @param array $result
     * @return boolean
     */
    public function makeSeasonAndEpisodeInSmarty(array $data, int $createdVideoResponse, array $result): bool;
}